<?php

namespace backend\models;

use yii\base\Model;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Signup forms
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $introduction;
    public $stores_id;
    public $authority;
    public $permissions;//角色外的权限

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
//            ['username', 'unique', 'targetClass' => '\common\models\ars\AdminUser', 'message' => '用户名已存在'],
            ['username', 'string', 'min' => 2, 'max' => 255],
//            ['email', 'trim'],
//            ['email', 'required'],
//            ['email', 'email'],
//            ['email', 'string', 'max' => 255],
//            ['email', 'unique', 'targetClass' => '\common\models\ars\AdminUser', 'message' => '邮箱名已存在'],

            [['password', 'password_repeat'], 'required'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            ['introduction', 'string', 'max' => 255],

            ['stores_id', 'integer'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码的输入不一致'],
        ];
    }

    public static function getAllPermissions()
    {
        $data = [];
        $allPermission = include(Yii::getAlias("@console/config/permission.php"));
        foreach ($allPermission as $k => $permissions) {
            if ($k == 'dev' || $k == 'rbac') {
                continue;
            }
            foreach ($permissions as $description => $permission) {
                $data[$permission] = $description;
            }
        }
        return $data;
    }

    public static function roles()
    {
        $roles = \Yii::$app->authManager->getRoles();
        foreach ($roles as $k => $v) {
            $adminList[$k] = $k;
        }
        return $adminList;
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮箱',
            'password' => '登录密码',
            'password_repeat' => '再次输入密码',
            'stores_id' => '门店',
            'introduction' => '简介',
        ];
    }


    public function createAuthUser()
    {
        $auth = Yii::$app->authManager;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = Yii::$app->request->post('SignupForm');
            $oRole = $auth->getRole($res['authority']);
            $user = $this->signup();
            if (is_array($res['permissions'])) {
                foreach ($res['permissions'] as $route) {
                    $permission = $auth->getPermission($route);
                    $auth->assign($permission, $user->id);
                }
            }
            if ($auth->assign($oRole, $user->id)) {
                $transaction->commit();
                return ['status' => true];
            } else {
                throw new \Exception('分配角色出现异常');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * @return AdminUser|null
     * @throws \Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new AuthUser();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->introduction = $this->introduction;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->updated_at = 1;
        $user->created_at = 1;
        if ($user->save()) {
            return $user;
        } else {
            throw new \Exception('admin user save false');
        }

    }
}
