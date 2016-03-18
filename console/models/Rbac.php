<?php

namespace console\models;

use yii\base\Model;
use api\rbac\OwnerRule;

class Rbac extends Model
{

    public function initRoles()
    {
        \yii\rbac\ManagerInterface::class;
        $auth = \Yii::$app->authManager;
        
        $auth->removeAll();
        
        $rule = new OwnerRule();
        $auth->add($rule);
        
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

        $deleteAll = $auth->createPermission('access-token.deleteAll');
        $deleteAll->description = 'Delete all access tokens';
        $auth->add($deleteAll);
        
        $delete = $auth->createPermission('access-token.delete');
        $delete->description = 'Delete access token';
        $auth->add($delete);
        
        $deleteOwn = $auth->createPermission('access-token.deleteOwn');
        $deleteOwn->description = 'Delete own access token';
        $deleteOwn->ruleName = $rule->name;
        $auth->add($deleteOwn);
        $auth->addChild($deleteOwn, $delete);
        
        $viewAll = $auth->createPermission('access-token.viewAll');
        $viewAll->description = 'View all access tokens';
        $auth->add($viewAll);
        
        $view = $auth->createPermission('access-token.view');
        $view->description = 'View access token';
        $auth->add($view);
        
        $viewOwn = $auth->createPermission('access-token.viewOwn');
        $viewOwn->description = 'View own access token';
        $viewOwn->ruleName = $rule->name;
        $auth->add($viewOwn);
        $auth->addChild($viewOwn, $view);
        
        $auth->addChild($performer, $deleteOwn);
        $auth->addChild($performer, $viewOwn);
        
        $auth->addChild($manager, $deleteOwn);
        $auth->addChild($manager, $viewOwn);
        
        $auth->addChild($chief, $deleteOwn);
        $auth->addChild($chief, $viewOwn);
        
        $auth->addChild($admin, $delete);
        $auth->addChild($admin, $deleteAll);
        $auth->addChild($admin, $view);
        $auth->addChild($admin, $viewAll);
        
        //$auth->assign($admin, 1);
        //$auth->assign($performer, 3);
    }

}
