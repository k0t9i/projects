<?php

use yii\db\Migration;

class m160317_180312_set_created_at_is_not_null extends Migration
{
    public function safeUp()
    {
        $this->delete('access_token');
        $this->dropColumn('access_token', 'created_at');
        $this->addColumn('access_token', 'created_at', $this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('access_token', 'created_at');
        $this->addColumn('access_token', 'created_at', $this->integer());
    }
}
