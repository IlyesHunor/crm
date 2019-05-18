<?php

namespace app\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\PostHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Users\helpers\UserHelper;

/**
 * Default controller for the `notifications` module
 */
class AjaxController extends FrontSideController
{
    private $notification;

    public function actionSet_notification_read()
    {
        $result = array( "status" => "error" );

        $this->Validate_and_load_notification();

        if( ! empty( $this->notification ) && empty( $this->notification->is_viewed ) )
        {
            $this->Set_notification_as_read();
        }

        $result["status"]   = "success";
        $result["link"]     = ( ! empty( $this->notification ) ? $this->notification->link : "" );

        $this->Show_result_with_json( $result );
    }

    private function Validate_and_load_notification()
    {
        $notification_id = PostHelper::Get_integer( "notification_id" );

        if( empty( $notification_id ) )
        {
            return false;
        }

        $this->notification = NotificationsModel::Get_by_item_id( $notification_id );
    }

    private function Set_notification_as_read()
    {
        $model  = NotificationsModel::Get_by_item_id( $this->notification->id );
        $data   = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "is_viewed"         => "1",
            "modify_date"       => DateHelper::Get_datetime(),
        );

        $model->updateAttributes( $data );
    }
}
