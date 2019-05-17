<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\AdminUser */

$this->title = '更新管理员信息: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<style>
    .select2-container--krajee .select2-selection--multiple .select2-search--inline .select2-search__field{
        background: transparent!important;
    }
</style>
<div class="adminuser-update">

    <div class="signupForm-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'authority')->dropDownList(\backend\models\SignupForm::roles())->label('分配角色') ?>
        <?php
        echo $form->field($model, 'permissions')->widget(Select2:: classname(), [
            'data' => $data,
            'options' => ['placeholder' => '选择角色外的其他权限...', 'multiple' => true,'background'=>'transparent',
            ],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [',', ''],
                'maximumInputLength' => 10
            ]
        ])->label('其他权限')
        ?>
        <?= $form->field($model, 'introduction')->textarea(['maxlength' => true,'rows'=>6])?>
        <div class="form-group">
            <?= Html::submitButton('更改', ['class' => 'btn btn-success']) ?>
            <?= Html::a('返回',['index'], ['class' => 'btn btn-info']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
