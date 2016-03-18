<?php

use yii\db\Migration;

class m160315_190553_set_access_token_token_unique extends Migration
{
    public function up()
    {
        $this->createIndex('idx-access_token-token', '{{%access_token}}', 'token', true);
    }

    public function down()
    {
        $this->dropIndex('idx-access_token-token', '{{%access_token}}');
    }
}
