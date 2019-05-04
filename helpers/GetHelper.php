<?php
namespace app\helpers;

use app\controllers\FrontSideController;
use Yii;

class GetHelper extends FrontSideController
{
    public static function Get( $name, $object = null )
    {
        if( ! empty( $_GET ) )
        {
            if( isset( $_GET[$name] ) && ! empty( $_GET[$name] ) )
            {
                return $_GET[$name];
            }
        }
        elseif( ! empty( $object ) )
        {
            if( ! empty( $object->$name ) )
            {
                return $object->$name;
            }
        }

        return false;
    }

    public static function Get_integer( $name, $object = null )
    {
        $value = self::Get( $name, $object );

        return intval( $value );
    }
}