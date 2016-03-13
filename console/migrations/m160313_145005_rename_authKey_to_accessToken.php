<?php

use yii\db\Migration;

class m160313_145005_rename_authKey_to_accessToken extends Migration {

    public function up()
    {
        $this->renameColumn('user', 'authKey', 'accessToken');
    }

    public function down()
    {
        $this->renameColumn('user', 'accessToken', 'authKey');
    }

}
