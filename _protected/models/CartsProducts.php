<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carts_products".
 *
 * @property int $carts_id
 * @property int $user_id
 * @property int $products_id
 * @property int $amount
 *
 * @property Carts $carts 
 * @property Products $products
 * @property User $user 
 */
class CartsProducts extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'carts_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'products_id', 'amount'], 'required'],
            [['user_id', 'products_id', 'amount'], 'integer'],
            ['amount', 'in', 'range' => range(1, \Yii::$app->params['maxAmountOfProduct'])],
            [['user_id', 'products_id'], 'unique', 'targetAttribute' => ['user_id', 'products_id']],
            [['products_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['products_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'products_id' => Yii::t('app', 'Products ID'),
            'amount' => Yii::t('app', 'Amount'),
        ];
    }

    /**
     * Gets query for [[User]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasOne(Products::className(), ['id' => 'products_id']);
    }

}
