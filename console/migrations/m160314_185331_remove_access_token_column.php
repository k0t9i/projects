<?php

use yii\db\Migration;

class m160314_185331_remove_access_token_column extends Migration
{

    public function up()
    {
        $this->dropColumn('{{%user}}', 'access_token');
    }

    public function down()
    {
        $this->addColumn('{{%user}}', 'access_token', $this->string(256));
    }

}
