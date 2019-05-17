<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => '日志列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advice-view">

    <P>
        <?= Html::button('返回', ['class' => 'btn btn-info', 'onclick' => "window.history.go(-1)"]) ?>
    </p>

    <style>
        tbody tr th {
            white-space: nowrap;
        }

        tbody tr td {
            -ms-word-break: break-all;
            word-break: break-all;
        }
    </style>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'admin_id',
            'admin_name',
            'ip',
            'admin_agent',
            'path',
            'method',
//            'params',
            ['label' => 'GET 传参',
                'value' => function ($model) {
                    return json_encode($model->params[0], JSON_UNESCAPED_UNICODE);
                }
            ],
            ['label' => 'POST 传参',
                'value' => function ($model) {
                    return json_encode($model->params[1], JSON_UNESCAPED_UNICODE);
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ]
        ],
    ]) ?>

</div>
