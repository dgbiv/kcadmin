<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\AdviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advice-index">

    <style>
        table {
            table-layout: fixed;
        }

        th:nth-child(3), td:nth-child(3) {
            width: 30% !important;
        }
    </style>

    <?php $gridColumns = [
//            '_id',
//        'admin_id',
        'admin_name',
        'ip',
        [
            'attribute' => 'path',
            'value' => function ($model) {
                $permission = include(Yii::getAlias("@console/config/permission.php"));
                foreach ($permission as $k => $v) {
                    if (in_array('/' . $model->path, $v)) {
                        return $model->path .'('. $k.')';
                    }
                }
                return '首页';
            }
        ],
//        'path',
        ['attribute'=>'method',
            'filter'=>['GET'=>'GET','POST'=>'POST']
        ],
        ['class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '查看'),
                        'aria-label' => Yii::t('yii', '查看'),
                        'data-method' => '',
                        'data-pjax' => 0,
                        'class'=>'btn btn-success fa fa-view'
                    ];
                    return Html::a('', $url, $options);
                },
            ],
        ]
    ]; ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,

        'options' => [
            'style' => 'overflow: auto; word-wrap: break-word;'
        ],
        'toolbar' => [
            ['content' =>
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
