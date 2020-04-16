<?php
 
return [
    'failedValicationError' => [
        'code' => 400001,
        'message' => 'リクエストの形式が異なります。',
    ],
    'tokenSomethingWentWrongError' => [
        'code' => 401000,
        'message' => 'トークンのその他のエラーです。'
    ],
    'headerAuthorizationMissing' => [
        'code' => 401001,
        'message' => 'authorizationヘッダーが無い/空です。'
    ],
    'invalidAccessToken' => [
        'code' => 401002,
        'message' => 'トークンが無効です。'
    ],
    'expiredAccessToken' => [
        'code' => 401003,
        'message' => 'トークンが有効期限切れです。'
    ],
    'tokenValidDate' => [
        'code' => 401004,
        'message' => 'トークンの発行日時がパスワード最終更新日時より前です。'
    ],
    'wrongIdOrPassword' => [
        'code' => 401005,
        'message' => 'ログインidまたはパスワードが間違っています。'
    ],
    'lockAccount' => [
        'code' => 401006,
        'message' => 'アカウントがロックされています。'
    ],
    'refreshTokenValidDate' => [
        'code' => 401007,
        'message' => 'リフレッシュトークンが無効です。'
    ],
    'refreshTokenExpireDate' => [
        'code' => 401008,
        'message' => 'リフレッシュトークンが有効期限切れです。'
    ],
    'mailActivationError' => [
        'code' => 401009,
        'message' => 'メール認証キーが無効、または既に認証が完了しています。'
    ],
    'databaseTransactionRollback' => [
        'code' => 500001,
        'message' => 'もう1度お試し頂くかしばらく時間を置いてからご利用ください。'
    ],
    'failedValicationError' => [
        'code' 
    ]
];
