<?php

use yii\db\Migration;

class m160323_185734_change_undercase_to_camelcase extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('{{%access_token}}', 'id_user', 'idUser');
        $this->renameColumn('{{%access_token}}', 'expires_in', 'expiresIn');
        $this->renameColumn('{{%access_token}}', 'created_at', 'createdAt');
        
        $this->renameColumn('{{%j_user_user_group}}', 'id_user', 'idUser');
        $this->renameColumn('{{%j_user_user_group}}', 'id_user_group', 'idUserGroup');
        
        $this->renameColumn('{{%project}}', 'started_at', 'startedAt');
        $this->renameColumn('{{%project}}', 'ended_at', 'endedAt');
        $this->renameColumn('{{%project}}', 'is_active', 'isActive');
        
        $this->renameColumn('{{%project_user}}', 'id_user', 'idUser');
        $this->renameColumn('{{%project_user}}', 'id_project', 'idProject');
        $this->renameColumn('{{%project_user}}', 'attached_at', 'attachedAt');
        $this->renameColumn('{{%project_user}}', 'is_active', 'isActive');
        
        $this->renameColumn('{{%project_user_record}}', 'id_project_user', 'idProjectUser');
        
        $this->renameColumn('{{%user}}', 'id_gender', 'idGender');
        $this->renameColumn('{{%user}}', 'password_hash', 'passwordHash');
        
        $this->renameColumn('{{%user_group}}', 'main_role', 'mainRole');
    }

    public function safeDown()
    {
        $this->renameColumn('{{%access_token}}', 'idUser', 'id_user');
        $this->renameColumn('{{%access_token}}', 'expiresIn', 'expires_in');
        $this->renameColumn('{{%access_token}}', 'createdAt', 'created_at');
        
        $this->renameColumn('{{%j_user_user_group}}', 'idUser', 'id_user');
        $this->renameColumn('{{%j_user_user_group}}', 'idUserGroup', 'id_user_group');
        
        $this->renameColumn('{{%project}}', 'startedAt', 'started_at');
        $this->renameColumn('{{%project}}', 'endedAt', 'ended_at');
        $this->renameColumn('{{%project}}', 'isActive', 'is_active');
        
        $this->renameColumn('{{%project_user}}', 'idUser', 'id_user');
        $this->renameColumn('{{%project_user}}', 'idProject', 'id_project');
        $this->renameColumn('{{%project_user}}', 'attachedAt', 'attached_at');
        $this->renameColumn('{{%project_user}}', 'isActive', 'is_active');
        
        $this->renameColumn('{{%project_user_record}}', 'idProjectUser', 'id_project_user');
        
        $this->renameColumn('{{%user}}', 'idGender', 'id_gender');
        $this->renameColumn('{{%user}}', 'passwordHash', 'password_hash');
        
        $this->renameColumn('{{%user_group}}', 'mainRole', 'main_role');
    }

}
