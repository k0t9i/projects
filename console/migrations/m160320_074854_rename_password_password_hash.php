<?php

use yii\db\Migration;

class m160320_074854_rename_password_password_hash extends Migration
{
    public function up()
    {
        if (!$this->db->driverName == 'sqlite') {
            $this->renameColumn('{{%user}}', 'password', 'password_hash');
        } else {
            $this->dropColumn('{{%user}}', 'password');
            $this->addColumn('{{%user}}', 'password_hash', $this->string(64)->notNull());
        }
    }

    public function down()
    {
        if (!$this->db->driverName == 'sqlite') {
            $this->renameColumn('{{%user}}', 'password_hash', 'password');
        } else {
            $this->dropColumn('{{%user}}', 'password_hash');
            $this->addColumn('{{%user}}', 'password', $this->string(64)->notNull());
        }
    }
}
