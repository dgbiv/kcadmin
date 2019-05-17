<?php

namespace backend\controllers;

use backend\models\Role;
use common\models\ars\AdminUser;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * AdminuserController implements the CRUD actions for AdminUser model.
 */
class RoleController extends CommonController
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
     * @inheritdoc
     */
    public function labels()
    {
        return [
            'Item' => 'Role',
            'Items' => 'Roles',
        ];
    }

    /**
     * Lists all AdminUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Role(['type' => 1]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AdminUser model.
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
     * @return string|\yii\web\Response
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        $model = new Role();
        $data = $model->getAllPermission();
        if (Yii::$app->request->isPost) {
            $res = $model->createOrUpdateRole();
            if ($res['status']) {
                return $this->redirect('index');
            } else {
                throw new ServerErrorHttpException($res['info']);
            }
        }
        return $this->render('create',
            ['data' => $data,
                'model' => $model
            ]);
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws ServerErrorHttpException
     */
    public function actionUpdate()
    {
        $model = new Role();
        $model->name = Yii::$app->request->get('id');
        $data = $model->getAllPermission();
        $checkedData = $model->getCheckedPermission($model->name);
        if (Yii::$app->request->post('permission')) {
            $res = $model->createOrUpdateRole($model->name);
            if ($res['status']) {
                return $this->redirect('index');
            } else {
                throw new ServerErrorHttpException($res['info']);
            }
        }
        return $this->render('update',
            ['data' => $data,
                'model' => $model,
                'checkedData' => $checkedData
            ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     *
     */
    public function actionDelete($id)
    {
        $tra = Yii::$app->db->beginTransaction();
        try {
            $auth = Yii::$app->authManager;
            $oRole = $auth->getRole($id);
//            $userIds = $auth->getUserIdsByRole($id);
//            $users = AdminUser::find()
//                ->where(['in', 'id', $userIds])
//                ->all();
//            foreach ($users as $user) {
//                $user->status = AdminUser::STATUS_DELETED;
//                $user->save();
//            }
            if ($auth->remove($oRole)) {
                $tra->commit();
                return $this->redirect('index');
            }
        } catch (\Exception $e) {
            $tra->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

}
