<?php

use yii\db\Migration;
use app\models\User;

/**
 * Class m200707_143229_create_default_user
 */
class m200707_143229_create_default_user extends Migration {

    // Use up()/down() to run migration code without a transaction.
    public function up() {
        $user = $this->createAdminUser();

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('theCreator');
        $info = $auth->assign($role, $user->id);

        // if assignment was successful return true, else return false to alarm the problem
        if ($info->roleName != "theCreator")
            throw new Exception(Yii::t('app', 'Unable to assign role theCreator'));
        
        $this->createTestUser();
        
    }
    
    protected function createAdminUser(){
        $user = new User();

        $user->username = 'cartapi';
        $user->email = 'cartapi@gog.com';
        $user->setPassword('cartapi');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        if (!$user->save()) {
            throw new Exception(Yii::t('app', 'Unable to save admin user'));
        }
        return $user;
    }
    
    protected function createTestUser(){
        $user = new User();

        $user->username = 'test';
        $user->email = 'test@gog.com';
        $user->setPassword('testtest');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        if (!$user->save()) {
            throw new Exception(Yii::t('app', 'Unable to save test user'));
        }
        return $user;
    }

    public function down() {
        $user =  User::find()->where(['username'=>'cartapi'])->one();
        return $user->delete();

        
    }

}
