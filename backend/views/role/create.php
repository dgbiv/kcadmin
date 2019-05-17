<?php


$this->title = '新增角色';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
    'data'=>$data,
    'checkedData' => [],
]) ?>
