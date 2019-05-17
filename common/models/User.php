<?php

namespace common\models;

use common\models\ars\DailyActiveUser;
use common\models\ars\Order;
use common\models\ars\MemberRank;
use iron\exchange\models\ars\ExchangeLog;
use livan\coupon\behaviors\CouponBehavior;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\caching\FileCache;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $pay_points 用户的消费积分
 * @property integer $tier_points 用户的等级积分
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $normal;
    public $received_quantity;
    public $url;
    public $nextRank;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tel', 'balance', 'commission', 'rankid', 'deposit', 'tier_points'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['name', 'tel'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '微信昵称',
            'name' => '用户名',
//            'sex'=>'性别',
            'tel' => '联系方式',
            'balance' => '余额',
            'rankid' => '会员等级',
            'is_distributor' => '是否为分销商',
            'deposit' => '押金',
            'join_time' => '加入时间',
            'p_id' => '上级分销商',
            'is_verifier' => '是否为核销员',
            'is_deliveryman' => '是否为配送员',
            'pay_points' => '可用积分'
        ];
    }

    public function fields()
    {

        $fields = parent::fields();
        unset($fields['id_card']);
        unset($fields['is_pid']);
        unset($fields['norma']);
        unset($fields['created_at']);
        unset($fields['username']);
//        unset($fields['qrcode']);
        unset($fields['qrcode_con']);
        unset($fields['sex']);
        unset($fields['wx_openid']);
        unset($fields['mini_openid']);
        unset($fields['is_deliveryman']);
        unset($fields['is_verifier']);
        unset($fields['email']);
//        unset($fields['join_time']);
        unset($fields['deposit']);
        unset($fields['commission']);
        unset($fields['role']);
        unset($fields['status']);
        unset($fields['is_check']);
        unset($fields['auth_key']);
        unset($fields['updated_at']);
        unset($fields['expire_at']);
        unset($fields['is_follow']);
        unset($fields['is_distributor']);
//        unset($fields['p_id']);
        unset($fields['unionid']);
        unset($fields['openid']);
        unset($fields['password_hash']);
        unset($fields['updated_at']);
        unset($fields['access_token']);
        unset($fields['password_reset_token']);

        $fields['normal'] = function () {
            if (Order::findOne(['user_id' => $this->id, 'pay_status' => Order::PAY_STATUS_PAID])) {
                return 1;
            } else {
                return 0;
            }
        };
        $fields['received_quantity'] = function () {
            $data = CouponBehavior::getUserCouponData();
            return isset($data['unUsed']) ? count($data['unUsed']) : 0;
        };
        $fields['join_time'] = function ($model) {
            return date("Y-m-d H:i", $model->join_time);
        };
        $fields['nickname'] = function ($model) {
            return base64_decode($model->nickname);
        };
        $fields['rankid'] = function ($model) {
            return MemberRank::findOne($model->rankid);
        };

        $fields['nextRank'] = function ($model) {
            $rank = MemberRank::findOne($model->rankid);
            return $rank ? MemberRank::find()->where(['<', 'discount', $rank->discount])->orderBy('discount DESC')->one() : '';
        };
        return $fields;
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
            $this->updated_at = time();
            Yii::$app->data->userCount();
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }


    public function checkoutRank($tierPoints)
    {
        $ranks = MemberRank::find()->asArray()->where(['is not', 'tier_points', null])->orderBy('discount DESC')->all();
        $data = ArrayHelper::map($ranks, 'discount', 'tier_points');
        foreach ($data as $discount => $points) {

//            if ($this->tier_points >= $points && ((!$this->rankid) || $this->rank->discount > $discount)) {
            if ($tierPoints > $points || $tierPoints == $points) {
                $this->rankid = MemberRank::findOne(['discount' => $discount])->id;
            }
        }
//        $this->save();
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented . ');
        $user = static::find()->where(['access_token' => $token, 'status' => self::STATUS_ACTIVE])->one();
        if (!$user) {
            return false;
        }
        if ($user->expire_at < time()) {
            throw new UnauthorizedHttpException('the access - token expired ', -1);
        } else {
            return $user;
        }
    }

    public static function findByOpenid($openid)
    {
        return static::findOne(['mini_openid' => $openid, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUnionid($unionid)
    {
        return static::findOne(['unionid' => $unionid, 'status' => self::STATUS_ACTIVE]);
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
        $expire = Yii::$app->params['user . passwordResetTokenExpire'];
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

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
        return $this->access_token;
    }

    public function getRank()
    {
        return $this->hasOne(MemberRank::className(), ['id' => 'rankid']);
    }

    //willeny begin

    /**
     * 注册支付密码内容处理
     * @param $name sting 姓名
     * @param $tel int 手机号
     * @param $paymentPassword sting 支付密码
     * @param $smsCode int 验证码
     * @return array
     */
    public static function registeredWritePaymentPassword($name, $tel, $paymentPassword, $smsCode)
    {
        //当前用户
        $userId = Yii::$app->user->id;
        $user = User::findOne($userId);
        $user->name = $name;
        $user->tel = $tel;
        $user->payment_password = md5(md5($paymentPassword));
        $user->updated_at = time();
        $result = $user->save();
        if ($result) {
            $returnData = array(
                'status' => 1,
                'info' => '注册成功！'
            );
            return $returnData;
        } else {
            $returnData = array(
                'status' => -1,
                'info' => '网络异常，请稍后刷新页面后重试！'
            );
            return $returnData;
        }
    }

    /**
     * 重置支付密码内容处理
     * @param $tel int 手机号
     * @param $paymentPassword sting 支付密码
     * @return array
     */
    public static function ResetPaymentPassword($tel, $paymentPassword)
    {
        //当前用户
        $userId = Yii::$app->user->id;
        $user = User::find()->where(['id' => $userId, 'tel' => $tel])->one();
        if (empty($user)) {
            $returnData = array(
                'status' => -1,
                'info' => '没有该用户！'
            );
            return $returnData;
        } else {
            $user->payment_password = md5(md5($paymentPassword));
            $user->updated_at = time();
            $result = $user->save();
            if ($result) {
                $returnData = array(
                    'status' => 1,
                    'info' => '重置支付密码成功！'
                );
                return $returnData;
            } else {
                $returnData = array(
                    'status' => -1,
                    'info' => '网络异常，请稍后刷新页面后重试！'
                );
                return $returnData;
            }
        }
    }

    /**
     * 修改支付密码
     * @param $oldPaymentPassword sting 原密码
     * @param $newPaymentPassword sting 新密码
     * @return array
     */
    public static function ModifyPaymentPassword($oldPaymentPassword, $newPaymentPassword)
    {
        //当前用户
        $userId = Yii::$app->user->id;
        $paymentPassword = md5(md5($oldPaymentPassword));
        $user = User::find()->where(['id' => $userId, 'payment_password' => $paymentPassword])->one();
        if (empty($user)) {
            $returnData = array(
                'status' => -1,
                'info' => '密码不对！'
            );
            return $returnData;
        } else {
            $user->payment_password = md5(md5($newPaymentPassword));
            $user->updated_at = time();
            $result = $user->save();
            if ($result) {
                $returnData = array(
                    'status' => 1,
                    'info' => '修改支付密码成功！'
                );
                return $returnData;
            } else {
                $returnData = array(
                    'status' => -1,
                    'info' => '网络异常，请稍后刷新页面后重试！'
                );
                return $returnData;
            }
        }
    }

    /**
     * 申请会员等级
     * @param $name sting 姓名
     * @param $tel int 手机号
     * @param $idCard string 身份证
     * @param $company sting 公司
     * @return array
     */
    public static function ApplyMemberRank($name, $tel)
    {
        //当前用户
        $userId = Yii::$app->user->id;
        $user = User::findOne($userId);
        $user->name = $name;
        $user->tel = $tel;
        $result = $user->save();
        if ($result) {
            $returnData = array(
                'status' => 1,
                'info' => '申请会员提交成功！'
            );
            return $returnData;
        } else {
            $returnData = array(
                'status' => -1,
                'info' => '网络异常，请稍后刷新页面后重试！'
            );
            return $returnData;
        }
    }

    /**
     * @param $amount
     * @return array
     * 余额充值
     */
    public static function recharge($amount)
    {
        if ($amount < 0.01) {
            return ['status' => false, 'info' => '充值金额至少1分'];
        }
        $order = new Order();
        $order->order_type = Order::ORDER_TYPE_RECHARGE;
        $order->order_status = Order::ORDER_STATUS_CONFIRMED;
        $order->goods_fee = $order->pay_fee = $amount;
        if ($order->save()) {
            return ['status' => true, 'orderId' => $order->id];
        } else {
            return ['status' => false, 'info' => '创建订单失败'];
        }

    }

    /**
     * @return bool
     * 用户信息编辑
     */
    public function infoEdit()
    {
        try {
            $this->nickname = base64_encode(Yii::$app->request->post('nickname'));
            $this->avatar = Yii::$app->request->post('avatar');
            $iv = Yii::$app->request->post('iv');
            $encryptedData = Yii::$app->request->post('encryptedData');
            if ($iv && $encryptedData) {
                $sessionId = $this->session_key;
                $res = Yii::$app->miniprogram->decryptData($sessionId, $iv, $encryptedData);
                $this->unionid = $res['unionId'];
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * 统计用户人活跃度
     */
    public static function setDau()
    {
        $today = date('Y-m-d', time());
        $todayDAU = DailyActiveUser::find()->where(['date' => $today])->one();
        if ($todayDAU) {
            $ids = $todayDAU->user_ids;
            if (!in_array(Yii::$app->user->id, $ids)) {
                array_push($ids, Yii::$app->user->id);
                $todayDAU->user_ids = $ids;
                $todayDAU->count += 1;
            }
        } else {
            $todayDAU = new DailyActiveUser();
            $todayDAU->user_ids = array(Yii::$app->user->id);
            $todayDAU->date = $today;
        }
        return $todayDAU->save() ? true : false;
    }

    /**
     * @return bool
     * 更新用户等级
     */
    public function updateRank()
    {
        try {
            $ranks = MemberRank::find()->where(['is not', 'tier_points', null])->asArray()->orderBy('discount DESC')->all();
            $data = ArrayHelper::map($ranks, 'discount', 'tier_points');
            foreach ($data as $discount => $points) {
//            if ($this->tier_points >= $points && ((!$this->rankid) || $this->rank->discount > $discount)) {
                if ($this->tier_points == $points || $this->tier_points > $points) {
                    $this->rankid = MemberRank::findOne(['discount' => $discount])->id;
                }
            }
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

}
