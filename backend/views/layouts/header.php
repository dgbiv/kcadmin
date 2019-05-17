<?php
use yii\helpers\Html;
use common\models\ars\Config;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<style>
    .notifications-menu .dropdown-menu ul.menu li i.pink {
        background: #98455d;
        color: white !important;
    }
</style>
<header class="main-header headroom">
    <a href="#" class="sidebar-toggle fa fa-bars" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <?= Html::a('<span class="logo-mini">' . Yii::$app->name . '</span><span class="logo-lg">' .  Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-notice"></i>

                    </a>

                </li>

                <ul class="nav navbar-nav">


                    <li class="dropdown user user-menu">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="/img/avatar.png" class="user-image" alt="User Image"/>
                            <span class="hidden-xs">管理员<?php echo(' ' . Yii::$app->user->identity->username) ?></span>

                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/img/avatar.png" class="img-circle"
                                     alt="User Image"/>

                                <p>
                                    <?=Yii::$app->user->identity->username.'('.key(Yii::$app->authManager->getAssignments(Yii::$app->user->id)).')' ?>
                                    <small>
                                        获得管理权限时间： <?php echo(date('Y-m-d', Yii::$app->user->identity->created_at)) ?></small>
                                </p>
                            </li>
                            <!--                         Menu Body-->
                            <!--                                                <li class="user-body">-->
                            <!--                                                    <div class="col-xs-4 text-center">-->
                            <!--                                                        <a href="#">Followers</a>-->
                            <!--                                                    </div>-->
                            <!--                                                    <div class="col-xs-4 text-center">-->
                            <!--                                                        <a href="#">Sales</a>-->
                            <!--                                                    </div>-->
                            <!--                                                    <div class="col-xs-4 text-center">-->
                            <!--                                                        <a href="#">Friends</a>-->
                            <!--                                                    </div>-->
                            <!--                                                </li>-->
                            <!--                         Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left" style="float: none!important;">
                                    <?= Html::a('退出登录', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat','style'=>'display:block']) ?>
                                    <!--                                    <a href="#" class="btn btn-default btn-flat">个人信息</a>-->
                                </div>
                                <div class="pull-right">
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <!--                <li>-->
                    <!--                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
                    <!--                </li>-->
                </ul>
        </div>
    </nav>
</header>
