<?php

namespace App\Services;

use App\Models\RazorpayPaymentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RazorpayLogService
{
    /**
     * Log a new payment event
     *
     * @param string $eventType The type of event (order_created, payment_success, payment_failed, webhook)
     * @param array $data The data to be logged
     * @param string|null $entityType The type of entity (e.g., 'event_registration')
     * @param int|null $entityId The ID of the related entity
     * @return RazorpayPaymentLog|null
     */
    public function logEvent($eventType, $data, $entityType = null, $entityId = null)
    {
        try {
            // Extract payment ID and order ID if they exist in the data
            $paymentId = $data['razorpay_payment_id'] ?? $data['payment_id'] ?? $data['id'] ?? null;
            $orderId = $data['razorpay_order_id'] ?? $data['order_id'] ?? null;
            
            // Create a new log entry
            $log = new RazorpayPaymentLog([
                'event_type' => $eventType,
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'status' => $this->determineStatus($eventType, $data),
                'data' => json_encode($data)
            ]);
            
            $log->save();
            
            Log::info("Razorpay {$eventType} logged", [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'entity_type' => $entityType,
                'entity_id' => $entityId
            ]);
            
            return $log;
        } catch (\Exception $e) {
            Log::error("Error logging Razorpay {$eventType}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            
            return null;
        }
    }
    
    /**
     * Log a new order
     *
     * @param array $orderData The order data
     * @param string|null $entityType The type of entity
     * @param int|null $entityId The ID of the entity
     * @param bool $isMock Whether this is a mock order for testing
     * @return RazorpayPaymentLog|null
     */
    public function logOrder($orderData, $entityType = null, $entityId = null, $isMock = false)
    {
        // Add mock flag to data if it's a mock order
        if ($isMock) {
            $orderData['is_mock'] = true;
        }
        
        return $this->logEvent('order_created', $orderData, $entityType, $entityId);
    }
    
    /**
     * Log a successful payment
     *
     * @param array $paymentData The payment data
     * @param string|null $entityType The type of entity
     * @param int|null $entityId The ID of the entity
     * @return RazorpayPaymentLog|null
     */
    public function logSuccess($paymentData, $entityType = null, $entityId = null)
    {
        return $this->logEvent('payment_success', $paymentData, $entityType, $entityId);
    }
    
    /**
     * Log a failed payment
     *
     * @param array $paymentData The payment data
     * @param string $errorCode The error code
     * @param string $errorDescription The error description
     * @param string|null $entityType The type of entity
     * @param int|null $entityId The ID of the entity
     * @return RazorpayPaymentLog|null
     */
    public function logFailure($paymentData, $errorCode, $errorDescription, $entityType = null, $entityId = null)
    {
        // Add error details to the payment data
        $paymentData['error'] = [
            'code' => $errorCode,
            'description' => $errorDescription
        ];
        
        return $this->logEvent('payment_failed', $paymentData, $entityType, $entityId);
    }
    
    /**
     * Log a webhook event
     *
     * @param string $event The webhook event
     * @param array $data The webhook data
     * @return RazorpayPaymentLog|null
     */
    public function logWebhookEvent($event, $data)
    {
        // Extract entity type and ID if available in the payload
        $entityType = null;
        $entityId = null;
        
        // Try to get entity type and ID from notes if they exist
        if (isset($data['payment']['entity']['notes'])) {
            $notes = $data['payment']['entity']['notes'];
            $entityType = $notes['entity_type'] ?? null;
            $entityId = $notes['entity_id'] ?? null;
        } elseif (isset($data['order']['entity']['notes'])) {
            $notes = $data['order']['entity']['notes'];
            $entityType = $notes['entity_type'] ?? null;
            $entityId = $notes['entity_id'] ?? null;
        }
        
        // Prepare webhook data for logging
        $webhookData = [
            'event' => $event,
            'data' => $data
        ];
        
        return $this->logEvent('webhook_' . $event, $webhookData, $entityType, $entityId);
    }
    
    /**
     * Determine the status based on the event type and data
     *
     * @param string $eventType The type of event
     * @param array $data The data
     * @return string The status
     */
    private function determineStatus($eventType, $data)
    {
        if ($eventType === 'payment_failed') {
            return 'failed';
        }
        
        if ($eventType === 'payment_success') {
            return 'success';
        }
        
        if ($eventType === 'order_created') {
            return 'created';
        }
        
        if (strpos($eventType, 'webhook_') === 0) {
            if (strpos($eventType, 'webhook_payment.captured') === 0) {
                return 'success';
            }
            
            if (strpos($eventType, 'webhook_payment.failed') === 0) {
                return 'failed';
            }
            
            if (strpos($eventType, 'webhook_order.paid') === 0) {
                return 'success';
            }
        }
        
        return 'unknown';
    }
} 