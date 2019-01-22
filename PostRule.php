<?php
namespace micro\rbac;
use Yii;
use yii\rbac\Rule;
use micro\modules\v1\models\Post;
/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 */
class PostRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
    $helpers =new \micro\components\Helpers();
   $post = Post::find()
   ->where(['id' => $params['id']])
    ->one();

    if($helpers->getIdByToken()==$post->user_id){
      return true;
    }else if ($helpers->getRoleByToken()== 'admin'||$helpers->getRoleByToken()== 'manager'){
      return true;
    }else{
      return false;
    }

  }
}
