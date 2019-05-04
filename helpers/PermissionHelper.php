<?php
namespace app\helpers;

use app\controllers\FrontSideController;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\UserTypesModel;
use Yii;

class PermissionHelper extends FrontSideController
{
    public static function Can_modify( $object )
    {
        $user_id = UserHelper::Get_user_id();

        if( empty( $user_id ) )
        {
            return false;
        }

        if( $object->user_id == $user_id )
        {
            return true;
        }

        return false;
    }

    public static function Is_company()
    {
        $user = UserHelper::Get_user();

        if( empty( $user ) )
        {
            return false;
        }

        $user_type = UserTypesModel::Get_by_item_id( $user->user_type_id );

        if( empty( $user_type ) )
        {
            return false;
        }

        if( $user_type->name == "Company Delegate" )
        {
            return true;
        }
    }

    public static function Is_student()
    {
        $user = UserHelper::Get_user();

        if( empty( $user ) )
        {
            return false;
        }

        $user_type = UserTypesModel::Get_by_item_id( $user->user_type_id );

        if( empty( $user_type ) )
        {
            return false;
        }

        if( $user_type->name == "Student" )
        {
            return true;
        }
    }

    public static function Is_coordinator()
    {
        $user = UserHelper::Get_user();

        if( empty( $user ) )
        {
            return false;
        }

        $user_type = UserTypesModel::Get_by_item_id( $user->user_type_id );

        if( empty( $user_type ) )
        {
            return false;
        }

        if( $user_type->name == "Coordinator" )
        {
            return true;
        }
    }

    public static function Is_head_of_department()
    {
        $user = UserHelper::Get_user();

        if( empty( $user ) )
        {
            return false;
        }

        $user_type = UserTypesModel::Get_by_item_id( $user->user_type_id );

        if( empty( $user_type ) )
        {
            return false;
        }

        if( $user_type->name == "Head_of_department" )
        {
            return true;
        }
    }
}