<?php

use yii\db\Migration;

class m160318_193814_create_project_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1024)->notNull(),
            'description' => $this->text(),
            'started_at' => $this->integer()->notNull(),
            'ended_at' => $this->integer(),
            'is_active' => $this->boolean()
        ]);
        
        $this->createTable('{{%project_user}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_project' => $this->integer()->notNull(),
            'attached_at' => $this->integer()->notNull(),
            'is_active' => $this->boolean()
        ]);
        
        $this->createIndex('idx-project_user-id_user-id_project', '{{%project_user}}', ['id_user', 'id_project'], true);

        $this->addForeignKey('fk-project_user-id_user', '{{%project_user}}', 'id_user', '{{%user}}', 'id');
        $this->addForeignKey('fk-project_user-id_project', '{{%project_user}}', 'id_project', '{{%project}}', 'id');
        
        $this->createTable('{{%project_user_record}}', [
            'id' => $this->primaryKey(),
            'id_project_user' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull()
        ]);
        
        $this->createIndex('idx-project_user_record-id_project_user', '{{%project_user_record}}', 'id_project_user');
        
        $this->addForeignKey('fk-project_user_record-id_project_user', '{{%project_user_record}}', 'id_project_user', '{{%project_user}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%project_user_record}}');
        $this->dropTable('{{%project_user}}');
        $this->dropTable('{{%project}}');
    }
}
