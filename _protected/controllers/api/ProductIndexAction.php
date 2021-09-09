<?php

namespace app\controllers\api;

use Yii;
use yii\rest\IndexAction;

/**
 * Description of ProductIndexAction
 *
 * @author Andrzej Ambroziewicz
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
