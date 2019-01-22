<?php
namespace micro\components;
use Yii;
class Helpers{
//	функция вывода id по токену из baerer
	public function getIdByToken(){
    //получаем пользователя по токену
    $request = Yii::$app->request;
    $authHeader = $request->getHeaders()->get('Authorization');
    $re = '/Bearer /';
    $subst = '';
    $result = preg_replace($re, $subst, $authHeader, 1);

    $getIdByToken=Yii::$app->user->loginByAccessToken($result);
    return $getIdByToken->id;
	}
		public function getRoleByToken(){
			$id=$this->getIdByToken();
		$roleUser=Yii::$app->authManager->getRolesByUser($id);
		foreach ($roleUser as $role) {
						return $role->name ; //если админ о разрешаем
		}
	}
	//обрезка картинки


}
