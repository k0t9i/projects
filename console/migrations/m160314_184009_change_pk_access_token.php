<?php

use yii\db\Migration;

class m160314_184009_change_pk_access_token extends Migration
{

    public function safeUp()
    {
        $this->dropPrimaryKey('pk-access_token', '{{%access_token}}');
        $this->addColumn('{{%access_token}}', 'id', $this->primaryKey());
        
    }

    public function safeDown()
    {
        $this->dropColumn('{{%access_token}}', 'id');
        $this->addPrimaryKey('pk-access_token', '{{%access_token}}', 'token');
    }

}
