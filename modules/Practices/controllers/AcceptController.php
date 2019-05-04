<?php

namespace app\modules\Practices\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Practices\models\PracticesModel;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Practices\models\PracticesUsersAssnController;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `practises` module
 */
class AcceptController extends FrontSideController
{
    private $user_id;
    private $practice_id;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( ! $this->Check_if_has_permission() || ! $this->Validate_practice() || ! $this->Validate_user() )
        {
            return;
        }

        $this->practice_id  = GetHelper::Get_integer( "practice_id" );
        $this->user_id      = GetHelper::Get_integer( "user_id" );

        if( ! $this->Check_if_accepted() )
        {
            return;
        }

        $this->Accept();

        $this->redirect( Yii::$app->request->referrer );
    }

    private function Check_if_has_permission()
    {
        if( ! UserHelper::Is_logged_in() )
        {
            $this->Set_error_message( Yii::t( "app", "Not_logged_in" ) );

            return false;
        }

        if( ! PermissionHelper::Is_company() )
        {
            $this->Set_error_message( Yii::t( "app", "Dont_have_permission" ) );

            return false;
        }

        return true;
    }

    private function Check_if_accepted()
    {
        $assn = PracticesUsersAssnModel::Get_by_practice_id_and_user_id(
            $this->practice_id,
            $this->user_id
        );

        if( empty( $assn ) )
        {
            return true;
        }

        $this->Set_error_message( Yii::t( "app", "User_already_accepted" ) );

        return false;
    }

    private function Accept()
    {
        $data = array(
            "added_user_id" => UserHelper::Get_user_id(),
            "practice_id"   => $this->practice_id,
            "user_id"       => $this->user_id,
            "insert_date"   => DateHelper::Get_datetime(),
            "is_enabled"    => 1,
            "is_deleted"    => 0
        );

        $model              = new PracticesUsersAssnModel();
        $model->attributes  = $data;

        $model->save();
        $this->Modify_subscription_state();
    }

    private function Modify_subscription_state()
    {
        $subscription = PracticeSubscribersModel::Get_by_practice_id_and_user_id(
            $this->practice_id,
            $this->user_id
        );

        if( empty( $subscription ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_accepted"       => ! intval( $subscription->is_accepted )
        );

        $subscription->updateAttributes( $data );
    }

    public function actionDelete()
    {
        if( ! $this->Check_if_has_permission() || ! $this->Validate_practice() || ! $this->Validate_user() )
        {
            return;
        }

        $this->practice_id  = GetHelper::Get_integer( "practice_id" );
        $this->user_id      = GetHelper::Get_integer( "user_id" );

        $this->Delete();

        $this->redirect( Yii::$app->request->referrer );
    }

    private function Delete()
    {
        if( empty( $this->practice_id ) || empty( $this->user_id ) )
        {
            return;
        }

        $assn = PracticesUsersAssnModel::Get_by_practice_id_and_user_id( $this->practice_id, $this->user_id );

        if( empty( $assn ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_deleted"        => "1",
        );

        $assn->updateAttributes( $data );

        $this->Modify_subscription_state();
    }
}
