<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\web\ServerErrorHttpException;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotAcceptableHttpException;
use Yii;
use app\models\Products;
use app\models\CartsProducts;

/**
 * Controller for cart administration
 *
 * @author Andrzej Ambroziewicz
 */
class CartController extends Controller {

    protected function verbs() {
        return [
            'index' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
//        unset($behaviors['verbFilter']);
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Disables session and login for rest
     */
    public function init() {
        parent::init();
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;
    }

    /*
     * Run check access before action
     */

    public function beforeAction($action) {
        $this->checkAccess($action);
        return parent::beforeAction($action);
    }

    /*
     * @return 
     */

    protected function getUser() {
        return \Yii::$app->user->identity;
    }

    public function checkIfCartExists() {
        if (!$this->getUser()->hasCart()) {
            throw new MethodNotAllowedHttpException(Yii::t('app', 'First you need to create a cart.'));
        }
    }

    /*
     * List products in cart
     */

    public function actionIndex() {
        $this->checkIfCartExists();
        $cart = $this->getUser()->cartsProducts;
        $products = [];
        $total = 0;

        foreach ($cart as $productInCart) {
            /* @var $productInCart app\models\CartsProducts */

            $products[] = ['product_id' => $productInCart->products_id, 'amount' => $productInCart->amount];
            $total += $productInCart->products->price * $productInCart->amount;
        }

        return [
            'products' => $products,
            'total' => \Yii::$app->formatter->asDecimal($total, 2)
        ];
    }

    /*
     * Creates cart
     */

    public function actionCreate() {
        $user = $this->getUser();
        $cartVal = $user->createCart();

        if ($user->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$user->hasErrors()) {
            throw new ServerErrorHttpException(Yii::t('app', 'Failed to create the object for unknown reason.'));
        } else {
            throw new NotAcceptableHttpException(Yii::t('app', 'Unable to create cart for user'));
        }

        return ['created_at' => $cartVal];
    }

    /*
     * get params from request
     */

    protected function getParams() {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return $requestParams;
    }

    /*
     * Add product to cart cart
     */

    public function actionUpdate() {
        $this->checkIfCartExists();
        $params = $this->getParams();
        if (!array_key_exists('product_id', $params) || !array_key_exists('amount', $params)) {
            throw new NotFoundHttpException(Yii::t('app', 'Unable to find "product_id" and "amount" params in request.'));
        }
        if ($params['amount'] != intval($params['amount'])) {
            throw new RangeNotSatisfiableHttpException(Yii::t('app', 'Amount should be an integer value.'));
        }

        $product = Products::findOne($params['product_id']);
        if (!isset($product))
            throw new NotFoundHttpException(Yii::t('app', 'Unable to find product with product_id: {id}.', ['id' => $params['product_id']]));

        $user = $this->getUser();
        $cp = $user->getCartsProducts()->where(['products_id' => $product->id])->one();
        if (isset($cp)) {
            //Product already exists;
            $cp->amount += intval($params['amount']);
        } else {
            if ($user->getCartsProducts()->count() >= \Yii::$app->params['maxElementsInCart'])
                throw new MethodNotAllowedHttpException(Yii::t('app', 'Cart is full.'));

            $cp = new CartsProducts(['user_id' => $user->id, 'products_id' => $product->id, 'amount' => $params['amount']]);
        }

        if ($cp->save() === false && !$cp->hasErrors()) {
            throw new ServerErrorHttpException(Yii::t('app', 'Failed to update the object for unknown reason.'));
        }
        if ($cp->hasErrors()) {
            throw new MethodNotAllowedHttpException(Yii::t('app', 'Unable to save cart position.'));
        }

        return ['product_id' => $cp->products_id, 'amount' => $cp->amount];
    }

    /*
     * Delete product from cart
     */

    public function actionDelete() {
        $this->checkIfCartExists();
        $params = $this->getParams();
        if (!array_key_exists('product_id', $params)) {
            throw new NotFoundHttpException(Yii::t('app', 'Unable to find "product_id" param in request.'));
        }
        $cp = $this->getUser()->getCartsProducts()->where(['products_id' => $params['product_id']])->one();

        if (!isset($cp))
            throw new NotFoundHttpException(Yii::t('app', 'Unable to find product with product_id: {id}.', ['id' => $params['product_id']]));

        if ($cp->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    //TODO: Uzupełnić w razie potrzeb
    public function checkAccess($action) {
        
    }

}
