<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\RazorpayService;
use App\Mail\SubscriptionPurchased;
use App\Mail\AdminNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RazorpayController extends Controller
{
    protected $razorpayService;
    
    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }
    
    /**
     * Show checkout page for a plan
     */
    public function checkout($planId)
    {
        try {
            $plan = Plan::findOrFail($planId);
            $user = Auth::user();
            
            // Create a unique receipt ID
            $receiptId = 'sub_' . Str::random(10);
            
            // Log the starting of checkout process
            Log::info('Starting Razorpay checkout process', [
                'plan_id' => $plan->id, 
                'user_id' => $user->id,
                'amount' => $plan->price,
                'receipt_id' => $receiptId
            ]);
            
            // Store order details in session for verification later
            session([
                'checkout' => [
                    'plan_id' => $plan->id,
                    'amount' => $plan->price,
                    'receipt_id' => $receiptId,
                    'user_id' => $user->id,
                ]
            ]);
            
            // Create Razorpay order
            $orderData = $this->razorpayService->createOrder(
                $plan->price,
                $receiptId,
                [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'plan_name' => $plan->name,
                    'user_email' => $user->email,
                ]
            );
            
            // Debug log
            Log::info('Razorpay order created in controller', ['order_data' => $orderData]);
            
            // Check if order creation was successful and has ID
            if (empty($orderData) || !isset($orderData['id'])) {
                Log::error('Razorpay order creation failed: No order ID returned', [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'response' => $orderData ?? 'Empty response'
                ]);
                
                return redirect()->back()->with('error', 'Unable to initialize payment. Please try again later.');
            }
            
            // Fetch categories for the public navbar -- REMOVED
            // $categories = \App\Models\Category::with('subcategories')->get();

            // --- TEMPORARY DEBUG LOGGING ---
            Log::debug('Passing data to razorpay.checkout view', [
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'razorpay_key_check' => config('services.razorpay.key') ? 'Key Found' : 'Key MISSING',
                'amount_paise' => $plan->price * 100,
                'currency' => 'INR',
                'generated_order_id' => $orderData['id'],
                'receipt_id' => $receiptId
            ]);
            // --- END DEBUG LOGGING ---

            // Return checkout view with Razorpay details
            return view('razorpay.checkout', [
                'plan' => $plan,
                'user' => $user,
                'razorpayId' => config('services.razorpay.key'),
                'amount' => $plan->price * 100, // amount in paise
                'currency' => 'INR',
                'orderId' => $orderData['id'],
                'receiptId' => $receiptId,
            ]);
        } catch (\Exception $e) {
            Log::error('Error initializing Razorpay checkout', [
                'message' => $e->getMessage(),
                'plan_id' => $planId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process payment callback from Razorpay
     */
    public function paymentCallback(Request $request)
    {
        $input = $request->all();
        
        // Verify if we have all necessary parameters
        if (!isset($input['razorpay_payment_id']) || !isset($input['razorpay_order_id']) || !isset($input['razorpay_signature'])) {
            return redirect()->route('subscription.failed')->with('error', 'Payment failed. Required parameters missing.');
        }
        
        // Verify signature
        $isSignatureValid = $this->razorpayService->verifySignature(
            $input['razorpay_payment_id'],
            $input['razorpay_order_id'],
            $input['razorpay_signature']
        );
        
        if (!$isSignatureValid) {
            Log::error('Razorpay signature verification failed', $input);
            return redirect()->route('subscription.failed')->with('error', 'Payment verification failed. Please contact support.');
        }
        
        // Get the checkout data from session
        $checkout = session('checkout');
        if (!$checkout) {
            Log::error('Checkout session data missing');
            return redirect()->route('subscription.failed')->with('error', 'Checkout session expired. Please try again.');
        }
        
        try {
            // Start a transaction
            DB::beginTransaction();
            
            // Get payment details from Razorpay
            $paymentDetails = $this->razorpayService->fetchPayment($input['razorpay_payment_id']);
            
            // Get plan and user
            $plan = Plan::findOrFail($checkout['plan_id']);
            $user = User::findOrFail($checkout['user_id']);
            
            // Cancel any existing active subscriptions for this user
            $activeSubscription = $user->activeSubscription();
            if ($activeSubscription) {
                $activeSubscription->status = 'cancelled';
                $activeSubscription->save();
            }
            
            // Calculate end date based on plan duration
            $startedAt = now();
            $endsAt = null;
            
            if ($plan->duration_type !== 'one-time') {
                if ($plan->duration_type === 'monthly') {
                    $endsAt = $startedAt->copy()->addMonths($plan->duration_value);
                } elseif ($plan->duration_type === 'yearly') {
                    $endsAt = $startedAt->copy()->addYears($plan->duration_value);
                }
            }
            
            // Create new subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'started_at' => $startedAt,
                'ends_at' => $endsAt,
                'amount_paid' => $plan->price,
                'payment_method' => 'razorpay',
                'transaction_id' => $input['razorpay_payment_id'],
                'payment_details' => $paymentDetails,
            ]);
            
            // Commit transaction
            DB::commit();
            
            // Send email notifications
            try {
                // Send email to vendor with invoice receipt
                Mail::to($user->email)->send(new SubscriptionPurchased($subscription));
                
                // Send notification to admin
                $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                if (!empty($adminEmails)) {
                    Mail::to($adminEmails)->send(new AdminNotification(
                        'New Subscription Purchase',
                        [
                            'type' => 'subscription',
                            'message' => "A new subscription has been purchased by {$user->name}",
                            'details' => [
                                'vendor_name' => $user->name,
                                'vendor_email' => $user->email,
                                'business_name' => $user->business_name,
                                'plan_name' => $plan->name,
                                'amount_paid' => '₹' . number_format($plan->price, 2),
                                'transaction_id' => $input['razorpay_payment_id'],
                                'subscription_id' => $subscription->id,
                                'started_at' => $startedAt->format('F j, Y g:i A'),
                                'ends_at' => $endsAt ? $endsAt->format('F j, Y g:i A') : 'Lifetime',
                            ],
                            'action_url' => route('admin.subscriptions.show', $subscription),
                            'action_text' => 'View Subscription'
                        ]
                    ));
                }
                
                // Send notification to host email (configured email)
                $hostEmail = config('mail.from.address');
                if ($hostEmail && $hostEmail !== 'hello@example.com') {
                    Mail::to($hostEmail)->send(new AdminNotification(
                        'Subscription Purchase Notification',
                        [
                            'type' => 'subscription',
                            'message' => "Subscription purchased for {$user->business_name}",
                            'details' => [
                                'vendor' => "{$user->name} ({$user->email})",
                                'business' => $user->business_name,
                                'plan' => $plan->name,
                                'amount' => '₹' . number_format($plan->price, 2),
                                'payment_id' => $input['razorpay_payment_id'],
                            ]
                        ]
                    ));
                }
                
                Log::info('Subscription purchase emails sent successfully', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id
                ]);
                
            } catch (\Exception $mailError) {
                Log::error('Failed to send subscription purchase emails', [
                    'error' => $mailError->getMessage(),
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id
                ]);
                // Don't fail the transaction for email errors
            }
            
            // Clear checkout session
            session()->forget('checkout');
            
            // Redirect to success page
            return redirect()->route('subscription.success', ['subscription' => $subscription->id])
                ->with('success', 'Subscription activated successfully!');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            Log::error('Subscription creation failed: ' . $e->getMessage());
            
            return redirect()->route('subscription.failed')
                ->with('error', 'Failed to process subscription. Please contact support.');
        }
    }
    
    /**
     * Show subscription success page
     */
    public function success($subscriptionId)
    {
        $subscription = Subscription::with(['user', 'plan'])->findOrFail($subscriptionId);
        
        // Ensure the subscription belongs to current user or admin is viewing
        if (Auth::user()->id !== $subscription->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        // Fetch categories for the public navbar -- REMOVED
        // $categories = \App\Models\Category::with('subcategories')->get();
        
        // return view('razorpay.success', compact('subscription', 'categories'));
        return view('razorpay.success', compact('subscription'));
    }
    
    /**
     * Show subscription failed page
     */
    public function failed()
    {
        // Fetch categories for the public navbar -- REMOVED
        // $categories = \App\Models\Category::with('subcategories')->get();
        // return view('razorpay.failed', compact('categories'));
        return view('razorpay.failed');
    }
    
    /**
     * Handle webhook from Razorpay
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature if you have configured a webhook
        $webhookSecret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();
        
        Log::info('Razorpay webhook received', [
            'event' => $request->input('event'), 
            'has_signature' => !empty($signature)
        ]);
        
        // Verify signature if webhook secret is configured
        if ($webhookSecret) {
            if (empty($signature)) {
                Log::error('Razorpay webhook missing signature header');
                return response('Signature header missing', 400);
            }
            
            $computedSignature = hash_hmac('sha256', $payload, $webhookSecret);
            if ($computedSignature !== $signature) {
                Log::error('Razorpay webhook signature verification failed', [
                    'computed' => $computedSignature,
                    'received' => $signature
                ]);
                return response('Signature verification failed', 400);
            }
            
            Log::info('Razorpay webhook signature verified');
        }
        
        // Process webhook based on event
        $input = $request->all();
        $event = $input['event'] ?? null;
        
        if (empty($event)) {
            Log::error('Razorpay webhook missing event parameter');
            return response('Event parameter missing', 400);
        }
        
        Log::info('Processing Razorpay webhook', ['event' => $event]);
        
        try {
            switch ($event) {
                case 'payment.captured':
                    // Payment was successful
                    $paymentId = $input['payload']['payment']['entity']['id'] ?? null;
                    $orderId = $input['payload']['payment']['entity']['order_id'] ?? null;
                    
                    if (!$paymentId || !$orderId) {
                        Log::error('Razorpay webhook missing payment or order ID', [
                            'payment_id' => $paymentId,
                            'order_id' => $orderId
                        ]);
                        return response('Missing payment or order ID', 400);
                    }
                    
                    // Find subscription with this transaction ID
                    $subscription = Subscription::where('transaction_id', $paymentId)->first();
                    
                    if ($subscription) {
                        // Update status if needed
                        if ($subscription->status !== 'active') {
                            $subscription->status = 'active';
                            $subscription->save();
                            
                            Log::info('Subscription activated via webhook', [
                                'subscription_id' => $subscription->id,
                                'payment_id' => $paymentId
                            ]);
                        }
                    } else {
                        // If not found by transaction ID, try to find by order ID
                        // This handles cases where the webhook arrives before callback
                        $subscription = Subscription::where('razorpay_order_id', $orderId)->first();
                        
                        if ($subscription) {
                            $subscription->status = 'active';
                            $subscription->transaction_id = $paymentId;
                            $subscription->save();
                            
                            Log::info('Subscription activated via webhook (found by order ID)', [
                                'subscription_id' => $subscription->id,
                                'order_id' => $orderId,
                                'payment_id' => $paymentId
                            ]);
                        } else {
                            Log::warning('Razorpay webhook: No subscription found for payment', [
                                'payment_id' => $paymentId,
                                'order_id' => $orderId
                            ]);
                        }
                    }
                    break;
                    
                case 'payment.failed':
                    // Payment failed
                    $paymentId = $input['payload']['payment']['entity']['id'] ?? null;
                    $orderId = $input['payload']['payment']['entity']['order_id'] ?? null;
                    $errorDescription = $input['payload']['payment']['entity']['error_description'] ?? 'Unknown error';
                    
                    Log::error('Razorpay payment failed', [
                        'payment_id' => $paymentId,
                        'order_id' => $orderId,
                        'error' => $errorDescription
                    ]);
                    
                    if ($paymentId) {
                        // Find subscription with this transaction ID
                        $subscription = Subscription::where('transaction_id', $paymentId)
                            ->orWhere('razorpay_order_id', $orderId)
                            ->first();
                            
                        if ($subscription) {
                            // Update status
                            $subscription->status = 'failed';
                            $subscription->save();
                            
                            Log::info('Subscription marked as failed via webhook', [
                                'subscription_id' => $subscription->id
                            ]);
                        }
                    }
                    break;
                    
                case 'order.paid':
                    // Order was paid - handle completed orders
                    $orderId = $input['payload']['order']['entity']['id'] ?? null;
                    
                    if ($orderId) {
                        $subscription = Subscription::where('razorpay_order_id', $orderId)->first();
                        
                        if ($subscription && $subscription->status !== 'active') {
                            $subscription->status = 'active';
                            $subscription->save();
                            
                            Log::info('Subscription activated via order.paid webhook', [
                                'subscription_id' => $subscription->id,
                                'order_id' => $orderId
                            ]);
                        }
                    }
                    break;
                
                default:
                    // Other events - just log them
                    Log::info('Unhandled Razorpay webhook event', ['event' => $event]);
                    break;
            }
            
            return response('Webhook processed', 200);
        } catch (\Exception $e) {
            Log::error('Error processing Razorpay webhook', [
                'event' => $event,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response('Error processing webhook: ' . $e->getMessage(), 500);
        }
    }
    }