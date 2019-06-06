<?php
namespace app\modules\Theses\helpers;

use app\helpers\PermissionHelper;
use app\modules\Theses\models\PracticeSubscribersModel;
use app\modules\Theses\models\ThesisSubscribersModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\helpers\Url;

class ThesisHelper
{
    public static function Get_url()
    {
        return Url::toRoute( ["/theses"] );
    }
    public static function Get_view_url( $thesis )
    {
        if( empty( $thesis ) )
        {
            return false;
        }

        return Url::toRoute( ["/theses/view?thesis_id=$thesis->id"] );
    }

    public static function Get_edit_url( $thesis )
    {
        if( ! empty( $thesis ) )
        {
            return Url::toRoute( ["/theses/item?thesis_id=$thesis->id"] );
        }

        return Url::toRoute( ["/theses/item"] );
    }

    public static function Get_delete_url( $thesis )
    {
        if( empty( $thesis ) )
        {
            return false;
        }

        return Url::toRoute( ["/theses/item/delete?thesis_id=$thesis->id"] );
    }

    public static function Get_file_delete_url( $thesis )
    {
        if( empty( $thesis ) )
        {
            return false;
        }

        return Url::toRoute( ["/theses/item/delete_file/?thesis_id=$thesis->id"] );
    }

    public static function Get_my_thesis_url()
    {
        return Url::toRoute( ["/theses/view/my_thesis"] );
    }

    public static function Get_subrscribe_url( $thesis )
    {
        if( empty( $thesis ) )
        {
            return;
        }

        return Url::toRoute( ["/theses/subscribe?thesis_id=$thesis->id"] );
    }

    public static function Get_unsubrscribe_url( $thesis )
    {
        if( empty( $thesis ) )
        {
            return;
        }

        return Url::toRoute( ["/theses/unsubscribe?thesis_id=$thesis->id"] );
    }

    public static function Is_subscribed( $thesis )
    {
        $user_id = UserHelper::Get_user_id();

        if( empty( $user_id ) || empty( $thesis ) )
        {
            return false;
        }

        $subscription = ThesisSubscribersModel::Get_by_thesis_id_and_user_id(
            $thesis->id,
            UserHelper::Get_user_id()
        );

        if( empty( $subscription ) )
        {
            return false;
        }

        return true;
    }

    public static function Is_subscription_used( $thesis )
    {
        if( empty( $thesis ) )
        {
            return false;
        }

        if( empty( $thesis->student_id ) )
        {
            return true;
        }

        return false;
    }
}