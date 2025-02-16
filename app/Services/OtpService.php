<?php

namespace App\Services;

use SoapClient;
use Exception;

class OtpService
{
    protected $wsdlUrl;
    protected $username;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->wsdlUrl = 'http://server.smson.com/SmsWebService.asmx?wsdl'; // Replace with actual URL
        $this->username = config('sms.username'); // Store credentials in config
        $this->password = config('sms.password');
        $this->token = config('sms.token');
    }

    public function sendOtp($phone, $otp)
    {
        try {
            $client = new SoapClient($this->wsdlUrl, ['trace' => 1, 'exceptions' => true]);

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'token' => $this->token,
                'recipient' => $phone,
                'message' => "Your OTP code is: $otp",
            ];

            $response = $client->__soapCall('SendOtp', [$params]);

            return $response;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
