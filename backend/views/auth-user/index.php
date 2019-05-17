<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-user-index">

    <!--  重置密码成功并跳转后显示提示框  -->

    <?php if ($tag) { ?>
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <p style="color: #0D3349"><strong>注意：</strong>管理员<label
                        style="color: white"><?php echo($username) ?></label>的密码已被重置</p>
            <p style="color: #0D3349">请提醒用户用新密码进行登录。</p>
        </div>
    <?php } ?>
    <?php
    $gridColumns = [
//            ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'id',
            'width' => '5%'
        ],
        ['attribute' => 'username',
            'width' => '15%'
        ],
        ['label' => '用户角色',
            'value' => function ($model) {
                $auth = Yii::$app->authManager;
                $roles = $auth->getRolesByUser($model->id);
                return key($roles);
            },
        ],
        ['attribute' => 'created_at',
            'format' => ['date', 'php:Y-m-d H:i:s']
        ],
        ['attribute' => 'updated_at',
            'format' => ['date', 'php:Y-m-d H:i:s']
        ],
        //'role',
        //'status',
        //'created_at',
        //'updated_at',

        ['class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{reset-pwd} {update}  {delete}',
            'buttons' => [
                'reset-pwd' => function ($url, $model) {
                    $options = [
                        'title' => Yii::t('yii', '重置密码'),
                        'aria-label' => Yii::t('yii', '重置密码'),
                        'data-method' => 'post',
                        'class' => 'btn btn-primary',
                        'data-pjax' => '0',
                    ];
                    return Html::a('重置密码', $url, $options);
                },
                'update' => function ($url, $model) {
                    $options = [
                        'title' => Yii::t('yii', '修改'),
                        'aria-label' => Yii::t('yii', '修改'),
                        'data-method' => 'post',
                        'class' => 'btn btn-info',
                        'data-pjax' => '0',
                    ];
                    if ($model->username != 'admin') {
                        return Html::a('修改', $url, $options);
                    }
                },
                'delete' => function ($url, $model) {
                    $options = [
                        'title' => Yii::t('yii', '删除'),
                        'aria-label' => Yii::t('yii', '删除'),
                        'data-method' => 'post',
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => '是否确定删除该管理员，删除后该用户将无法再登录管理后台。该操作不可逆！！',
                            'method' => 'post',
                        ],
                        'data-pjax' => '0',
                    ];
                    if ($model->username != 'admin') {
                        return Html::a('删除', $url, $options);
                    }
                }
            ]
        ],
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
                Html::a('创建新的管理员', ['create'], ['class' => 'btn btn-success']) .
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
