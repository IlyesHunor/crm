<?php
namespace app\modules\Users\helpers;

use app\modules\Users\models\UsersModel;
use Yii;
use yii\helpers\Url;

class UserHelper
{
    public static function Get_user()
    {
        $user = Yii::$app->user->identity;

        if( empty( $user ) )
        {
            return false;
        }

        return $user;
    }

    public static function Get_user_id()
    {
        $user = self::Get_user();

        return ( ! empty( $user ) ? $user->id : false );
    }

    public static function Get_email_address()
    {
        $user = self::Get_user();

        return ( ! empty( $user ) ? $user->email : false );
    }

    public static function Get_user_name()
    {
        $user = self::Get_user();

        return ( ! empty( $user ) ? $user->first_name . " " .$user->last_name : false );
    }

    public static function Get_user_name_by_user( $user )
    {
        if( empty( $user ) )
        {
            return;
        }

        return ( ! empty( $user ) ? $user->first_name . " " .$user->last_name : false );
    }

    public static function Get_user_name_by_id( $user_id )
    {
        $user = UsersModel::Get_by_item_id_for_admin( $user_id );

        return ( ! empty( $user ) ? $user->first_name . " " .$user->last_name : false );
    }

    public static function Is_logged_in()
    {
        $user = Yii::$app->user->identity;

        if( empty( $user ) )
        {
            return false;
        }

        return true;
    }

    public static function Get_head_of_department_user_id()
    {
        $user = UsersModel::Get_head_of_department();

        return ( ! empty( $user ) ? $user->id : false );
    }

    public static function Get_edit_url( $user )
    {
        if( ! empty( $user ) )
        {
            return Url::toRoute( ["/users/item?user_id=$user->id"] );
        }

        return Url::toRoute( ["/theses/item"] );
    }

    public static function Get_delete_url( $user )
    {
        if( empty( $user ) )
        {
            return false;
        }

        return Url::toRoute( ["/users/item/delete?user_id=$user->id"] );
    }
}