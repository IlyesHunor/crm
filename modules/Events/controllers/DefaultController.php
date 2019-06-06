<?php
namespace app\modules\Events\controllers;
use app\controllers\FrontSideController;
use app\helpers\PermissionHelper;
use app\modules\Events\models\EventsModel;
use app\modules\Users\helpers\UserHelper;
use Yii;

/**
 * Default controller for the `events` module
 */
class DefaultController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_events();

        return $this->Render_view( 'index' );
    }

    protected function Load_events( $limit = null )
    {
        $this->data["total_items"]  = EventsModel::Count_where_is_enabled_and_public();
        $this->data["events"]       = EventsModel::Get_list_where_is_enabled_and_public( $limit );
    }

    public function actionMine()
    {
        $user_id = UserHelper::Get_user_id();

        if( empty( $user_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "Dont_have_permission" ) );

            return;
        }

        if( PermissionHelper::Is_student() )
        {
            $this->Set_error_message( Yii::t( "app", "Dont_have_permission" ) );

            return;
        }

        $this->Load_added_events( $user_id );

        return $this->Render_view('index');
    }

    private function Load_added_events( $user_id )
    {
        $this->data["events"] = EventsModel::Get_list_by_user_id( $user_id );
    }
}
