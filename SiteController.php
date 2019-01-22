<?php

namespace micro\modules\v1\controllers;
use Yii;
use yii\rest\Controller;
use micro\models\LoginForm;
use micro\models\SignupForm;
use micro\models\User;
use micro\models\PasswordResetRequestForm;
use micro\models\ResetPasswordForm;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\filters\Cors;


class SiteController extends Controller
{



  public function behaviors()
  {
      return [
        'corsFilter' => [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['http://localhost:3000'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['POST', 'PUT','OPTIONS'],
                // Allow only headers 'X-Wsse'
              'Access-Control-Request-Headers' => ['*'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],

        ],
        'authenticator' => [
          'class' => CompositeAuth::className(),
         'only' => ['upload'],
          'authMethods' => [
              HttpBearerAuth::className(),
          ]
      ],
          'access' => [
              'class' => AccessControl::className(),
              'only' => ['auth', 'logout', 'signup'],
              'rules' => [
                  [
                      'actions' => ['auth','signup','request-password-reset','reset-password'],
                      'allow' => true,
                      'roles' => ['?'],
                  ],
                  [
                      'actions' => ['logout'],
                      'allow' => true,
                      'roles' => ['@'],
                  ],

              ],
          ],
          'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
              
                'request-password-reset' => ['post'],
              ],
          ],
      ];
  }
    //авторизация
    public function actionAuth()
    {
      $model = new LoginForm();
    //  return Yii::$app->request->bodyParams;
       $model->load(Yii::$app->request->bodyParams, '');
       if ($token = $model->auth()) {
           return $token;
       } else {
           return $model;
       }

    }
    //регистрация
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->bodyParams, '')&& $model->validate()) {

            if ($user = $model->signup()) {
              //  if (Yii::$app->getUser()->login($user)) {
                    return 'вы зарегистрировались, теперь вы можете войти на сайт!';
            }else{
              return 'ss';
            }
    }else if(!$model->validate()){
  Yii::$app->response->statusCode = 400;
  Yii::$app->response->data = $model->errors;
  return Yii::$app->response->data  ;/* */
  //$model->errors();
}


  }
    /**
   * Отправка токена сброса пароля
   *
   * @return mixed
   */
  public function actionRequestpasswordreset()
  {
    //return 'ss';
      $model = new PasswordResetRequestForm();

      if ($model->load(Yii::$app->request->bodyParams, '') && $model->validate()) {
//return 's';
          if ($model->sendEmail()) {
              return 'Сообщение отправлено, действуте согласно инструкции';
          } else {
              return 'Извените мы не смогли отправить вам письмо';
          }
      }else if(!$model->validate()){
        Yii::$app->response->statusCode = 400;
        Yii::$app->response->data = $model->errors;
        return Yii::$app->response->data;  /* */
      }

  }
  /**
    * смена пароля
    *
    *
    */
   public function actionResetpassword()
   {
     $token=Yii::$app->request->getBodyParam('token');

     $pasword=Yii::$app->request->getBodyParam('password');
     $message;
       try {
           $model = new ResetPasswordForm($token);
          //  $message= 'Пароль успешно';
           if ($model->load(Yii::$app->request->bodyParams, '') && $model->validate() && $model->resetPassword()) {
               $message= 'Пароль успешно изменен!';
           }else{
             Yii::$app->response->statusCode = 400;
             Yii::$app->response->data = $model->errors;
                $message= Yii::$app->response->data;
           }

       } catch (InvalidParamException $e) {
          $message= $e->getMessage();
       }finally {
    return $message;
}



}


     public function actionGetimages(){
       $files=\yii\helpers\FileHelper::findFiles('img');
       return $files;
     }

}
