<?php

namespace frontend\modules\Images\controllers;

use yii\web\Controller;
use Yii;
use frontend\modules\images\models\ImagesTool;

/**
 * Default controller for the `images` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * 生成略缩图 请求
     * $path 原图片完整的路径
     * $newpath 生成新图片的完整路径
     */
    public function actionImages($path, $newpath)
    {

        if (file_exists($path)) {
            $pathinfo = pathinfo($path);
            $imagine = new \Imagine\Imagick\Imagine();
            $dirname = pathinfo($newpath);
            if (!file_exists($dirname['dirname'])) { //判断前台是否有该文件夹，若无则新建文件夹
                mkdir($dirname['dirname'], 0755, true);
            }
            $imagine->open($path)->save($newpath)->show($pathinfo['extension']);
            die;
        }

        if (!file_exists($newpath)) {  //判断前台是否有该图片文件
            $pathinfo = pathinfo($path);
            $data = explode('.', $path);
            $position = explode('x', $data[1]);
            $width = $position[0];
            $height = $position[1];
            $toolModel = new ImagesTool();
            $image = $toolModel->createThumbnail($data[0] . '.' . $pathinfo['extension'], $newpath, $width, $height);
            if ($image['state']) {
                $image['msg']->show($pathinfo['extension']);
            } else {
                //print_r($image['msg']);
            }
        } else {
            //print_r('已存在');
        }


    }


    /**
     * 加水印
     */
    public function actionWater()
    {

        if (Yii::$app->request->isGet) {
            $imgpath = Yii::getAlias('@img');
            $path = $imgpath . '/page.600x600.png';
            $waterfile = $imgpath . '/logo.png';
            $toolModel = new ImagesTool();
            $info = $toolModel->addWater($path, $waterfile, 2);
            if ($info['state']) {
                $info['msg']->show('jpg');
            } else {
                print_r($info['msg']);
            }

        }
    }
}
