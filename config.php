<?php
$bd = require __DIR__ . '/bd.php';
return [
  'language' => 'ru-RU',
'sourceLanguage'=>'en_US',
'charset'=>'utf-8',
    'id' => 'micro',

    // basePath (базовый путь) приложения будет каталог `micro-app`
    'basePath' => __DIR__,
    // это пространство имен где приложение будет искать все контроллеры
    'controllerNamespace' => 'micro\controllers',
    // установим псевдоним '@micro', чтобы включить автозагрузку классов из пространства имен 'micro'
    'aliases' => [
        '@micro' => __DIR__,
    ],
    'modules' => [
        'v1' => [
            'class' => 'micro\modules\v1\Module',
        ]
    ],
    'timeZone' => 'Asia/Irkutsk',
    'components' => [
      'db' => $bd,
        'formatter' => [
          'class' => 'yii\i18n\Formatter',
           'dateFormat' => 'd.MM.yyyy ',
           'timeFormat' => 'H:mm',
           'datetimeFormat' => 'd MMMM EEEE H:mm yyyy M',
       ],
      'response' => [
    'format' => yii\web\Response::FORMAT_JSON,
    'charset' => 'UTF-8'
],
      'mailer' => [
           'class' => 'yii\swiftmailer\Mailer',
       ],

 'request' => [
        'enableCookieValidation' => false,
        'parsers' => [
              'application/json' => 'yii\web\JsonParser',
              'multipart/form-data' => 'yii\web\MultipartFormDataParser'
          ]
      ],  /**/

//RBAC роли
      'authManager' => [
                  'class' => 'yii\rbac\DbManager'
              ],
  'user' => [
                   'identityClass' => 'micro\models\User',
                   'enableAutoLogin' => false,
                   'enableSession' => false,
                   'class' => 'yii\web\User',
               ],     /*  */

      'urlManager' => [
          'enablePrettyUrl' => true,
          'enableStrictParsing' => true,
          'showScriptName' => false,
          'rules' => [
              'api/v1/signup'=> 'v1/site/signup',
              'api/v1/auth'=> 'v1/site/auth',
              'api/v1/request-password-reset'=> 'v1/site/requestpasswordreset',
              'api/v1/reset-password'=> 'v1/site/resetpassword',
              'api/v1/file/upload'=> 'v1/file/upload',
              'api/v1/file/get-images'=> 'v1/file/getimages',
              'api/v1/file/<url:[^"]*(.)(jpeg|png|jpg)>'=> 'v1/file/delete',

              '/' => 'site/index',
              'init' => 'site/init',
              '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
              '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
              [
                'class' => 'yii\rest\UrlRule',
                 'controller' => [
                   'v1/post',
                   'v1/user',
                   'v1/profile',
                   'v1/category',
                 ],
                 'extraPatterns' => [
                  'POST avatar-upload/<id:\d+>' => 'avatarupload',
                  'OPTIONS avatar-upload/<id:\d+>' => 'avatarupload',
                  'preview-upload' => 'previewupload',
                  'GET role' => 'role',
                  'OPTIONS role' => 'role',
                  'GET search' => 'search',
                  'OPTIONS search' => 'search',
              ],


                 'prefix' => 'api',
               ],

          ],
      ],


],
'params' =>[
  /*переменная проверки токена сброса пароля*/
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail' => 'robot@devreadwrite.com'
]



];
