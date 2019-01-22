<?
namespace console\controllers;

use Yii;
use micro\rbac\PostRule;
use micro\rbac\ProfileRule;

class RoleController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        // добавлем правило
        $profRule = new ProfileRule;
        $postRule = new PostRule;
        $auth->add($profRule);
        $auth->add($postRule);
    /*    // добавляем разрешение "createPost"
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);
    */
        // добавляем разрешение "updatePost" редактировать посты
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);
        // добавляем разрешение "updateOwnPost" редактировать свой профайл
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $postRule->name;
        $auth->add($updateOwnPost);
        $auth->addChild($updateOwnPost, $updatePost);

        // добавляем разрешение "$updateProfile" редактировать профили
        $updateProfile = $auth->createPermission('updateProfile');
        $updateProfile->description = 'Update profile';
        $auth->add($updateProfile);

        // добавляем разрешение "updateOwnProfile" редактировать свой профайл
        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
        $updateOwnProfile->description = 'Update own profile';
        $updateOwnProfile->ruleName = $profRule->name;
        $auth->add($updateOwnProfile);
        $auth->addChild($updateOwnProfile, $updateProfile);


        // добавляем роль "user" и даём роли разрешение "createPost"
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $updateOwnPost);
        $auth->addChild($user, $updateOwnProfile);

        // добавляем роль "manager" и даём роли разрешение "createPost"
        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $updateOwnPost);
        $auth->addChild($manager, $updateOwnProfile);
        // добавляем роль "admin" и даём роли разрешение "updatePost"
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateProfile);
        $auth->addChild($admin, $updatePost);
      //  $auth->addChild($admin, $author);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
      //  $auth->assign($author, 2);
      //$auth->assign($admin, 1);
    }
}
