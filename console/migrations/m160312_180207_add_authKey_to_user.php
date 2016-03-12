<?php

use yii\db\Migration;

class m160312_180207_add_authKey_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'authKey', $this->string(256));
    }

    public function down()
    {
        $this->dropColumn('user', 'authKey');
    }
}
