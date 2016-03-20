<?php

use yii\db\Migration;

class m160313_185944_add_user_group_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%user_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1024)->notNull(),
            'main_role' => $this->string(64)->notNull()
        ]);
        
        $this->createIndex('idx-user_group-main_role', '{{%user_group}}', 'main_role');
        if (!$this->db->driverName == 'sqlite') {
            $this->addForeignKey('fk-user_group-auth_item', '{{%user_group}}', 'main_role', 'auth_item', 'name');
        }
        
        
        $this->createTable('{{%j_user_user_group}}', [
            'id_user' => $this->integer(),
            'id_user_group' => $this->integer(),
            'PRIMARY KEY(id_user, id_user_group)'
        ]);
        
        $this->createIndex('idx-j_user_user_group-id_user', '{{%j_user_user_group}}', 'id_user');
        $this->createIndex('idx-j_user_user_group-id_user_group', '{{%j_user_user_group}}', 'id_user_group');

        if (!$this->db->driverName == 'sqlite') {
            $this->addForeignKey('fk-j_user_user_group-id_user', '{{%j_user_user_group}}', 'id_user', '{{%user}}', 'id', 'CASCADE');
            $this->addForeignKey('fk-j_user_user_group-id_user_group', '{{%j_user_user_group}}', 'id_user_group', '{{%user_group}}', 'id', 'CASCADE');
        }
    }

    public function safeDown()
    {
        $this->dropTable('{{%j_user_user_group}}');
        $this->dropTable('{{%user_group}}');
    }

}
