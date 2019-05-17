<?php


$this->title = '修改信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
    'data' => $data,
    'checkedData' => $checkedData,
]) ?>
