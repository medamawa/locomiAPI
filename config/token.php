
<?php
 
/**
 * トークン用の設定
 */
return [
 
    // トークン有効期限の設定
    'expire' => [
 
        // デフォルト60分
        'accessToken' => env('ACCESS_TOKEN_EXPIRATION_SECONDS', 3600),
 
        // デフォルト4週間
        'refreshToken' => env('REFRESH_TOKEN_EXPIRATION_SECONDS', 2419200),
    ]
];
