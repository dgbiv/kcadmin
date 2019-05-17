<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use backend\models\AuthUser;
use mdm\admin\models\Menu;
use mdm\admin\components\MenuHelper;

/**
 * Description of RbacsetController
 *
 * @author blobt
 */
class InitController extends Controller
{

    public function beforeAction($action)
    {

        $this->role = include(Yii::getAlias("@console/config/role.php"));

        $this->permissions = include(Yii::getAlias("@console/config/permission.php"));

        $this->menus = include(Yii::getAlias("@console/config/menu.php"));

        if (!isset(Yii::$app->i18n->translations['rbac-admin'])) {
            Yii::$app->i18n->translations['rbac-admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@mdm/admin/messages',
            ];
        }
        return parent::beforeAction($action);
    }

    /**
     * @var array kcshop所有角色
     */
    public $role;

    /**
     * @var array kcshop所有权限
     */
    public $permissions;

    /**
     * @var array kcshop所有菜单
     */
    public $menus;

    /**
     * 初始化所系统
     */
    public function actionIndex($password = NULL)
    {
        $this->actionDb();
        $this->actionAdmin($password);
    }

    public function actionAdmin($password = NULL)
    {
        $this->actionPermission();
        $this->actionRole();
        $this->actionMenu();
        $this->actionCreateAdmin($password);
    }

    public function actionTest()
    {
        $menu = MenuHelper::getAssignedMenu(2, NULL, NULL, 1);
        print_r($menu);
    }

    public function actionCreateAdmin($password = NULL)
    {
        $auth = Yii::$app->authManager;
        while ($password == NULL) {
            $password = $this->prompt("\n\n请输入admin用户密码（最少6位任意字符）:");

            if (strlen($password) < 6) {
                $password = NULL;
                continue;
            }
            break;
        }

        $this->createAdmin($password);
    }

    public function actionMenu()
    {
        yii::$app->db->createCommand()->truncateTable("menu")->execute();

        $order = 1;
        foreach ($this->menus as $parent => $children) {
            $pMenu = new Menu();
            $pMenu->name = $parent;
            $pMenu->data = $children['data'];
            $pMenu->order = $order++;
            $ret = $pMenu->save();

            if (!$ret) {
                print_r($pMenu->getErrors());
                exit;
            }

            foreach ($children['children'] as $item) {
                $cMenu = new Menu();
                $cMenu->name = $item[0];
                $cMenu->route = $item[1];
                $cMenu->data = $item[2] ?? '';
                $cMenu->parent = $pMenu->id;
                $cMenu->order = $order++;
                $ret = $cMenu->save();
                if (!$ret) {
                    print_r($cMenu->getErrors());
                    exit;
                }
            }
            echo 'update menu success' . date('Y-m-d H:i:s', time()) . "\n";
        }
    }

    /**
     * 初始化权限
     */
    public function actionRole()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->role as $role => $permissions) {
            if ($auth->getRole($role)) {
                $oRole = $auth->getRole($role);
            } else {
                $oRole = $auth->createRole($role);
                $oRole->description = "所有权限";
                $oRole->ruleName = NULL;
                $oRole->data = NULL;
                if ($auth->add($oRole)) {
                    $this->stdout("Add role {$role} success!\n", Console::FG_GREEN);
                }
            }
            foreach ($permissions as $peimission) {
                $oPeimission = $auth->getPermission($peimission);
                if (!$oPeimission) { //如果没有则跳过
                    continue;
                }
                $ownPermission = $auth->getChildren($role);
                if (in_array($oPeimission, $ownPermission)) {
                    continue;
                };
                $auth->addChild($oRole, $oPeimission);
                $this->stdout("Add child {$peimission} to {$role}\n", Console::FG_GREEN);
            }
        }
    }

    /**
     * 初始化路由
     */
    public function actionPermission()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0;")->execute();
//        yii::$app->db->createCommand()->truncateTable("auth_item")->execute();
//        yii::$app->db->createCommand()->truncateTable("auth_item_child")->execute();
        $auth = Yii::$app->authManager;

        foreach ($this->permissions as $permission => $routes) {
            if ($auth->getPermission($permission)) {
                $oPermiss = $auth->getPermission($permission);
            } else {
                $oPermiss = $this->createPermission($permission);
            }
            foreach ($routes as $description => $route) {
                if ($auth->getPermission($route, $description)) {
                    continue;
                }
                $oRoute = $this->createPermission($route, $description);
                $auth->addChild($oPermiss, $oRoute);

                $this->stdout("Add child {$route} to {$permission}\n", Console::FG_GREEN);
            }
        }
    }

    private function createPermission($permission, $description = '')
    {
        $auth = Yii::$app->authManager;
        $oPermiss = $auth->createPermission($permission);
        $oPermiss->description = $description ?: $permission;
        $auth->add($oPermiss);
        $this->stdout("Add permission {$permission}\n", Console::FG_GREEN);
        return $oPermiss;
    }

    /**
     * 初始化数据库结构
     */
    public function actionDb()
    {
        $this->clearDb();
        $this->migrate();
        $this->stdout("Create DB structure success\n", Console::FG_GREEN);
    }

    /**
     * 数据迁移
     * @param sring $migrationPath 数据迁移代码目录
     */
    private function migrate($migrationPath = "@app/migrations")
    {
        $migrate = Yii::createObject("yii\console\controllers\MigrateController", ["action", $this]);
        $migrate->interactive = false;
        $migrate->migrationPath = $migrationPath;
        $migrate->runAction("up", []);
    }

    /**
     * TODO没有完善
     * 清空数据库
     */
    public function clearDb()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0;")->execute();
        $dbname = explode('=', explode(';', Yii::$app->db->dsn)[1])[1];
        $sql = "SELECT CONCAT('drop table ',table_name,';') FROM information_schema.`TABLES` WHERE table_schema='{$dbname}';";
        $sqls = Yii::$app->db->createCommand($sql)->queryColumn();
        foreach ($sqls as $dropSql) {
            Yii::$app->db->createCommand($dropSql)->execute();
        }
    }

    /**
     * 创建管理员
     * @param string $password 管理员密码
     */
    private function createAdmin($password)
    {
        $auth = Yii::$app->authManager;

        $model = AuthUser::findOne("username = 'admin");

        if (empty($model)) {
            $model = new AuthUser();
        }

        $model->username = "admin";
        $model->email = "admin@kcshop.store";
        $model->setPassword($password);
        $model->generateAuthKey();
        $model->status = AuthUser::STATUS_ACTIVE;
        $model->created_at = $model->updated_at = time();
        if ($model->save()) {
            $oRole = $auth->getRole("超级管理员");
            if ($auth->assign($oRole, $model->id)) {
                $this->stdout("Create admin success!\n", Console::FG_GREEN);
            }
        }
    }

}
