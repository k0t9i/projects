<?php

use yii\db\Migration;

class m160313_183944_rename_camelcase_columns extends Migration
{

    public function up()
    {
        if (!$this->db->driverName == 'sqlite') {
            $this->renameColumn('{{%user}}', 'accessToken', 'access_token');
            $this->renameColumn('{{%user}}', 'lastLogin', 'last_login');
        } else {
            $this->dropColumn('{{%user}}', 'accessToken');
            $this->addColumn('{{%user}}', 'access_token', $this->string(256));
            $this->dropColumn('{{%user}}', 'lastLogin');
            $this->addColumn('{{%user}}', 'last_login', $this->dateTime());
        }
    }

    public function down()
    {
        if (!$this->db->driverName == 'sqlite') {
            $this->renameColumn('{{%user}}', 'access_token', 'accessToken');
            $this->renameColumn('{{%user}}', 'last_login', 'lastLogin');
        } else {
            $this->dropColumn('{{%user}}', 'access_token');
            $this->addColumn('{{%user}}', 'accessToken', $this->string(256));
            $this->dropColumn('{{%user}}', 'last_login');
            $this->addColumn('{{%user}}', 'lastLogin', $this->dateTime());
        }
    }

}
