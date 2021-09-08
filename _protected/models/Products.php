<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $title
 * @property float $price
 *
 * @property Carts[] $carts
 * @property CartsProducts[] $cartsProducts
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'price'], 'required'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'price' => Yii::t('app', 'Price'),
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Carts::className(), ['id' => 'carts_id'])->viaTable('carts_products', ['products_id' => 'id']);
    }

    /**
     * Gets query for [[CartsProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCartsProducts()
    {
        return $this->hasMany(CartsProducts::className(), ['products_id' => 'id']);
    }
}
