<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $authKey;
    private $senderId;
    private $baseUrl;

    public function __construct()
    {
        //old credentials
        // $this->authKey = config('services.msgclub.auth_key', 'f75d8543c06120a3f674417b6bb8802c');
        // $this->senderId = config('services.msgclub.sender_id', 'YOUTHC');
        // $this->baseUrl = 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms';

        //new credentials
        $this->authKey = config('services.msgclub.auth_key', '88cba4d385f3e1b26aa8dfc9a819262');
        $this->senderId = config('services.msgclub.sender_id', 'YCNTUR');
        $this->baseUrl = 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms';
    }

    /**
     * Send OTP SMS to a mobile number
     *
     * @param string $mobileNumber
     * @param string $otp
     * @return array
     */
    public function sendOtp($mobileNumber, $otp)
    {
        // $message = "Your Youth Central OTP is: {$otp}. Valid for 5 minutes. Do not share this code with anyone.";
        
        $message = "Your Youth Centuary Academy OTP is: {$otp}. Valid for 5 minutes. Do not share this code with anyone.";
        
        return $this->sendSms($mobileNumber, $message, 8); // Route 8 = OTP Route
    }

    /**
     * Send SMS using MSG Club API
     *
     * @param string $mobileNumber
     * @param string $message
     * @param int $routeId
     * @return array
     */
    public function sendSms($mobileNumber, $message, $routeId = 1)
    {
        try {
            // Clean mobile number (remove any non-numeric characters)
            $cleanMobile = preg_replace('/[^0-9]/', '', $mobileNumber);
            
            // Add country code if not present
            if (strlen($cleanMobile) == 10) {
                $cleanMobile = '91' . $cleanMobile; // Add India country code
            }

            $params = [
                'AUTH_KEY' => $this->authKey,
                'message' => $message,
                'senderId' => $this->senderId,
                'routeId' => $routeId,
                'mobileNos' => $cleanMobile,
                'smsContentType' => 'english'
                // 'entityid' => 'NoneedIfAddedInPanel',
                // 'tmid' => '140200000022',
                // 'templateid' => 'NoneedIfAddedInPanel',
                // 'concentFailoverId' => '30',
                // 'peChainID' => 'NoneedIfAddedInPanel'
            ];

            // Build URL with parameters
            $url = $this->baseUrl . '?' . http_build_query($params);

            // Make HTTP request
            $response = Http::timeout(30)->get($url);

            $responseData = $response->json();

            Log::info('SMS API Response', [
                'mobile' => $cleanMobile,
                'response' => $responseData,
                'status_code' => $response->status()
            ]);

            if ($response->successful() && isset($responseData['responseCode'])) {
                return [
                    'success' => $responseData['responseCode'] === '3001',
                    'message' => $this->getResponseMessage($responseData['responseCode']),
                    'response_code' => $responseData['responseCode'],
                    'request_id' => $responseData['response'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to send SMS',
                'response_code' => null,
                'request_id' => null
            ];

        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'mobile' => $mobileNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'SMS service temporarily unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get human-readable message for response codes
     *
     * @param string $responseCode
     * @return string
     */
    private function getResponseMessage($responseCode)
    {
        $messages = [
            '3001' => 'SMS sent successfully',
            '3002' => 'Invalid authentication key',
            '3003' => 'Insufficient balance',
            '3004' => 'Invalid mobile number',
            '3005' => 'Invalid sender ID',
            '3006' => 'Invalid route ID',
            '3007' => 'Message content is empty',
            '3008' => 'Invalid message content type',
            '3009' => 'Template not found',
            '3010' => 'DND number',
        ];

        return $messages[$responseCode] ?? 'Unknown response code: ' . $responseCode;
    }

    /**
     * Generate a 6-digit OTP
     *
     * @return string
     */
    public function generateOtp()
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Validate mobile number format
     *
     * @param string $mobileNumber
     * @return bool
     */
    public function isValidMobileNumber($mobileNumber)
    {
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobileNumber);
        
        // Check if it's a valid Indian mobile number (10 digits) or with country code (12 digits)
        return in_array(strlen($cleanMobile), [10, 12]) && 
               (strlen($cleanMobile) == 10 ? substr($cleanMobile, 0, 1) >= '6' : substr($cleanMobile, 0, 2) == '91');
    }
} 