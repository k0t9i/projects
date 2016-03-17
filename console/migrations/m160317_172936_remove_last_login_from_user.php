<?php

use yii\db\Migration;

class m160317_172936_remove_last_login_from_user extends Migration
{
    public function up()
    {
        $this->dropColumn('user', 'last_login');
    }

    public function down()
    {
        $this->addColumn('user', 'last_login', $this->dateTime());
    }
}
