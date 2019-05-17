<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$this->title = '角色';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="role-index">

    <?php
    $gridColumns = [
        [
            'attribute' => 'name',
            'width' => '15%'
        ],
//        [
//            'attribute' => 'ruleName',
//        ],
        [
            'attribute' => 'description',
        ],
        ['class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{update}  {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    $options = [
                        'title' => Yii::t('yii', '修改'),
                        'aria-label' => Yii::t('yii', '修改'),
                        'data-method' => 'post',
                        'class' => 'btn btn-info',
                        'data-pjax' => '0',
                    ];
                    if ($model->name != '超级管理员') {
                        return Html::a('修改', $url, $options);
                    }
                },
                'delete' => function ($url, $model) {
                    $options = [
                        'title' => Yii::t('yii', '删除'),
                        'aria-label' => Yii::t('yii', '删除'),
                        'data-method' => 'post',
                        'class' => 'btn btn-danger',
                        'data-pjax' => '0',
                        'data' => [
                            'confirm' => '是否删除该角色，删除后将同时清空分配了该角色的管理员角色权限，需要重新为该部分管理员分配权限。该操作不可逆！！',
                            'method' => 'post'
                        ]
                    ];
                    if ($model->name != '超级管理员') {
                        return Html::a('删除', $url, $options);
                    }
                }
            ]
        ],
    ];
    ?>

    <?=
    \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,

        'options' => [
            'style' => 'overflow: auto; word-wrap: break-word;'
        ],
        'toolbar' => [
            ['content' =>
                Html::a('创建新角色', ['create'], ['class' => 'btn btn-primary']) .
//                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax' => 0, 'class' => 'btn btn-success', 'title' => '添加采购单']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']),
            ],
            '{export}',
            '{toggleData}'
        ],
        //'pjax' => true,

        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT
        ]
    ]);
    ?>

</div>
