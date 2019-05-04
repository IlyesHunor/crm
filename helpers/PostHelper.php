<?php
namespace app\helpers;

use app\controllers\FrontSideController;
use Yii;

class PostHelper extends FrontSideController
{
    public static function Get( $name, $object = null )
    {
        if( ! empty( $_POST ) )
        {
            if( isset( $_POST[$name] ) && ! empty( $_POST[$name] ) )
            {
                return $_POST[$name];
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