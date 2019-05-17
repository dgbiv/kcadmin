<?php
namespace backend\models;

use yii\base\Model;
/**
 * Signup forms
 */
class ResetPwd extends Model
{
    public $password;
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['password','password_repeat',], 'required'],
            [['password','password_repeat'], 'string', 'min' => 5],

            ['password_repeat','compare','compareAttribute'=>'password','message'=>'两次密码的输入不一致'],
        ];
    }
    public function attributeLabels()
    {
        return [

            'password'=>'新密码',
            'password_repeat'=>'再次输入密码',

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function reset($id)
    {
        if (!$this->validate()) {
            return null;
        }
        $user = AuthUser::findOne($id);
        $user->setPassword($this->password);
        $user->generatePasswordResetToken();
//        $user->save();d($user->errors);exit();
        return $user->save() ? $user : null;
    }
}
