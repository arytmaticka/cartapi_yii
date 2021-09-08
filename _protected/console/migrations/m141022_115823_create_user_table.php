<?php
use yii\db\Migration;

class m141022_115823_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_polish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'account_activation_token' => $this->string()->unique(),          
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'has_cart'=>$this->integer()->defaultValue(0)->notNull()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
