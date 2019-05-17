<?php

namespace backend\models;

use Yii;
use yii\db\Exception;
use yii\web\IdentityInterface;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "AuthUser".
 * @property string $username 用户名
 * @property string $auth_key 密钥
 * @property string $introduction 用户描述
 * @property string $password_reset_token 更改密码token
 * @property string $password_hash 加密后的密码
 * @property string $email 邮箱
 * @property int $role 角色
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class AuthUser extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $authority;
    public $permissions;

    public static function tableName()
    {
        return 'auth_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['username', 'checkoutUsername'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['email', 'default', 'value' => ''],
            [['username', 'auth_key', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'introduction', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],

        ];
    }

    public function checkoutUsername($attribute, $params)
    {
        $auth = AuthUser::findOne(['username' => $this->$attribute, 'status' => self::STATUS_ACTIVE]);
        if ($this->isNewRecord) {
            if ($auth) {
                return $this->addError($attribute, "用户名已存在");
            }
        } else {
            if ($auth && $auth->id != $this->id) {
                return $this->addError($attribute, "用户名已存在");
            }
        }
    }

    /**
     * @return array
     * 获取用户已选中的角色外权限
     */
    public function getSelectedPermission()
    {
        $selected = [];
        $permissions = $this->allPermissionByUserExceptRole();
        foreach ($permissions as $permission => $obj) {
            $selected[] = $permission;
        }
        return $selected;
    }

    /**
     * @return int|string|null
     * 获取选中的角色
     */
    public function getSelectedRole()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRolesByUser($this->id);
        return key($role);
    }

    /**
     * @return array
     * @throws ServerErrorHttpException
     * 更新管理员角色外权限
     */
    public function updateMsgAndPermission()
    {
        $tra = Yii::$app->db->beginTransaction();
        try {
            $auth = Yii::$app->authManager;
            $assignments = $this->allPermissionByUserExceptRole();
            foreach ($assignments as $assignment) {
                $permission = $auth->getPermission($assignment->roleName);
                if (!$auth->revoke($permission, $this->id)) {
                    throw new Exception('初始化权限失败');
                }
            }
            $res = Yii::$app->request->post('AuthUser');
            foreach ($res['permissions'] ?: [] as $route) {
                $permission = $auth->getPermission($route);
                if (!$auth->assign($permission, $this->id)) {
                    throw new Exception('分配权限失败');
                }
            }
            $this->updateRole($res['authority']);
            $tra->commit();
            return ['status' => true];
        } catch (\Exception $e) {
            $tra->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * @param $roleName
     * @return bool
     * @throws Exception
     * 更新管理员角色
     */
    private function updateRole($roleName)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $roles = $auth->getRolesByUser($this->id);
        if (in_array($role, $roles)) {
            return true;
        }
        if (!empty($roles)) {
            $oldRole = $auth->getRole(key($roles));
            if (!$auth->revoke($oldRole, $this->id)) {
                throw new Exception('删除旧角色失败');
            }
        }
        if (!$auth->assign($role, $this->id)) {
            throw new Exception('增加新角色失败');
        }
    }

    /**
     * @return \yii\rbac\Assignment[]
     * 用户所有不包含角色的权限
     */
    private function allPermissionByUserExceptRole()
    {
        $auth = Yii::$app->authManager;
        $allAssignments = $auth->getAssignments($this->id);
        $roles = $auth->getRolesByUser($this->id);
        unset($allAssignments[key($roles)]);
        return $allAssignments;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'email' => '邮箱',
            'introduction' => '简介',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {

                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return static::find()->where(['access_token' => $token, 'status' => self::STATUS_ACTIVE])
            ->andWhere(['>', 'expire_at', time()])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,

        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


}
