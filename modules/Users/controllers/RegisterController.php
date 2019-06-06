<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
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
 * Register controller for the `users` module
 */
class RegisterController extends FrontSideController
{
    private $code;
    private $invitation;
    private $user;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( $this->Check_code() )
        {
            return;
        }

        $this->Handle_post();

        return $this->Render_view( "/register/index" );
    }

    private function Check_code()
    {
        $this->code = GetHelper::Get( "code" );
        $invitation = InvitationsModel::Get_by_item_code( $this->code );

        if( empty( $invitation ) )
        {
            return false;
        }

        $this->invitation = $invitation;

        return $this->Check_invitation();
    }

    private function Check_invitation()
    {
        if( UserHelper::Is_logged_in() && UserHelper::Get_user_id() != $this->invitation->user_id )
        {
            $this->Set_error_message( Yii::t( "app", "Dont_have_permission" ) );

            return false;
        }

        if( DateHelper::Get_datetime() >  $this->invitation->expiration_date )
        {
            $this->Set_error_message( Yii::t( "app", "Invitation_expired" ) );

            return false;
        }

        $this->Load_user();

        if( ! empty( $this->user ) && ! empty( $this->user->is_enabled ) )
        {
            $this->Set_error_message( Yii::t( "app", "Registration_already_exists" ) );

            return false;
        }
    }

    private function Load_user()
    {
        if( empty( $this->invitation ) )
        {
            return;
        }

        $this->user                 = UsersModel::Get_by_item_id_for_admin( $this->invitation->user_id );
        $this->data["user_details"] = $this->user;
    }

    private function Handle_post()
    {
        if( empty( $_POST ) )
        {
            return;
        }

        if( ! $this->Validate() )
        {
            return;
        }

        $this->Save();
    }

    private function Validate()
    {
        $validation             = new UsersModel();
        $validation->attributes = $_POST;

        if( $validation->validate() )
        {
            $this->Set_error_message( $validation->getErrorSummary( true ) );

            //return false;
        }

        return $this->Validate_password();
    }

    private function Validate_password()
    {
        $password           = PostHelper::Get( "password" );
        $confirm_password   = PostHelper::Get( "confirm_password" );

        if( empty( $password ) || empty( $confirm_password ) )
        {
            $this->Set_error_message( Yii::t( "app", "Password_is_required" ) );

            return false;
        }

        if( $password != $confirm_password )
        {
            $this->Set_error_message( Yii::t( "app", "Password_not_match" ) );

            return false;
        }
    }

    private function Save()
    {
        if( empty( $this->user ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => $this->user->id,
            "password"          => md5( PostHelper::Get( "password" ) ),
            "first_name"        => PostHelper::Get( "first_name" ),
            "last_name"         => PostHelper::Get( "last_name" ),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_enabled"        => "1",
        );

        $model = UsersModel::findOne( $this->user->id );

        $model->updateAttributes( $data );

        return $this->redirect( "users/login" );
    }
}
