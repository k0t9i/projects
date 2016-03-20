<?php

namespace console\models;

use yii\base\Model;
use api\rbac\OwnerRule;
use yii\rbac\ManagerInterface;

class Rbac extends Model
{

    public $db;
    
    public function init()
    {
        parent::init();
        if (is_null($this->db)) {
            $this->db = \Yii::$app->db;
        } else {
            $this->db = \yii\di\Instance::ensure($this->db, \yii\db\Connection::className());
        }
    }
    
    public function initRoles()
    {
        /* @var $auth \yii\rbac\ManagerInterface */
        $auth = \Yii::$app->authManager;
        $auth->db = $this->db;
        
        $auth->removeAll();
        
        $rules = [
            'ownerRule' => new OwnerRule()
        ];
        foreach ($rules as $rule) {
            $auth->add($rule);
        }
        
        $performer = $auth->createRole('performer');
        $performer->description = 'Project performer';
        $auth->add($performer);
        
        $manager = $auth->createRole('manager');
        $manager->description = 'Project manager';
        $auth->add($manager);
        
        $chief = $auth->createRole('chief');
        $chief->description = 'Organization chief';
        $auth->add($chief);
        
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);
        
        $tokenRoles = $this->initAccessTokenRoles($auth, 'access-token', $rules);
        $userRoles = $this->initUserRoles($auth, 'user', $rules);
        
        $auth->addChild($performer, $tokenRoles['deleteOwn']);
        $auth->addChild($performer, $tokenRoles['viewOwn']);
        
        $auth->addChild($manager, $tokenRoles['deleteOwn']);
        $auth->addChild($manager, $tokenRoles['viewOwn']);
        
        $auth->addChild($chief, $tokenRoles['deleteOwn']);
        $auth->addChild($chief, $tokenRoles['viewOwn']);
        $auth->addChild($chief, $userRoles['view']);
        $auth->addChild($chief, $userRoles['viewAll']);
        $auth->addChild($chief, $userRoles['create']);
        $auth->addChild($chief, $userRoles['update']);
        $auth->addChild($chief, $userRoles['delete']);
        
        $auth->addChild($admin, $tokenRoles['delete']);
        $auth->addChild($admin, $tokenRoles['deleteAll']);
        $auth->addChild($admin, $tokenRoles['view']);
        $auth->addChild($admin, $tokenRoles['viewAll']);
        $auth->addChild($admin, $userRoles['view']);
        $auth->addChild($admin, $userRoles['viewAll']);
        $auth->addChild($admin, $userRoles['create']);
        $auth->addChild($admin, $userRoles['update']);
        $auth->addChild($admin, $userRoles['delete']);
    }
    
    private function initAccessTokenRoles(ManagerInterface $manager, $key, array $rules)
    {
        $ret = [];
        
        $ret['deleteAll'] = $manager->createPermission($key . '.deleteAll');
        $ret['deleteAll']->description = 'Delete all access tokens';
        $manager->add($ret['deleteAll']);
        
        $ret['delete'] = $manager->createPermission($key . '.delete');
        $ret['delete']->description = 'Delete access token';
        $manager->add($ret['delete']);
        
        $ret['deleteOwn'] = $manager->createPermission($key . '.deleteOwn');
        $ret['deleteOwn']->description = 'Delete own access token';
        $ret['deleteOwn']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['deleteOwn']);
        $manager->addChild($ret['deleteOwn'], $ret['delete']);
        
        $ret['viewAll'] = $manager->createPermission($key . '.viewAll');
        $ret['viewAll']->description = 'View all access tokens';
        $manager->add($ret['viewAll']);
        
        $ret['view'] = $manager->createPermission($key . '.view');
        $ret['view']->description = 'View access token';
        $manager->add($ret['view']);
        
        $ret['viewOwn'] = $manager->createPermission($key . '.viewOwn');
        $ret['viewOwn']->description = 'View own access token';
        $ret['viewOwn']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['viewOwn']);
        $manager->addChild($ret['viewOwn'], $ret['view']);
        
        return $ret;        
    }
    
    private function initUserRoles(ManagerInterface $manager, $key, array $rules)
    {
        $ret = [];
        
        $ret['create'] = $manager->createPermission($key . '.create');
        $ret['create']->description = 'Create user';
        $manager->add($ret['create']);
        
        $ret['viewAll'] = $manager->createPermission($key . '.viewAll');
        $ret['viewAll']->description = 'View all users';
        $manager->add($ret['viewAll']);
        
        $ret['view'] = $manager->createPermission($key . '.view');
        $ret['view']->description = 'View user';
        $manager->add($ret['view']);
        
        $ret['update'] = $manager->createPermission($key . '.update');
        $ret['update']->description = 'Update user';
        $manager->add($ret['update']);
        
        $ret['delete'] = $manager->createPermission($key . '.delete');
        $ret['delete']->description = 'Delete user';
        $manager->add($ret['delete']);
        
        return $ret;        
    }

}
