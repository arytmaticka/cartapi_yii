<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
    public function authenticateToApi($username) {
        $query = new \yii\db\Query;
        $auth = $query->select('auth_key')->from('user')->where(['username' => $username])->scalar();
        $api=$this->getModule('REST');
        $api->amHttpAuthenticated($auth, '');
        $api->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    public function checkResponse($response, $code = \Codeception\Util\HttpCode::OK){
        $api=$this->getModule('REST');
        $api->seeResponseCodeIs($code);
        $api->seeResponseIsJson();
        $api->seeResponseContains($response);
    }
}
