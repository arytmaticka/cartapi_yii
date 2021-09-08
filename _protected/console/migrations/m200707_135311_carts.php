<?php

use yii\db\Migration;
use app\models\User;
use app\models\Carts;
use app\models\CartsProducts;
use app\models\Products;
/**
 * Class m200707_135311_carts
 */
class m200707_135311_carts extends Migration
{

    public function up()
    {
        $this->createTable(CartsProducts::tableName(), [
            'user_id' => $this->integer()->notNull(),
            'products_id' => $this->integer()->notNull(),
            'amount'=>$this->integer()->unsigned()->notNull()
        ]);
        
        
        $this->addForeignKey('fk_carts_products_user_id', CartsProducts::tableName(), 'user_id', User::tableName(), 'id');
        $this->addForeignKey('fk_carts_products_products_id', CartsProducts::tableName(), 'products_id', Products::tableName(), 'id');
        $this->createIndex('unq_carts_products', CartsProducts::tableName(), ['user_id','products_id'], true);
    }

    public function down()
    {
        $this->dropTable('carts_products');
    }
}
