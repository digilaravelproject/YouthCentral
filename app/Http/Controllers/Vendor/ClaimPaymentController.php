<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessClaim;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClaimPaymentController extends Controller
{
    protected $razorpayService;
    
    public function __construct(RazorpayService $razorpayService)
    {
        $this->middleware('auth');
        $this->middleware('role:vendor');
        $this->razorpayService = $razorpayService;
    }
    
    /**
     * Show payment page for a business claim
     */
    public function checkout($claimId)
    {
        $claim = BusinessClaim::where('user_id', Auth::id())
            ->with('business')
            ->findOrFail($claimId);
        
        // Check claim status
        if ($claim->status !== 'pending') {
            return redirect()->route('vendor.claims.myClaims')
                ->with('error', 'Only pending claims can be processed for payment.');
        }
        
        // Set a fixed claim fee (you can adjust this or make it dynamic)
        $claimFee = 500.00; // ₹500
        
        // Create a unique receipt ID
        $receiptId = 'claim_' . Str::random(10);
        
        // Store payment details in session for verification later
        session([
            'claim_payment' => [
                'claim_id' => $claim->id,
                'amount' => $claimFee,
                'receipt_id' => $receiptId,
                'user_id' => Auth::id(),
                'business_id' => $claim->business_id,
            ]
        ]);
        
        try {
            // Create Razorpay order
            $orderData = $this->razorpayService->createOrder(
                $claimFee,
                $receiptId,
                [
                    'claim_id' => $claim->id,
                    'user_id' => Auth::id(),
                    'business_id' => $claim->business_id,
                    'business_name' => $claim->business->business_name,
                ]
            );
            
            // Return checkout view with Razorpay details
            return view('vendor.claims.payment', [
                'claim' => $claim,
                'user' => Auth::user(),
                'razorpayId' => config('services.razorpay.key'),
                'amount' => $claimFee * 100, // amount in paise
                'currency' => 'INR',
                'orderId' => $orderData['id'],
                'receiptId' => $receiptId,
                'claimFee' => $claimFee,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create Razorpay order for business claim: ' . $e->getMessage());
            return redirect()->route('vendor.claims.myClaims')
                ->with('error', 'Failed to initialize payment process. Please try again later.');
        }
    }
    
    /**
     * Process payment callback from Razorpay
     */
    public function callback(Request $request)
    {
        $input = $request->all();
        
        // Verify if we have all necessary parameters
        if (!isset($input['razorpay_payment_id']) || !isset($input['razorpay_order_id']) || !isset($input['razorpay_signature'])) {
            return redirect()->route('vendor.claims.payment.failed')
                ->with('error', 'Payment failed. Required parameters missing.');
        }
        
        // Verify signature
        $isSignatureValid = $this->razorpayService->verifySignature(
            $input['razorpay_payment_id'],
            $input['razorpay_order_id'],
            $input['razorpay_signature']
        );
        
        if (!$isSignatureValid) {
            Log::error('Razorpay signature verification failed for business claim', $input);
            return redirect()->route('vendor.claims.payment.failed')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
        
        // Get the claim payment data from session
        $paymentData = session('claim_payment');
        if (!$paymentData) {
            Log::error('Claim payment session data missing');
            return redirect()->route('vendor.claims.payment.failed')
                ->with('error', 'Payment session expired. Please try again.');
        }
        
        try {
            // Start a transaction
            DB::beginTransaction();
            
            // Get payment details from Razorpay
            $paymentDetails = $this->razorpayService->fetchPayment($input['razorpay_payment_id']);
            
            // Get claim
            $claim = BusinessClaim::findOrFail($paymentData['claim_id']);
            
            // Update claim with payment information
            $claim->payment_id = $input['razorpay_payment_id'];
            $claim->payment_amount = $paymentData['amount'];
            $claim->payment_status = 'paid';
            $claim->payment_date = now();
            $claim->payment_details = $paymentDetails;
            $claim->save();
            
            // Commit transaction
            DB::commit();
            
            // Clear payment session
            session()->forget('claim_payment');
            
            // Redirect to success page
            return redirect()->route('vendor.claims.payment.success', ['claim' => $claim->id])
                ->with('success', 'Payment completed successfully! Your claim is now under review.');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            Log::error('Business claim payment processing failed: ' . $e->getMessage());
            
            return redirect()->route('vendor.claims.payment.failed')
                ->with('error', 'Failed to process payment. Please contact support.');
        }
    }
    
    /**
     * Show payment success page
     */
    public function success($claimId)
    {
        $claim = BusinessClaim::where('user_id', Auth::id())
            ->with('business')
            ->findOrFail($claimId);
        
        return view('vendor.claims.payment_success', compact('claim'));
    }
    
    /**
     * Show payment failed page
     */
    public function failed()
    {
        return view('vendor.claims.payment_failed');
    }
} 