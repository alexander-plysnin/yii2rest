<?php
/*
http://micro/api/v1/profiles
http://micro/api/v1/profiles/18
put,get
*/
namespace micro\modules\v1\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;
use micro\modules\v1\models\Profile;
use yii\filters\VerbFilter;
use micro\models\UploadForm;
use yii\web\UploadedFile;
use yii\imagine\Image;
class ProfileController extends ActiveController
{
    public $modelClass = 'micro\modules\v1\models\Profile';

    public function behaviors()
    {
        return [
          'corsFilter' => [
              'class' => \yii\filters\Cors::className(),
              'cors' => [
                  // restrict access to
                  'Origin' => ['http://localhost:3000'],
                  // Allow only POST and PUT methods
               'Access-Control-Request-Method' => ['GET','POST','HEAD', 'PUT','OPTIONS'],
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
        //     'except' => ['options'],
        'only' => ['view','update','role','avatarupload'],
            'authMethods' => [
                HttpBearerAuth::className(),

            ]
        ],
          /**/  'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','role','view'],
                        'allow' => true,
                        'roles' => ['user','manager','admin'],
                    ],
                    [
                        'actions' => ['update','avatarupload'],
                        'allow' => true,
                        'roles' => ['manager','admin','user'],
                      'matchCallback' => function($id) {
                              return Yii::$app->user->can('updateProfile', ['id' => Yii::$app->request->get('id')]);
                          },
                    ],
                ],
              //  'ruleConfig' => ['class' => 'micro\rbac\AuthorRule'],
            ],
        ];
    }


    /* */   public function actions()
    {
        return [
          'options' => [
        'class' => 'yii\rest\OptionsAction',
      ],
          'index' => [
              'class' => 'yii\rest\IndexAction',
              'modelClass' => $this->modelClass,
              'checkAccess' => [$this, 'checkAccess'],
          ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
              //
                'findModel' => [$this, 'findModel']
             ],
             'update'=>[
               'class' => 'yii\rest\UpdateAction',
               'modelClass' => $this->modelClass,
               'findModel' => [$this, 'findModel']
             ]

           ];
         }

         public function findModel($id)
          {

              $model = Profile::find()
               ->where(['user_id' => (int) $id])
               ->one();
              if (!$model) {
                throw new ServerErrorHttpException('Page not found');
              }
              return $model;

             }

             //экшен аватарки
             public function actionAvatarupload($id)
             {
                  $model = new UploadForm();
                  $helpers =new \micro\components\Helpers();
           if (Yii::$app->request->isPost) {
                        $model->file = UploadedFile::getInstanceByName( 'avatar');

                        if ($model->file && $model->validate()) {
                          $filename=Yii::$app->security->generateRandomString();
                          $filename_photo_50=Yii::$app->security->generateRandomString();
                          $avatar='avatars/' . $filename . '.' . $model->file->extension;
                          $avatar_50='avatars/' . $filename_photo_50 . '.' . $model->file->extension;
                          $model->file->saveAs($avatar);

                          Image::thumbnail('@webroot/'.$avatar, 50, 50)
                          ->save(Yii::getAlias('@webroot/'.$avatar_50), ['quality' => 100]);

                          $this->updateAvatarUrl($helpers->getIdByToken(), $avatar, $avatar_50);
                          return true;
                        }else{
                           return $model->getErrors() ;
                        }
                    }/* */
             }
             public function actionRole()
             {
                 $helpers =new \micro\components\Helpers();
                return $helpers->getRoleByToken();

             }
             public function updateAvatarUrl($id, $avatar, $avatar_50){
               $model = $this->findModel($id);
               if($model->photo!=null){
                 $this->deleteAvatar($model->photo);
                 $this->deleteAvatar($model->photo_50);
               }
               $model->photo=$avatar;
               $model->photo_50=$avatar_50;
               if ($model->save() === false && !$model->hasErrors()) {
                   throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
               }
               return true;
             }
               public function deleteAvatar($avatar){
                 unlink(Yii::$app->basePath.'/web/'.$avatar);
               }

}
