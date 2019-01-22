<?php
namespace micro\rbac;
use Yii;
use yii\rbac\Rule;
/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 */
class ProfileRule extends Rule
{
    public $name = 'isAuthorProfile';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
    $helpers =new \micro\components\Helpers();

    if($helpers->getIdByToken()==$params['id']){
      return true;
    }else if ($helpers->getRoleByToken()== 'admin'||$helpers->getRoleByToken()== 'manager'){
      return true;
    }else{
      return false;
    }

  }
}
