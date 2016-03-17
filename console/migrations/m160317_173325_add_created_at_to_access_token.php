<?php

use yii\db\Migration;

class m160317_173325_add_created_at_to_access_token extends Migration
{
    public function up()
    {
        $this->addColumn('access_token', 'created_at', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('access_token', 'created_at');
    }
}
