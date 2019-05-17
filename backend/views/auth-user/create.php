<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdminUser */

$this->title = '添加新的管理员';
$this->params['breadcrumbs'][] = ['label' => '管理员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-create">

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ]) ?>

</div>
