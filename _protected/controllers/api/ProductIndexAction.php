<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use Yii;
use yii\rest\IndexAction;

/**
 * Description of ProductIndexAction
 *
 * @author propa
 */
class ProductIndexAction extends IndexAction {

    protected function prepareDataProvider() {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        $dp = parent::prepareDataProvider();
        $dp->setPagination(['params' => $requestParams, 'pageSize' => 3,]);
        return $dp;
    }

}
