<?php
namespace app\modules\Notifications\helpers;

use app\helpers\PermissionHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Practices\helpers\ThesisHelper;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\helpers\Url;

class NotificationHelper
{
    public static function Get_notification_name_for_generated_contract()
    {
        return Yii::t( "app", "Contract" );
    }

    public static function Get_notification_title( $practice_details, $student_details )
    {
        $text   = Yii::t( "app", "Contract_was_generated_title" ) . "<br>";

        if( empty( $student_details ) || empty( $practice_details ) )
        {
            return $text;
        }

        $text   .= " " . Yii::t( "app", "Name_of_student" ) . ": " . UserHelper::Get_user_name_by_user( $student_details );

        return $text;
    }

    public static function Has_unreaded_notifications()
    {
        $number_of_notifications = NotificationsModel::Count_where_is_unreaded_by_user_id( UserHelper::Get_user_id() );

        if( empty( $number_of_notifications ) )
        {
            return false;
        }

        return true;
    }

    public static function Get_notifications_list()
    {
        $notifications = NotificationsModel::Get_notifications_list_by_user_id( UserHelper::Get_user_id() );

        return $notifications;
    }
}