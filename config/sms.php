<?php

return [
    'enable'=>env('SMS_ENABLE'),
    'username'=>env('SMS_USERNAME'),
    'password'=>env('SMS_PASSWORD'),
    'tag_name'=>env('SMS_TAG_NAME'),
    'url'=>env('SMS_URL'),
    'timeout' => env('OTP_TIMEOUT',2),
];

?>
