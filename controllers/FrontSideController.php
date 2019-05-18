<?php

namespace app\controllers;

use app\helpers\GetHelper;
use app\helpers\PostHelper;
use app\modules\Events\models\EventCategoriesModel;
use app\modules\Events\models\EventsModel;
use app\modules\Practices\models\PracticesModel;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\UsersModel;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class FrontSideController extends Controller
{
    public $data                = array();
    public $default_date_format = "Y-m-d";

    public function Render_view( $view )
    {
        return $this->render( $view, $this->data );
    }

    public function Load_event_categories()
    {
        $this->data["event_categories"] = EventCategoriesModel::Get_list_where_is_enabled();
    }

    protected function Validate_user()
    {
        $user_id = $this->Get_id_from_post_or_get( "user_id" );

        if( empty( $user_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "User_not_found" ) );

            die( "ok" );
        }

        $user = UsersModel::Get_by_item_id( $user_id );

        if( empty( $user ) )
        {
            $this->Set_error_message( Yii::t( "app", "User_not_found" ) );

            die( "ok2" );
        }

        return true;
    }

    protected function Validate_practice()
    {
        $practice_id = $this->Get_id_from_post_or_get( "practice_id" );

        if( empty( $practice_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "Practice_not_found" ) );
            return false;
        }

        $practice = PracticesModel::Get_by_item_id( $practice_id );

        if( empty( $practice ) )
        {
            $this->Set_error_message( Yii::t( "app", "Practice_not_found" ) );

            return false;
        }

        return true;
    }

    protected function Get_id_from_post_or_get( $item_type )
    {
        $item_id = PostHelper::Get_integer( $item_type );

        if( empty( $item_id ) )
        {
            $item_id = GetHelper::Get_integer( $item_type );
        }

        return ( ! empty( $item_id ) ? $item_id : false );
    }

    protected function Check_is_owner( $object )
    {
        if( empty( $object ) || empty( $object->user_id ) )
        {
            return false;
        }

        return ( $object->user_id != UserHelper::Get_user_id() ? false : true );
    }

    protected function Set_error_message( $message )
    {
        Yii::$app->session->setFlash('error', $message, false );
    }

    protected function Set_success_message( $message )
    {
        Yii::$app->session->setFlash('success', $message, false );
    }

    protected function Show_result_with_json( $result )
    {
        $this->data["result"]       = $result;
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data   = Yii::$app->controller->renderPartial( "//partials/ajax/json", $this->data );

        Yii::$app->end();
    }

    protected function Load_practices( $limit = null )
    {
        $this->data["total_items"]  = PracticesModel::Count_where_is_enabled();
        $this->data["practices"]    = PracticesModel::Get_list_where_is_enabled( $limit );
    }

    protected function Load_events( $limit = null )
    {
        $this->data["total_items"]  = EventsModel::Count_where_is_enabled_and_public();
        $this->data["events"]       = EventsModel::Get_list_where_is_enabled_and_public( $limit );
    }
}
