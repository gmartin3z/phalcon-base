<?php

use Phalcon\Config;

return new Config([
    'visitor' => [
        'index' => ['public'],
        'faqs' => ['public'],
        'captcha' => ['generateCaptcha'],
        'profile' => ['register', 'login', 'recover', 'finishRecover'],
        'errors' => ['show404', 'show503'],
        'language' => ['change']
    ],
    'limited' => [
        'index' => ['public'],
        'faqs' => ['public'],
        'captcha' => ['generateCaptcha'],
        'profile' => ['logout', 'updateTempProfile','verify', 'finishVerify'],
        'errors' => ['show404', 'show503'],
        'language' => ['change']
    ],
    'admin' => [
        'index' => ['*'],
        'faqs' => ['*'],
        'captcha' => ['*'],
        'profile' => [
            'index',
            'logout',
            'updateAlias',
            'updateEmail',
            'finishUpdateEmail',
            'updatePassword',
            'finishUpdatePassword',
            'delete',
            'finishDelete'
        ],
        'errors' => ['show404', 'show503'],
        'language' => ['change']
    ]
]);
