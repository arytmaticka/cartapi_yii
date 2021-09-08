<?php

use yii\db\Migration;
use app\models\Products;

/**
 * Class m200707_135305_products
 */
class m200707_135305_products extends Migration
{
    protected function table(){
        return Products::tableName();
    }


    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_polish_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table(), [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique(),
            'price' => $this->decimal(10,2)->notNull(),            
        ], $tableOptions);
        
        $this->insertProducts();
    }

    private function insertProducts(){
        
        $this->insert($this->table(), [
            'title'=>'Fallout',
            'price'=>1.99
        ]);
        $this->insert($this->table(), [
            'title'=>'Don’t Starve',
            'price'=>2.99
        ]);
        $this->insert($this->table(), [
            'title'=>'Baldur’s Gate',
            'price'=>3.99
        ]);
        $this->insert($this->table(), [
            'title'=>'Icewind Dale',
            'price'=>4.99
        ]);
        $this->insert($this->table(), [
            'title'=>'Bloodborne',
            'price'=>5.99
        ]);        
        
    }
    
    public function down()
    {
        $this->dropTable($this->table());
    }
}
