<?php

use yii\db\Migration;

class m160320_074854_rename_password_password_hash extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%user}}', 'password', 'password_hash');
    }

    public function down()
    {
        $this->renameColumn('{{%user}}', 'password_hash', 'password');
    }
}
