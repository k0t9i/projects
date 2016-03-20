<?php

use yii\db\Migration;

class m160314_163921_create_access_token_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%access_token}}', [
            'token' => $this->string(256)->notNull(),
            'id_user' => $this->integer()->notNull(),
            'expires_in' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('pk-access_token', '{{%access_token}}', 'token');
        $this->createIndex('idx-access_token-id_user', '{{%access_token}}', 'id_user');
        if (!$this->db->driverName == 'sqlite') {
            $this->addForeignKey('fk-access_token-user', '{{%access_token}}', 'id_user', '{{%user}}', 'id');
        }
    }

    public function down()
    {
        $this->dropTable('{{%access_token}}');
    }

}
