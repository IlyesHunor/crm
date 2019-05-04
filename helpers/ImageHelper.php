<?php
namespace app\helpers;

use app\controllers\FrontSideController;
use Yii;

class ImageHelper extends FrontSideController
{
    public static function Get_image( $object )
    {
        if( empty( $object->image ) )
        {
            return false;
            //todo: default image
        }

        return Yii::getAlias( "@imgUrl" ) . $object->image;
    }
}