<?php
/**
 * Created by PhpStorm.
 * User: cgb
 * Date: 2018/4/2
 * Time: 17:02
 */

namespace frontend\modules\images\models;

use Yii;
use yii\base\Model;
class ImagesTool extends Model
{
    /**
     * @param $oldimagefile string 原图片路径
     * @param $newimagefile string 生成新图片的路径
     * @param $width integer  略缩图的长度
     * @param $height integer  略缩图的宽度
     */
    public function createThumbnail($oldImageFile,$newImageFile,$width,$height)
    {
        $dirname = pathinfo($newImageFile);
        if (!file_exists($dirname['dirname'])) { //判断前台是否有该文件夹，若无则新建文件夹
            mkdir($dirname['dirname'],755,true);
        }
        try {
            $imagine     = new \Imagine\Imagick\Imagine();
            $mode        = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
            $imageData   = $imagine->open($oldImageFile);
            $imageHeight = $imageData->getSize()->getHeight();
            $imageWidth  = $imageData->getSize()->getWidth();

            if ($width > $imageWidth && $height > $imageHeight) {
//              $ratio       = max($width/$imageWidth,$height/$imageHeight);
                $ratio        = $width/$imageWidth;
                $size         = new \Imagine\Image\Box($imageWidth*$ratio, $imageHeight*$ratio);
                $image['msg'] = $imageData->resize($size, \Imagine\Image\ImageInterface::FILTER_LANCZOS)
                                        ->save($newImageFile,['jpeg_quality' => 100]);
            } else{
                $size         = new \Imagine\Image\Box($width, $height);
                $image['msg'] = $imageData->thumbnail($size,$mode)
                                          ->save($newImageFile,['jpeg_quality' => 100]);
            }
            $image['state'] = true;
            return $image;
        } catch (\Imagine\Exception\Exception $e){
            $image['msg']   = $e->getMessage();
            $image['state'] = false;
            return $image;
        }

    }

    /**
     * @param $rootFile string //原图路径
     * @param $waterFile //水印图路径
     * @param int $position 1=lt,2=lb,3=rt,4=rb;l=left,r=right,t=top,b=bottom
     * @param $overRide boolean 是否覆盖原图
     */
    public function addWater($rootFile,$waterFile,$position=4,$overRide=false)
    {
        try {
            $pathinfo = pathinfo($rootFile);
            if (!$overRide) {
                $filename =  basename($rootFile,$pathinfo['extension']);
                $suffix   =  $pathinfo['extension'];
                $param    =  'w.'.$position.'.';
                $water_rootfile = $pathinfo['dirname'].'/'.$filename.$param.$suffix;
            } else {
                $water_rootfile = $rootFile;
            }
            $imagine = new \Imagine\Imagick\Imagine();
            $watermark = $imagine->open($waterFile);
            $image     = $imagine->open($rootFile);
            $size      = $image->getSize();
            $wSize     = $watermark->getSize();
            $position > 2 ? $pwidth=$size->getWidth()-$wSize->getWidth() : $pwidth=0;
            $position%2==0 ? $pheight=$size->getHeight()-$wSize->getHeight() : $pheight=0;
            $point = new \Imagine\Image\Point($pwidth,$pheight);
            $data['msg'] = $image->paste($watermark, $point)->save($water_rootfile);
            $data['state'] = true;
            return $data;
        } catch (\Imagine\Exception\Exception $e) {
            $data['msg'] = $e->getMessage();
            $data['state'] = false;
            return $data;
        }

    }
}