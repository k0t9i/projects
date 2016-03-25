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
        $projectRoles = $this->initProjectRoles($auth, 'project', $rules);
        $userGroupRoles = $this->initUserGroupRoles($auth, 'user-group', $rules);
        
        $auth->addChild($performer, $tokenRoles['deleteOwn']);
        $auth->addChild($performer, $tokenRoles['viewOwn']);
        $auth->addChild($performer, $userRoles['viewOwn']);
        $auth->addChild($performer, $userRoles['updateOwn']);
        $auth->addChild($performer, $userRoles['ownProjects']);
        $auth->addChild($performer, $userRoles['ownUserGroups']);
        $auth->addChild($performer, $projectRoles['viewOwn']);
        $auth->addChild($performer, $projectRoles['ownUsers']);
        $auth->addChild($performer, $userGroupRoles['ownPermissions']);
        
        $auth->addChild($manager, $tokenRoles['deleteOwn']);
        $auth->addChild($manager, $tokenRoles['viewOwn']);
        $auth->addChild($manager, $userRoles['viewOwn']);
        $auth->addChild($manager, $userRoles['updateOwn']);
        $auth->addChild($manager, $userRoles['ownProjects']);
        $auth->addChild($manager, $userRoles['ownUserGroups']);
        $auth->addChild($manager, $projectRoles['create']);
        $auth->addChild($manager, $projectRoles['viewOwn']);
        $auth->addChild($manager, $projectRoles['updateOwn']);
        $auth->addChild($manager, $projectRoles['ownUsers']);
        $auth->addChild($manager, $userGroupRoles['ownPermissions']);
        
        $auth->addChild($chief, $tokenRoles['deleteOwn']);
        $auth->addChild($chief, $tokenRoles['viewOwn']);
        $auth->addChild($chief, $userRoles['view']);
        $auth->addChild($chief, $userRoles['viewAll']);
        $auth->addChild($chief, $userRoles['create']);
        $auth->addChild($chief, $userRoles['update']);
        $auth->addChild($chief, $userRoles['projects']);
        $auth->addChild($chief, $userRoles['userGroups']);
        $auth->addChild($chief, $projectRoles['view']);
        $auth->addChild($chief, $projectRoles['viewAll']);
        $auth->addChild($chief, $projectRoles['create']);
        $auth->addChild($chief, $projectRoles['update']);
        $auth->addChild($chief, $projectRoles['users']);
        $auth->addChild($chief, $userGroupRoles['users']);
        $auth->addChild($chief, $userGroupRoles['permissions']);
        
        $auth->addChild($admin, $tokenRoles['delete']);
        $auth->addChild($admin, $tokenRoles['deleteAll']);
        $auth->addChild($admin, $tokenRoles['view']);
        $auth->addChild($admin, $tokenRoles['viewAll']);
        $auth->addChild($admin, $userRoles['view']);
        $auth->addChild($admin, $userRoles['viewAll']);
        $auth->addChild($admin, $userRoles['create']);
        $auth->addChild($admin, $userRoles['update']);
        $auth->addChild($admin, $userRoles['delete']);
        $auth->addChild($admin, $userRoles['projects']);
        $auth->addChild($admin, $userRoles['userGroups']);
        $auth->addChild($admin, $projectRoles['view']);
        $auth->addChild($admin, $projectRoles['viewAll']);
        $auth->addChild($admin, $projectRoles['create']);
        $auth->addChild($admin, $projectRoles['update']);
        $auth->addChild($admin, $projectRoles['delete']);
        $auth->addChild($admin, $projectRoles['users']);
        $auth->addChild($admin, $userGroupRoles['users']);
        $auth->addChild($admin, $userGroupRoles['permissions']);
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
        
        $ret['viewOwn'] = $manager->createPermission($key . '.viewOwn');
        $ret['viewOwn']->description = 'View own user';
        $ret['viewOwn']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['viewOwn']);
        $manager->addChild($ret['viewOwn'], $ret['view']);
        
        $ret['update'] = $manager->createPermission($key . '.update');
        $ret['update']->description = 'Update user';
        $manager->add($ret['update']);
        
        $ret['updateOwn'] = $manager->createPermission($key . '.updateOwn');
        $ret['updateOwn']->description = 'Update own user';
        $ret['updateOwn']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['updateOwn']);
        $manager->addChild($ret['updateOwn'], $ret['update']);
        
        $ret['delete'] = $manager->createPermission($key . '.delete');
        $ret['delete']->description = 'Delete user';
        $manager->add($ret['delete']);
        
        $ret['projects'] = $manager->createPermission($key . '.projects');
        $ret['projects']->description = 'View user projects list';
        $manager->add($ret['projects']);
        
        $ret['ownProjects'] = $manager->createPermission($key . '.ownProjects');
        $ret['ownProjects']->description = 'View own user projects list';
        $ret['ownProjects']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['ownProjects']);
        $manager->addChild($ret['ownProjects'], $ret['projects']);
        
        $ret['userGroups'] = $manager->createPermission($key . '.userGroups');
        $ret['userGroups']->description = 'View user usergroups list';
        $manager->add($ret['userGroups']);
        
        $ret['ownUserGroups'] = $manager->createPermission($key . '.ownUserGroups');
        $ret['ownUserGroups']->description = 'View own user usergroups list';
        $ret['ownUserGroups']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['ownUserGroups']);
        $manager->addChild($ret['ownUserGroups'], $ret['userGroups']);
        
        return $ret;        
    }
    
    private function initProjectRoles(ManagerInterface $manager, $key, array $rules)
    {
        $ret = [];
        
        $ret['create'] = $manager->createPermission($key . '.create');
        $ret['create']->description = 'Create project';
        $manager->add($ret['create']);
        
        $ret['viewAll'] = $manager->createPermission($key . '.viewAll');
        $ret['viewAll']->description = 'View all projects';
        $manager->add($ret['viewAll']);
        
        $ret['view'] = $manager->createPermission($key . '.view');
        $ret['view']->description = 'View project';
        $manager->add($ret['view']);
        
        $ret['viewOwn'] = $manager->createPermission($key . '.viewOwn');
        $ret['viewOwn']->description = 'View own project';
        $ret['viewOwn']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['viewOwn']);
        $manager->addChild($ret['viewOwn'], $ret['view']);
        
        $ret['update'] = $manager->createPermission($key . '.update');
        $ret['update']->description = 'Update project';
        $manager->add($ret['update']);
        
        $ret['updateOwn'] = $manager->createPermission($key . '.updateOwn');
        $ret['updateOwn']->description = 'Update own project';
        $ret['updateOwn']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['updateOwn']);
        $manager->addChild($ret['updateOwn'], $ret['update']);
        
        $ret['delete'] = $manager->createPermission($key . '.delete');
        $ret['delete']->description = 'Delete project';
        $manager->add($ret['delete']);
        
        $ret['users'] = $manager->createPermission($key . '.users');
        $ret['users']->description = 'View project users list';
        $manager->add($ret['users']);
        
        $ret['ownUsers'] = $manager->createPermission($key . '.ownUsers');
        $ret['ownUsers']->description = 'View own project users list';
        $ret['ownUsers']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['ownUsers']);
        $manager->addChild($ret['ownUsers'], $ret['users']);
        
        return $ret;        
    }
    
    private function initUserGroupRoles(ManagerInterface $manager, $key, array $rules)
    {
        $ret = [];
        
        $ret['users'] = $manager->createPermission($key . '.users');
        $ret['users']->description = 'View user group users';
        $manager->add($ret['users']);
        
        $ret['permissions'] = $manager->createPermission($key . '.permissions');
        $ret['permissions']->description = 'View user group permissions';
        $manager->add($ret['permissions']);
         
        $ret['ownPermissions'] = $manager->createPermission($key . '.ownPermissions');
        $ret['ownPermissions']->description = 'View own user group permissions';
        $ret['ownPermissions']->ruleName = $rules['ownerRule']->name;
        $manager->add($ret['ownPermissions']);
        $manager->addChild($ret['ownPermissions'], $ret['permissions']);
   
        return $ret;        
    }

}
