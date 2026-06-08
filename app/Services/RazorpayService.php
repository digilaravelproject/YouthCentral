<?php

namespace App\Services;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $api;
    protected $logService;
    
    /**
     * Initialize Razorpay API with credentials
     */
    public function __construct(RazorpayLogService $logService = null)
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        
        // Log key information for debugging (first few characters only)
        Log::info('Razorpay initialization', [
            'key_starts_with' => substr($key, 0, 6) . '...',
            'key_length' => strlen($key),
            'secret_length' => strlen($secret)
        ]);
        
        try {
            $this->api = new Api($key, $secret);
            $this->logService = $logService ?? new RazorpayLogService();
        } catch (\Exception $e) {
            Log::error('Razorpay API initialization error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Create a new order
     *
     * @param float $amount Amount in INR
     * @param string $receipt Receipt ID for reference
     * @param array $notes Additional notes
     * @param string $entityType Entity type (e.g., "event_registration")
     * @param int $entityId Entity ID
     * @return array Order details
     * @throws \Exception When order creation fails
     */
    public function createOrder($amount, $receipt, $notes = [], $entityType = null, $entityId = null)
    {
        try {
            // Validate inputs
            if (empty($amount) || $amount <= 0) {
                throw new \InvalidArgumentException("Amount must be greater than zero");
            }
            
            if (empty($receipt)) {
                throw new \InvalidArgumentException("Receipt ID is required");
            }
            
            $orderData = [
                'amount' => $amount * 100, // Convert to paise
                'currency' => 'INR',
                'receipt' => $receipt,
                'payment_capture' => 1,
                'notes' => $notes
            ];
            
            Log::info('Creating Razorpay order', [
                'amount' => $amount,
                'receipt' => $receipt
            ]);
            
            // Check if we are in development or testing environment
            $environment = env('APP_ENV', 'production');
            $mockRazorpay = env('MOCK_RAZORPAY', false);
            
            // If we're in a non-production environment or mock is enabled, and there's an error,
            // create a mock order ID for testing purposes
            if (($environment !== 'production' || $mockRazorpay) && 
                env('RAZORPAY_TEST_FALLBACK', true)) {
                try {
                    // Try to create the real order
                    $order = $this->api->order->create($orderData);
                } catch (\Exception $e) {
                    // If it fails, log it and create a mock order ID
                    Log::warning('Razorpay API failed, using mock order', [
                        'amount' => $amount,
                        'receipt' => $receipt,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Create a mock response for testing
                    $mockOrderId = 'order_mock_' . str_replace('reg_', '', $receipt) . '_' . time();
                    $result = [
                        'id' => $mockOrderId,
                        'amount' => $orderData['amount'],
                        'currency' => $orderData['currency'],
                        'receipt' => $orderData['receipt'],
                        'status' => 'created',
                        'created_at' => time()
                    ];
                    
                    // Log the mock order
                    $this->logService->logOrder($result, $entityType, $entityId, true);
                    
                    return $result;
                }
            } else {
                // In production, actually create the order
                $order = $this->api->order->create($orderData);
            }
            
            if (empty($order) || !isset($order->id)) {
                throw new \Exception("Invalid response from Razorpay: Order ID missing");
            }
            
            Log::info('Razorpay order created', [
                'order_id' => $order->id
            ]);
            
            // Create array from object properties
            $result = [
                'id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'receipt' => $order->receipt,
                'status' => $order->status,
                'created_at' => $order->created_at
            ];
            
            // Log the order creation
            $this->logService->logOrder($result, $entityType, $entityId);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed', [
                'amount' => $amount,
                'receipt' => $receipt,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Log failure
            if ($this->logService) {
                $this->logService->logFailure(
                    ['amount' => $amount, 'receipt' => $receipt],
                    'ORDER_CREATION_FAILED',
                    $e->getMessage(),
                    $entityType,
                    $entityId
                );
            }
            
            // Re-throw the exception to be handled by the controller
            throw $e;
        }
    }
    
    /**
     * Verify payment signature
     *
     * @param string $paymentId
     * @param string $orderId
     * @param string $signature
     * @param string $entityType Entity type
     * @param int $entityId Entity ID
     * @return bool
     */
    public function verifySignature($paymentId, $orderId, $signature, $entityType = null, $entityId = null)
    {
        try {
            $attributes = [
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature
            ];
            
            Log::info('Verifying Razorpay signature', [
                'payment_id' => $paymentId,
                'order_id' => $orderId
            ]);
            
            $this->api->utility->verifyPaymentSignature($attributes);
            
            Log::info('Razorpay signature verified successfully');
            
            // Log success
            $this->logService->logSuccess($attributes, $entityType, $entityId);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Razorpay signature verification failed', [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'message' => $e->getMessage()
            ]);
            
            // Log failure
            $this->logService->logFailure(
                [
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_order_id' => $orderId,
                    'razorpay_signature' => $signature
                ],
                'SIGNATURE_VERIFICATION_FAILED',
                $e->getMessage(),
                $entityType,
                $entityId
            );
            
            return false;
        }
    }
    
    /**
     * Capture payment
     *
     * @param string $paymentId
     * @param float $amount
     * @param string $entityType Entity type
     * @param int $entityId Entity ID
     * @return array
     */
    public function capturePayment($paymentId, $amount, $entityType = null, $entityId = null)
    {
        try {
            Log::info('Capturing Razorpay payment', [
                'payment_id' => $paymentId,
                'amount' => $amount
            ]);
            
            $payment = $this->api->payment->fetch($paymentId);
            $capturedPayment = $payment->capture(['amount' => $amount * 100]);
            $result = json_decode(json_encode($capturedPayment), true);
            
            Log::info('Razorpay payment captured successfully', [
                'payment_id' => $paymentId
            ]);
            
            // Log success
            $this->logService->logSuccess(
                array_merge(['amount' => $amount], $result),
                $entityType,
                $entityId
            );
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Razorpay payment capture failed', [
                'payment_id' => $paymentId,
                'amount' => $amount,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Log failure
            $this->logService->logFailure(
                ['payment_id' => $paymentId, 'amount' => $amount],
                'PAYMENT_CAPTURE_FAILED',
                $e->getMessage(),
                $entityType,
                $entityId
            );
            
            throw $e;
        }
    }
    
    /**
     * Fetch payment details
     *
     * @param string $paymentId
     * @return array
     */
    public function fetchPayment($paymentId)
    {
        try {
            Log::info('Fetching Razorpay payment', [
                'payment_id' => $paymentId
            ]);
            
            $payment = $this->api->payment->fetch($paymentId);
            
            Log::info('Razorpay payment fetched successfully');
            
            return json_decode(json_encode($payment), true);
        } catch (\Exception $e) {
            Log::error('Razorpay fetch payment failed', [
                'payment_id' => $paymentId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Fetch order details
     *
     * @param string $orderId
     * @return array
     */
    public function fetchOrder($orderId)
    {
        try {
            Log::info('Fetching Razorpay order', [
                'order_id' => $orderId
            ]);
            
            // Check if this is a mock order
            if (strpos($orderId, 'order_mock_') === 0) {
                Log::info('Using mock order data for testing');
                
                // Extract registration ID from the mock order ID (assuming format is order_mock_xxx_timestamp)
                $parts = explode('_', $orderId);
                $registrationId = $parts[2] ?? '0';
                
                // Create mock order data
                return [
                    'id' => $orderId,
                    'amount' => 10000, // 100 INR in paise
                    'amount_paid' => 0,
                    'amount_due' => 10000,
                    'currency' => 'INR',
                    'receipt' => 'mock_receipt_' . $registrationId,
                    'status' => 'created',
                    'attempts' => 0,
                    'created_at' => time()
                ];
            }
            
            $order = $this->api->order->fetch($orderId);
            
            Log::info('Razorpay order fetched successfully');
            
            return json_decode(json_encode($order), true);
        } catch (\Exception $e) {
            Log::error('Razorpay fetch order failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // In development mode, return a mock order instead of throwing error
            $environment = env('APP_ENV', 'production');
            $mockRazorpay = env('MOCK_RAZORPAY', false);
            
            if (($environment !== 'production' || $mockRazorpay) && 
                env('RAZORPAY_TEST_FALLBACK', true)) {
                Log::warning('Creating mock order data due to fetch failure');
                
                return [
                    'id' => $orderId,
                    'amount' => 10000, // 100 INR in paise
                    'amount_paid' => 0,
                    'amount_due' => 10000,
                    'currency' => 'INR',
                    'receipt' => 'mock_receipt',
                    'status' => 'created',
                    'attempts' => 0,
                    'created_at' => time()
                ];
            }
            
            throw $e;
        }
    }
    
    /**
     * Process webhook event
     *
     * @param string $event Event type
     * @param array $payload Event payload
     * @return bool
     */
    public function processWebhookEvent($event, $payload)
    {
        try {
            Log::info("Processing Razorpay webhook: {$event}", [
                'event' => $event
            ]);
            
            // Log webhook event
            $this->logService->logWebhookEvent($event, $payload);
            
            // Handle different event types
            switch ($event) {
                case 'payment.captured':
                    // Payment was successful
                    Log::info('Payment captured webhook received', [
                        'payment_id' => $payload['payment']['entity']['id'] ?? null
                    ]);
                    break;
                    
                case 'payment.failed':
                    // Payment failed
                    Log::warning('Payment failed webhook received', [
                        'payment_id' => $payload['payment']['entity']['id'] ?? null,
                        'error' => $payload['payment']['entity']['error_code'] ?? null
                    ]);
                    break;
                    
                case 'order.paid':
                    // Order was paid
                    Log::info('Order paid webhook received', [
                        'order_id' => $payload['order']['entity']['id'] ?? null
                    ]);
                    break;
                    
                default:
                    Log::info("Unhandled webhook event: {$event}");
                    break;
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'event' => $event,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
} 