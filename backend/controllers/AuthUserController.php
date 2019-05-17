<?php

namespace backend\controllers;

use Yii;
use backend\models\AuthUser;
use backend\models\AuthUserSearch;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\SignupForm;
use backend\models\ResetPwd;

/**
 * AuthUserController implements the CRUD actions for AuthUser model.
 */
class AuthUserController extends CommonController
{
    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all AuthUser models.
     * @return mixed
     */
    public function actionIndex($tag = 0, $username = null)
    {
        $searchModel = new AuthUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tag' => $tag,
            'username' => $username,
        ]);
    }

    /**
     * Displays a single AuthUser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string
     * @throws \yii\web\ServerErrorHttpException
     *
     */
    public function actionCreate()
    {
        $model = new signupForm();
        $data = SignupForm::getAllPermissions();
        if ($model->load(Yii::$app->request->post())) {
            $res = $model->createAuthUser();
            if ($res['status']) {
                $this->redirect('index');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'data' => $data
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->username == 'admin') {
            throw new ForbiddenHttpException('超级管理员账号不可修改');
        }
        $data = SignupForm::getAllPermissions();
        $model->permissions = $model->getSelectedPermission();
        $model->authority = $model->getSelectedRole();
        if (Yii::$app->request->post('AuthUser')) {
            $res = $model->updateMsgAndPermission();
            if ($res['status']) {
                $this->redirect('index');
            }
        }
        return $this->render('update', [
            'model' => $model,
            'data' => $data
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * 修改密码
     */
    public function actionResetPwd($id)
    {
        $model = new ResetPwd();
        $username = $this->findModel($id)->username;
        if ($model->load(Yii::$app->request->post())) {//load:块赋值 ；把表单中每个对应的属性赋值
            if ($model->reset($id)) {
                return $this->redirect(['index', 'tag' => 1, 'username' => $username]);
            }
        }
        return $this->render('reset_pwd', [
            'model' => $model, 'username' => $username,
        ]);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->username == 'admin') {
            throw new ForbiddenHttpException('超级管理员账号不可删除');
        }
        $model->status = AuthUser::STATUS_DELETED;
        if ($model->save()) {
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the Authuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
