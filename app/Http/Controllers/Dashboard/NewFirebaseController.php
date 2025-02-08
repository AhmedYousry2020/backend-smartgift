<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewFirebaseController extends Controller
{
    public function sendIOSNotification($tokens,$json,$type = null, $id = null)
    {
        // new implementation
        $serviceAccountPath = storage_path() . '/json/firebase_credentials.json';
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

    $privateKey = $serviceAccount['private_key'];
    $clientEmail = $serviceAccount['client_email'];
    $projectId = $serviceAccount['project_id'];

    // Step 1: Create JWT to get OAuth2 token
    $now = time();
    $expiry = $now + 3600; // Token valid for 1 hour
    $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
    $jwtClaimSet = json_encode([
        'iss' => $clientEmail,
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $expiry
    ]);

    // Base64 encode the JWT header and claim set
    $jwtHeaderEncoded = base64_encode($jwtHeader);
    $jwtClaimSetEncoded = base64_encode($jwtClaimSet);

    // Step 2: Sign the JWT using the private key
    $dataToSign = $jwtHeaderEncoded . '.' . $jwtClaimSetEncoded;
    $signature = '';
    openssl_sign($dataToSign, $signature, $privateKey, 'SHA256');
    $jwtSignatureEncoded = base64_encode($signature);

    // Full JWT
    $jwt = $jwtHeaderEncoded . '.' . $jwtClaimSetEncoded . '.' . $jwtSignatureEncoded;

    // Step 3: Use JWT to get access token from Google OAuth2 server
    $tokenRequest = [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ];

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($tokenRequest));
    $response = curl_exec($ch);
    $responseDecoded = json_decode($response, true);
    curl_close($ch);

    if (!isset($responseDecoded['access_token'])) {
        throw new Exception('Error fetching access token: ' . $response);
    }

    $accessToken = $responseDecoded['access_token'];

    // Prepare the message payload for iOS
    $notification = [
        'title' => $json['title'],
        'body' => $json['body'],
    ];

    // Ensure all data fields are strings
    $dataPayload = [
        'my_value_1' => json_encode($data['additional_data'] ?? []), // Ensure data is stringified
        'badge' => '0', // Convert to string
        'sound' => 'default',
    ];

    $message = [
        'message' => [
            'token' => $tokens, // FCM token of the target device
            'notification' => $notification,
            'data' => $dataPayload, // Custom data for your app
        ]
    ];

    // Step 4: Use cURL to send the FCM message with the access token
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send');
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($message));

    // Send the request
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // Handle cURL error
        echo 'Error:' . curl_error($ch);
    } else {
        // Process the response
        $responseDecoded = json_decode($response, true);
       // print_r($responseDecoded);
    }

    // Close cURL session
    curl_close($ch);
    return $response;
    }

    public function sendAndroidNotification($tokens,$json)
    {
        // Step 1: Load your Firebase service account JSON
   $serviceAccountPath = storage_path() . '/json/firebase_credentials.json';
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
    $privateKey = $serviceAccount['private_key'];
    $clientEmail = $serviceAccount['client_email'];
    $projectId = $serviceAccount['project_id'];

    // Step 2: Create JWT to get OAuth2 token
    $now = time();
    $expiry = $now + 3600; // Token valid for 1 hour
    $jwtHeader = json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]);

    $jwtClaimSet = json_encode([
        'iss' => $clientEmail,
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $expiry
    ]);

    // Base64 encode the JWT header and claim set
    $jwtHeaderEncoded = base64_encode($jwtHeader);
    $jwtClaimSetEncoded = base64_encode($jwtClaimSet);


    // Step 3: Sign the JWT using the private key
    $dataToSign = $jwtHeaderEncoded . '.' . $jwtClaimSetEncoded;
    $signature = '';
    openssl_sign($dataToSign, $signature, $privateKey, 'SHA256');
    $jwtSignatureEncoded = base64_encode($signature);

    // Full JWT
    $jwt = $jwtHeaderEncoded . '.' . $jwtClaimSetEncoded . '.' . $jwtSignatureEncoded;

    // Step 4: Use JWT to get access token from Google OAuth2 server
    $tokenRequest = [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ];


    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($tokenRequest));
    $response = curl_exec($ch);
    $responseDecoded = json_decode($response, true);
    curl_close($ch);

    if (!isset($responseDecoded['access_token'])) {
        throw new Exception('Error fetching access token: ' . $response);
    }

    $accessToken = $responseDecoded['access_token'];

// Step 5: Prepare the message payload for FCM
    $message = [
        'message' => [
            'token' => $tokens, // FCM token of the target device
            'notification' => [
                'title' => $json['title'],
                'body' => $json['body'],
            ],
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'sound' => 'default',
                    'channel_id' => 'basic_channel',
                ],
            ],
            'apns' => [
                'headers' => [
                    'apns-priority' => '10',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => $json['title'],
                             'body' => $json['body'],
                        ],
                        'sound' => 'default',
                    ],
                ],
            ],
        ]
    ];

    $dataString = json_encode($message);
    // Step 6: Use cURL to send the FCM message with the access token
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send');
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // Handle cURL error
        echo 'Error:' . curl_error($ch);
    } else {
        // Process the response
        $responseDecoded = json_decode($response, true);
       // print_r($responseDecoded);
    }

    curl_close($ch);

    return $response;

    }



    public function sendWebNotification($tokens, $json)
    {
        if (is_array($tokens)) {
            $tokenIds = $tokens;
        } else {
            $tokenIds = [$tokens];
        }

        // $tokenIds = array("fy8rAVKFwIg:APA91bG_xokMtf3I61NFPbor3eucwCb6RFYB4u_xvZ3PFg7sXEm517VgvwmV8hP61F9i8vkO5vknXW8kd5aOsnhV71HsoplhKGNXQS7_LMZkgw-TbpUTpVpIbNIuNG2cipvcSL-jQYLt" );
        $serverKey = 'AAAAConvqBg:APA91bHxu8Kit3-Bq6cQmN09cysgAHCxpMO1W_ZTV8MxwBGfOrq8JkUMQOosyDWxSEJd62dPDrmGICELjD943fwVbOIZ4R4LTrv67wDfOhatYYYY1S5bA9gUDo9mnk7vPrF7qBg8uXG-';

        $fields = array(
            'webpush' => array(
                'notification' => $json,
            ),
            'registration_ids' => $tokenIds,
        );

        $headers = array(
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }

        $result = json_decode($result, true);
        curl_close($ch);

        $response['firebase'] = $result;
        $response['json'] = $fields;
        return $response;
    }



    public function fillAndroidJson($title,$body,$type,$id)
    {
        $json =  array();
        $json['title'] = $title;
        $json['body'] = $body;
        $json['type'] = intval($type);
        $json['id'] = intval($id);
        return $json;
    }

    public function fillIOSJson($title,$body)
    {
        $json['title'] = $title;
        $json['body'] = $body;
        //$json['content_available'] = true;
        $json['sound'] = 'default';
        /*$aps['alert']['title'] = "dddd";
        $aps['alert']['body'] = "fdfdf";
        $json =  array();
        $json['aps'] = $aps;
        $json['aps']['content-available'] = 1;
        $json['aps']['sound'] = "default";
        $json['aps']['mutable-content'] = 1;
        $json['type'] = "memo";*/
        return $json;
    }

}
