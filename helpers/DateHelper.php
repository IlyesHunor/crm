<?php
namespace app\helpers;

use app\controllers\FrontSideController;
use Yii;

class DateHelper extends FrontSideController
{
    public static function Show_date_in_format( $date,  $format = null )
    {
        if( empty( $date ) )
        {
            return false;
        }

        if( empty( $format ) )
        {
            $format = Yii::$app->controller->default_date_format;
        }

        return date( $format, strtotime( $date ) );
    }

    public static function Get_date( $format = null )
    {
        if( empty( $format ) )
        {
            $format = Yii::$app->controller->default_date_format;
        }

        return date( $format );
    }

    public static function Get_datetime()
    {
        return date( "Y-m-d H:i:s" );
    }
}