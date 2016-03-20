<?php

use yii\db\Migration;

class m160312_163824_create_user_table extends Migration {

    public function safeUp()
    {        
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(256)->notNull()->unique(),
            'password' => $this->string(64)->notNull(),
            'lastname' => $this->string(1024),
            'firstname' => $this->string(1024),
            'middlename' => $this->string(1024),
            'id_gender' => $this->integer(),
            'email' => $this->string(256)->notNull()->unique(),
            'lastLogin' => $this->dateTime(),
        ]);
        $this->createIndex('idx-user-id_gender', '{{%user}}', 'id_gender');
        if (!$this->db->driverName == 'sqlite') {
            $this->addForeignKey('fk-user-d_gender', '{{%user}}', 'id_gender', '{{%d_gender}}', 'id');
        }
    }

    public function down()
    {
        $this->dropTable('user');
    }

}
