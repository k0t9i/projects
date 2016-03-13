<?php

use yii\db\Migration;

class m160312_162824_create_d_gender_table extends Migration {

    public function safeUp()
    {
        $this->createTable('d_gender', [
            'id' => $this->primaryKey(),
            'name' => $this->string(256)->notNull()->unique()
        ]);
        $this->insert('d_gender', [
            'name' => 'male'
        ]);
        $this->insert('d_gender', [
            'name' => 'female'
        ]);
    }

    public function down()
    {
        $this->dropTable('d_gender');
    }

}


