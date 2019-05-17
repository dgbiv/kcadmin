<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;//用了创建表单

/* @var $this yii\web\View */
/* @var $model common\models\AdminUser */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="signupForm-form">

    <?php $form = ActiveForm::begin(); ?><!--创建表单实例并标注表单的开始-->

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'authority')->dropDownList(\backend\models\SignupForm::roles())->label('分配角色') ?>

    <?php
    echo $form->field($model, 'permissions')->widget(Select2:: classname(), [
        'data' => $data,
        'options' => ['placeholder' => '选择角色外的其他权限...', 'multiple' => true],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ''],
            'maximumInputLength' => 10
        ]
    ])->label('其他权限')
    ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'password_repeat')->passwordInput() ?>

    <?= $form->field($model, 'introduction')->textArea(['maxlength' => true, 'rows' => 6]) ?><!--多行文本输入框-->


    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', 'index',['class'=>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
