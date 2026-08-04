<?php
// SMS Gateway Configuration
$auth_key_msg = "b0e99bea1fa7d15e27e1c5fd8e3c868";
$sender_id = "CITYXI";

function bulk_msg($mobile_list, $sms, $count = 1, $smstype = 'english')
{
    global $auth_key_msg;
    global $sender_id;
    
    $clean_mobiles = preg_replace('/[^0-9]/', '', $mobile_list);
    if (strlen($clean_mobiles) >= 10) {
        $clean_mobiles = substr($clean_mobiles, -10);
    }

    $data = array(
        'smsContent' => $sms,
        'groupId' => '',
        'routeId' => 1,
        'mobileNumbers' => $clean_mobiles,
        'senderId' => $sender_id,
        'signature' => '',
        'smsContentType' => $smstype
    );
    
    $text_sms = json_encode($data);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://msg.morg.in/rest/services/sendSMS/sendGroupSms?AUTH_KEY=$auth_key_msg",
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
    $log_entry = date('Y-m-d H:i:s') . " | Mobile: {$clean_mobiles} | Sender: {$sender_id} | Response: {$response} | Error: {$err}" . PHP_EOL;
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


// Wrapper function for OTP using CITYXI OfferPlant template
function sendOTP($mobile, $name, $otp) {
    $displayName = !empty(trim($name)) ? trim($name) : 'User';
    $message = "Dear $displayName,\nYour OTP / EVC / Password is $otp for Saran Index\n \n Regards\n CITYXI\n OfferPlant";
    
    return bulk_msg($mobile, $message);
}

// Backward compatibility wrapper
function send_registration_sms($mobile, $name, $code) {
    return sendOTP($mobile, $name, $code);
}

