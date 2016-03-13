<?php

use yii\db\Migration;

class m160313_183944_rename_camelcase_columns extends Migration
{

    public function up()
    {
        $this->renameColumn('user', 'accessToken', 'access_token');
        $this->renameColumn('user', 'lastLogin', 'last_login');
    }

    public function down()
    {
        $this->renameColumn('user', 'access_token', 'accessToken');
        $this->renameColumn('user', 'last_login', 'lastLogin');
    }

}
