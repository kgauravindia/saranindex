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

    $res = array();
    if ($err) {
        $res['status'] = 'error';
        $res['count'] = 0;
        $res['msg'] = $err;
    } else {
        $res['status'] = 'success';
        $res['count'] = $count;
        $res['msg'] = $count . " SMS Send Successfully";
        // Optionally log raw response for debugging
        // file_put_contents('sms_debug.log', $response . PHP_EOL, FILE_APPEND);
    }
    return $res;
}

// Wrapper function for OTP using CITYXI OfferPlant template
// Template: Dear {#var#}, Your OTP / EVC / Password is {#var#} For saranindex.com
function send_registration_sms($mobile, $name, $code) {
    $template = "Dear {#var#},\n Your OTP / EVC / Password is {#var#}\n  \n Regards\n CITYXI\n OfferPlant";

    // Replace {#var#} sequentially: 1st = name, 2nd = OTP/code
    $vars = [$name, $code];
    foreach ($vars as $var) {
        $template = preg_replace('/\{#var#\}/', $var, $template, 1);
    }

    return bulk_msg($mobile, $template);
}
