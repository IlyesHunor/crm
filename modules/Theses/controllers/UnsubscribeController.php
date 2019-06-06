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
 * UnsubscribeController controller for the `theses` module
 */
class UnsubscribeController extends FrontSideController
{
    private $supscription;
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

        $this->Unsubscribe();

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
            $this->Set_error_message( Yii::t( "app", "Not_subscribed" ) );

            return false;
        }

        $this->supscription = $subscription;

        return true;
    }

    private function Unsubscribe()
    {
        if( empty( $this->supscription ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_deleted"        => 1,
        );

        $model = ThesisSubscribersModel::findOne( $this->supscription->id );

        $model->updateAttributes( $data );
    }
}
