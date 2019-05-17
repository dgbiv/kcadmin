<?php

namespace backend\controllers;

use backend\models\OperationLog;
use backend\models\OperationLogSearch;
use Yii;
use yii\web\Controller;
/**
 * Created by PhpStorm.
 * User: iron
 * Date: 19-1-16
 * Time: 下午5:02
 */
class OperationLogController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new OperationLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        $model = OperationLog::findOne($id);
        return $this->render('view', ['model' => $model]);
    }
}