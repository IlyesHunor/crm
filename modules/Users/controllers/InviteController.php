<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\InvitationsModel;
use app\modules\Users\models\UsersModel;
use app\modules\Users\models\UserTypesModel;
use DateTime;
use Yii;
use yii\helpers\BaseUrl;

/**
 * Invite controller for the `users` module
 */
class InviteController extends FrontSideController
{
    private $code;
    private $user;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( ! $this->Check_permission() )
        {
            return;
        }

        $this->Load_user_types();
        $this->Handle_post();

        return $this->Render_view( "/invite/index" );
    }

    private function Check_permission()
    {
        if( ! PermissionHelper::Is_admin() )
        {
            return false;
        }

        return true;
    }

    private function Load_user_types()
    {
        $this->data["user_types"] = UserTypesModel::Get_list();
    }

    private function Handle_post()
    {
        if( empty( $_POST ) )
        {
            return;
        }

        if( ! $this->Check_permission() || ! $this->Validate() )
        {
            return;
        }

        $this->Try_to_save_user_and_invitation();
    }

    private function Validate()
    {
        $validation         = new UsersModel();
        $validation->email  = PostHelper::Get( "email" );

        $validation->validate( "email" );

        if( $validation->hasErrors() )
        {
            $this->Set_error_message( $validation->getErrorSummary( false ) );

            return false;
        }

        return $this->Validate_user_type();
    }

    private function Validate_user_type()
    {
        $user_type_id = PostHelper::Get_integer( "user_type_id" );

        if( empty( $user_type_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "User_not_found" ) );

            return false;
        }

        $user_type = UserTypesModel::Get_by_item_id( $user_type_id );

        if( empty( $user_type ) )
        {
            $this->Set_error_message( Yii::t( "app", "User_not_found" ) );

            return false;
        }

        return true;
    }

    private function Try_to_save_user_and_invitation()
    {
        $this->Save_user();
        $this->Generate_code();
        $this->Save_invitation();
        $this->Send_mail( $this->user->email );
    }

    private function Save_user()
    {
        $data = array(
            "added_user_id" => UserHelper::Get_user_id(),
            "user_type_id"  => PostHelper::Get_integer( "user_type_id" ),
            "email"         => PostHelper::Get( "email" ),
            "password"      => "",
            "first_name"    => "",
            "last_name"     => "",
            "insert_date"   => DateHelper::Get_datetime(),
            "is_enabled"    => "0"
        );

        $model              = new UsersModel();
        $model->attributes  = $data;

        $model->save( false );

        $this->user = $model;
    }

    private function Generate_code()
    {
        if( empty( $this->user ) )
        {
            return;
        }

        $date   = new DateTime( $this->user->insert_date );
        $time   = $date->getTimestamp();
        $number = $time * $this->user->id;

        $this->code = md5( $number );
    }

    private function Save_invitation()
    {
        $data = array(
            "added_user_id"     => UserHelper::Get_user_id(),
            "user_id"           => $this->user->id,
            "code"              => $this->code,
            "expiration_date"   => date( "Y-m-d H:i:s", strtotime( "+2 days", strtotime( DateHelper::Get_datetime() ) ) ),
            "insert_date"       => DateHelper::Get_datetime(),
        );

        $model              = new InvitationsModel();
        $model->attributes  = $data;

        $model->save( false );
    }

    private function Send_mail( $to )
    {
        $url = BaseUrl::base( true ) . "/users/register/?code=" . $this->code;

        Yii::$app->mailer->compose( "layouts/invitation", array( "url" => $url ) )
            ->setFrom( $this->site_mail )
            ->setTo( $to )
            ->setSubject( Yii::t( "app", "Invitation" ) )
            ->send();
    }
}
