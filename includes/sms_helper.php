<?php
// SMS Gateway Configuration
if (!defined('SMS_AUTH_KEY')) {
    define('SMS_AUTH_KEY', 'b0e99bea1fa7d15e27e1c5fd8e3c868');
}
if (!defined('SMS_SENDER_ID')) {
    define('SMS_SENDER_ID', 'SARDEX');
}

// DLT Template IDs Configuration
if (!defined('SMS_TEMPLATE_POST')) {
    define('SMS_TEMPLATE_POST', '1277178583311913578'); // SARDEX_POST
}
if (!defined('SMS_TEMPLATE_REG')) {
    define('SMS_TEMPLATE_REG', '1277178583941316188');  // SARDEX_REG
}
if (!defined('SMS_TEMPLATE_OTP')) {
    define('SMS_TEMPLATE_OTP', '1277178583966276809');  // SARDEX_OTP
}

$GLOBALS['auth_key_msg'] = SMS_AUTH_KEY;
$GLOBALS['sender_id']    = SMS_SENDER_ID;
$auth_key_msg            = SMS_AUTH_KEY;
$sender_id               = SMS_SENDER_ID;

if (!function_exists('bulk_msg')) {
    function bulk_msg($mobile_list, $sms, $count = 1, $smstype = 'english', $template_id = '')
    {
        global $auth_key_msg, $sender_id;
        
        $activeAuthKey = !empty($auth_key_msg) ? $auth_key_msg : ($GLOBALS['auth_key_msg'] ?? SMS_AUTH_KEY);
        $activeSenderId = !empty($sender_id) ? $sender_id : ($GLOBALS['sender_id'] ?? SMS_SENDER_ID);

        $clean_mobiles = preg_replace('/[^0-9]/', '', $mobile_list);
        if (strlen($clean_mobiles) >= 10) {
            $clean_mobiles = substr($clean_mobiles, -10);
        }

        $data = array(
            'smsContent' => $sms,
            'groupId' => '',
            'routeId' => 1,
            'mobileNumbers' => $clean_mobiles,
            'senderId' => $activeSenderId,
            'signature' => '',
            'smsContentType' => $smstype
        );
        
        $text_sms = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://msg.morg.in/rest/services/sendSMS/sendGroupSms?AUTH_KEY=" . urlencode($activeAuthKey),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $text_sms,
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        // Log raw response for debugging
        $log_entry = date('Y-m-d H:i:s') . " | Mobile: {$clean_mobiles} | Sender: {$activeSenderId} | Response: {$response} | Error: {$err}" . PHP_EOL;
        @file_put_contents(__DIR__ . '/../sms_debug.log', $log_entry, FILE_APPEND);
        error_log("SMS Dispatch: " . $log_entry);

        $res = array();
        $jsonResp = json_decode($response, true);

        if ($err) {
            $res['status'] = 'error';
            $res['count'] = 0;
            $res['msg'] = "cURL Error: " . $err;
        } elseif (is_array($jsonResp) && isset($jsonResp['responseCode']) && $jsonResp['responseCode'] != '3001') {
            $res['status'] = 'error';
            $res['count'] = 0;
            $res['msg'] = "Gateway Error ({$jsonResp['responseCode']}): " . ($jsonResp['response'] ?? 'SMS dispatch failed');
        } else {
            $res['status'] = 'success';
            $res['count'] = $count;
            $res['msg'] = $count . " SMS Sent Successfully";
        }
        return $res;
    }
}

if (!function_exists('sendOTP')) {
    /**
     * Send OTP SMS using SARDEX_OTP DLT Template (1277178583966276809)
     * Template:
     * Dear {#alp#}, 
     *  Your Application / Website EVC / OTP / Password is {#alp#} 
     *  
     *  Regards
     *  SARDEX
     *  OfferPlant
     */
    function sendOTP($mobile, $name, $otp) {
        $displayName = !empty(trim($name)) ? trim($name) : 'User';
        $message = "Dear $displayName, \n Your Application / Website EVC / OTP / Password is $otp \n \n Regards\n SARDEX\n OfferPlant";
        
        return bulk_msg($mobile, $message, 1, 'english', SMS_TEMPLATE_OTP);
    }
}

if (!function_exists('sendProfileSMS')) {
    /**
     * Send Registration / Profile SMS using SARDEX_REG DLT Template (1277178583941316188)
     * Template:
     * Dear {#alp#}, 
     *  Your Saran Index Profile {#alp#}
     *  
     *  Regards
     *  SARDEX
     *  OFFERPLANT
     */
    function sendProfileSMS($mobile, $name, $profileDetail = '') {
        $displayName = !empty(trim($name)) ? trim($name) : 'User';
        $message = "Dear $displayName, \n Your Saran Index Profile $profileDetail\n \n Regards\n SARDEX\n OFFERPLANT";
        
        return bulk_msg($mobile, $message, 1, 'english', SMS_TEMPLATE_REG);
    }
}

if (!function_exists('send_registration_sms')) {
    // Backward compatibility wrapper
    function send_registration_sms($mobile, $name, $code) {
        return sendOTP($mobile, $name, $code);
    }
}

if (!function_exists('sendRegistrationSMS')) {
    function sendRegistrationSMS($mobile, $name, $roleOrType, $codeOrPassword = '') {
        $otp = !empty($codeOrPassword) ? $codeOrPassword : $roleOrType;
        return sendOTP($mobile, $name, $otp);
    }
}


if (!function_exists('sendPostSMS')) {
    /**
     * Send Post / Profile Validity SMS using SARDEX_POST DLT Template (1277178583311913578)
     * Template:
     * Dear {#alp#},
     *  Your post / profile is valid till {#alp#} 
     *  More details{#urg#} 
     *  
     *  Regards
     *  SARDEX
     *  OfferPlant
     */
    function sendPostSMS($mobile, $name, $validTill, $moreDetailsUrl = '') {
        $displayName = !empty(trim($name)) ? trim($name) : 'User';
        $message = "Dear $displayName,\n Your post / profile is valid till $validTill \n More details$moreDetailsUrl \n \n Regards\n SARDEX\n OfferPlant";
        
        return bulk_msg($mobile, $message, 1, 'english', SMS_TEMPLATE_POST);
    }
}



