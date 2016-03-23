<?php

use yii\db\Migration;

class m160323_192925_add_isActive_to_user extends Migration
{

    public function up()
    {
        $this->addColumn('{{%user}}', 'isActive', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'isActive');
    }

}
