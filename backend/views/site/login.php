<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='fa fa-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='fa fa-key form-control-feedback'></span>"
];
?>
<?= $this->render('@app/web/custom/login_style.html') ?>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box__body">
        <div class="login-box__bar">
            登 录
        </div>
        <div class="login-box__left">
            <div class="login-box__logo">
                <img src="/img/biv.png" alt="">
                <p><?= Yii::$app->name?></p>
            </div>
        </div>
        <div class="login-box__right">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('用户名')]) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('密码')]) ?>

            <div class="submit-area">
                <?= $form->field($model, 'rememberMe')->checkbox()->label('记住登录') ?>
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>


            <?php ActiveForm::end(); ?>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
