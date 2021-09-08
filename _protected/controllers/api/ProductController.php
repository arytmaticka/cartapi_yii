<?php

namespace app\controllers\api;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;

/**
 * Support REST API for products management
 *
 * @author Andrzej Ambroziewicz
 */
class ProductController extends ActiveController {

    public $modelClass = 'app\models\Products';

    public function init() {
        parent::init();
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        $actions = parent::actions();
        $actions['index']['class'] = 'app\controllers\api\ProductIndexAction';
        return $actions;
    }

    //TODO: Uzupełnić w razie potrzeb
    public function checkAccess($action, $model = null, $params = []) {
        
    }

}
