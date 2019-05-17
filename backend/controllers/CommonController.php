<?php

namespace backend\controllers;

use backend\models\OperationLog;
use yii\web\Controller;
use Yii;

/**
 * Created by PhpStorm.
 * User: iron
 * Date: 19-1-16
 * Time: 上午10:22
 */
class CommonController extends Controller
{
    public function beforeAction($action)
    {
        $log = new OperationLog();
        $log->ip = getenv("HTTP_X_FORWARDED_FOR") ?: Yii::$app->request->userIP;
        $log->admin_agent = Yii::$app->request->userAgent;
        $log->path = Yii::$app->request->pathInfo;
        $log->admin_id = Yii::$app->user->id;
        $log->admin_name = Yii::$app->user->identity->username;
        $log->method = Yii::$app->request->getMethod();
        $params[] = Yii::$app->request->getQueryParams();
        $params[] = Yii::$app->request->getBodyParams();
        $log->params = $params;
        $log->created_at = time();
        $log->save();
        //        d(Yii::$app->request);
        return parent::beforeAction($action);
    }
}