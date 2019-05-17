<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ResetPwd */
/* @var $username  */

$this->title = '重置-'.$username.'-的登录密码: ';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '重置密码';
?>
<div class="adminuser-update">

    <h1></h1>

    <div class="signupForm-form">

        <?php $form = ActiveForm::begin(); ?>



        <?= $form->field($model, 'password')->textInput(['maxlength' => true,'type'=>'password']) ?>

        <?= $form->field($model, 'password_repeat')->textInput(['maxlength' => true,'type'=>'password']) ?>


        <div class="form-group">
            <?= Html::submitButton('重置', ['class' => 'btn btn-success']) ?>
            <?= Html::button('返回', ['class' => 'btn btn-info','onclick'=>"window.history.go(-1)"]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
