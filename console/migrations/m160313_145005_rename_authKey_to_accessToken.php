<?php

use yii\db\Migration;

class m160313_145005_rename_authKey_to_accessToken extends Migration {

    public function up()
    {
        if (!$this->db->driverName == 'sqlite') {
            $this->renameColumn('{{%user}}', 'authKey', 'accessToken');
        } else {
            $this->dropColumn('{{%user}}', 'authKey');
            $this->addColumn('{{%user}}', 'accessToken', $this->string(256));
        }
    }

    public function down()
    {
        if (!$this->db->driverName == 'sqlite') {
            $this->renameColumn('{{%user}}', 'accessToken', 'authKey');
        } else {
            $this->dropColumn('{{%user}}', 'accessToken');
            $this->addColumn('{{%user}}', 'authKey', $this->string(256));
        }
    }

}
