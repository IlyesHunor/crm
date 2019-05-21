<?php

namespace app\modules\Theses\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\PermissionHelper;
use app\modules\Practices\models\PracticesModel;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Theses\models\ThesisSubscribersModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\web\Controller;

/**
 * Subscribe controller for the `theses` module
 */
class SubscribeController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( ! $this->Check_if_has_permission() || ! $this->Validate_thesis() || ! $this->Check_if_subscribed() )
        {
            return;
        }

        $this->Subscribe();

        $this->redirect( Yii::$app->request->referrer );
    }

    private function Check_if_has_permission()
    {
        if( ! UserHelper::Is_logged_in() )
        {
            $this->Set_error_message( Yii::t( "app", "Not_logged_in" ) );

            return false;
        }

        if( ! PermissionHelper::Is_student() )
        {
            $this->Set_error_message( Yii::t( "app", "Dont_have_permission" ) );

            return false;
        }

        return true;
    }

    private function Check_if_subscribed()
    {
        $user_id        = UserHelper::Get_user_id();
        $thesis_id      = GetHelper::Get_integer( "thesis_id" );
        $subscription   = ThesisSubscribersModel::Get_by_thesis_id_and_user_id(
            $thesis_id,
            $user_id
        );

        if( empty( $subscription ) )
        {
            return true;
        }

        $this->Set_error_message( Yii::t( "app", "Already_subscribed" ) );

        return false;
    }

    private function Subscribe()
    {
        $data = array(
            "added_user_id" => UserHelper::Get_user_id(),
            "thesis_id"     => GetHelper::Get_integer( "thesis_id" ),
            "user_id"       => UserHelper::Get_user_id(),
            "insert_date"   => DateHelper::Get_datetime(),
            "is_enabled"    => 1,
            "is_deleted"    => 0,
        );

        $model              = new ThesisSubscribersModel();
        $model->attributes  = $data;

        $model->save( false );
    }
}
