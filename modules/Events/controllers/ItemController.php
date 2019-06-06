<?php

namespace app\modules\Events\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\ImageUploader;
use app\helpers\PostHelper;
use app\modules\Events\models\EventCategoriesModel;
use app\modules\Events\models\EventsModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `events` module
 */
class ItemController extends FrontSideController
{
    private $event_id;
    private $event_details;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->event_id = GetHelper::Get_integer( "event_id" );

        $this->Validate_and_load_event();
        $this->Load_event_categories();
        $this->Handle_post();

        return $this->Render_view( 'index' );
    }

    private function Validate_and_load_event()
    {
        if( empty( $this->event_id ) )
        {
            return;
        }

        $user_id            = UserHelper::Get_user_id();
        $this->event_details= EventsModel::Get_by_item_and_user_id( $this->event_id, $user_id );

        if( empty( $this->event_details ) )
        {
            $this->Set_error_message( Yii::t( "app", "Event_not_found" ) );
        }

        $this->data["event_details"] = $this->event_details;
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

        $this->Save_event();
    }

    private function Validate()
    {
        $validation             = new EventsModel();
        $validation->attributes = $_POST;

        $validation->validate();

        if( $validation->hasErrors() )
        {
            $errors = $validation->getErrorSummary( true );

            $this->Set_error_message( $errors );
        }

        if( ! $this->Validate_event_category() )
        {
            return false;
        }

        return $this->Validate_and_upload_image();
    }

    private function Validate_event_category()
    {
        $category_id    = PostHelper::Get_integer( "event_category_id" );
        $category       = EventCategoriesModel::Get_by_item_id( $category_id );

        if( ! empty( $category ) )
        {
            return true;
        }

        $this->Set_error_message( Yii::t( "app", "Category_not_found" ) );

        return false;
    }

    private function Validate_and_upload_image()
    {
        if( empty( $this->event_details ) )
        {
            return true;
        }

        if( empty( $_FILES["image"]["name"] ) )
        {
            return false;
        }

        $model          = new ImageUploader();
        $model->image   = UploadedFile::getInstanceByName( 'image' );

        $model->validate();

        if( $model->hasErrors() )
        {
            $this->Set_error_message( $model->getErrorSummary( true ) );

            return false;
        }

        $directory_path = FileHelper::createDirectory(
            Yii::getAlias( "@imgPath" )."/events/".$this->event_id,
            $mode = 0775,
            $recursive = true
        );

        if( empty( $directory_path ) )
        {
            $this->Set_error_message( Yii::t( "app", "Server_error" ) );

            return false;
        }

        $image_path = Yii::getAlias( "@imgPath" )."/events/".$this->event_id.'/'.$_FILES["image"]["name"];

        $model->image->saveAs( $image_path );

        $this->data["image_path"] = "/events/" . $this->event_id . '/' . $_FILES["image"]["name"];

        return true;
    }

    private function Save_event()
    {
        $data = array(
            "user_id"           => UserHelper::Get_user_id(),
            "event_category_id" => PostHelper::Get_integer( "event_category_id" ),
            "name"              => PostHelper::Get( "name" ),
            "country"           => PostHelper::Get( "country" ),
            "city"              => PostHelper::Get( "city" ),
            "address"           => PostHelper::Get( "address" ),
            "institution"       => PostHelper::Get( "institution" ),
            "description"       => PostHelper::Get( "description" ),
            "start_date"        => PostHelper::Get( "start_date" ),
            "end_date"          => PostHelper::Get( "end_date" ),
            "is_public"         => PostHelper::Get_integer( "is_public" ),
            "is_enabled"        => 1,
            "is_deleted"        => 0
        );

        if( ! empty( $this->data["image_path"] ) )
        {
            $data["image"] = $this->data["image_path"];
        }

        if( empty( $this->event_id ) )
        {
            $data["added_user_id"]  = UserHelper::Get_user_id();
            $data["insert_date"]    = DateHelper::Get_datetime();
            $event_model            = new EventsModel();
            $event_model->attributes= $data;

            $event_model->save( false );

            $new_id = Yii::$app->db->getLastInsertID();

            return Yii::$app->controller->redirect( "item?event_id=". $new_id );
        }

        $event_model                = EventsModel::findOne( $this->event_id );
        $data["modified_user_id"]   = UserHelper::Get_user_id();
        $data["modify_date"]        = UserHelper::Get_user_id();

        $event_model->updateAttributes( $data );

        return Yii::$app->controller->redirect( "item?event_id=". $this->event_id );
    }

    public function actionDelete()
    {
        $this->event_id = GetHelper::Get_integer( "event_id" );

        $this->Validate_and_load_event();
        $this->Delete();

        return Yii::$app->controller->redirect( Yii::$app->request->referrer );
    }

    private function Delete()
    {
        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_deleted"        => 1,
        );

        $event_model = EventsModel::findOne( $this->event_id );

        $event_model->updateAttributes( $data );
    }

    public function actionDelete_image()
    {
        $this->event_id = GetHelper::Get_integer( "event_id" );

        $this->Validate_and_load_event();
        $this->Delete_image();

        return Yii::$app->controller->redirect( Yii::$app->request->referrer );
    }

    private function Delete_image()
    {
        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "image"             => "",
        );

        $event_model = EventsModel::findOne( $this->event_id );

        $event_model->updateAttributes( $data );
    }
}
