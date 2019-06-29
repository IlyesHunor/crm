<?php

namespace app\modules\Practices\controllers;

use app\controllers\FrontSideController;
use app\helpers\CompanyHelper;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\ImageUploader;
use app\helpers\PostHelper;
use app\modules\Practices\models\PracticesModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `practices` module
 */
class ItemController extends FrontSideController
{
    public $practice_id;
    public $practice_details;

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
        $this->practice_id = GetHelper::Get_integer( "practice_id" );

        $this->Validate_and_load_practice();
        $this->Handle_post();

        return $this->Render_view( 'index' );
    }

    private function Validate_and_load_practice()
    {
        if( empty( $this->practice_id ) )
        {
            return;
        }

        $this->practice_details = PracticesModel::Get_by_item_and_user_id(
            $this->practice_id,
            UserHelper::Get_user_id()
        );

        if( empty( $this->practice_details ) )
        {
            $this->Set_error_message( "Practice_not_found" );
        }

        $this->data["practice_details"] = $this->practice_details;
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

        $this->Save_practice();
    }

    private function Validate()
    {
        $validation             = new PracticesModel();
        $validation->attributes = $_POST;

        $validation->validate();

        if( $validation->hasErrors() )
        {
            $errors = $validation->getErrorSummary( false );

            $this->Set_error_message( $errors );

            return false;
        }

        if( empty( $this->practice_details ) )
        {
            return true;
        }

        return $this->Validate_and_upload_image();
    }

    private function Validate_and_upload_image()
    {
        if( empty( $_FILES["image"]["name"] ) )
        {
            return true;
        }

        $model          = new ImageUploader();
        $model->image   = UploadedFile::getInstanceByName( 'image' );

        $model->validate();

        if( $model->hasErrors() )
        {
            $errors = $model->getErrorSummary( true );

            $this->Set_error_message( $errors );

            return false;
        }

        $directory_path = FileHelper::createDirectory(
            Yii::getAlias( "@imgPath" )."/practices/".$this->practice_id,
            $mode = 0775,
            $recursive = true
        );

        if( empty( $directory_path ) )
        {
            $this->Set_error_message( Yii::t( "app", "Server_error" ) );

            return false;
        }

        $image_path = Yii::getAlias( "@imgPath" )."/practices/".$this->practice_id.'/'.$_FILES["image"]["name"];

        $model->image->saveAs( $image_path );

        $this->data["image_path"] = "/practices/" . $this->practice_id . '/' . $_FILES["image"]["name"];

        return true;
    }

    private function Save_practice()
    {
        $data = array(
            "user_id"           => UserHelper::Get_user_id(),
            "company_id"        => CompanyHelper::Get_company_id_by_user( UserHelper::Get_user_id() ),
            "name"              => PostHelper::Get( "name" ),
            "country"           => PostHelper::Get( "country" ),
            "city"              => PostHelper::Get( "city" ),
            "address"           => PostHelper::Get( "address" ),
            "description"       => PostHelper::Get( "description" ),
            "start_date"        => PostHelper::Get( "start_date" ),
            "end_date"          => PostHelper::Get( "end_date" ),
            "deadline_date"     => PostHelper::Get( "deadline_date" ),
            "max_participants"  => PostHelper::Get( "max_participants" ),
            "is_enabled"        => 1,
            "is_deleted"        => 0
        );

        if( ! empty( $this->data["image_path"] ) )
        {
            $data["image"] = $this->data["image_path"];
        }

        if( empty( $this->practice_id ) )
        {
            $data["added_user_id"]      = UserHelper::Get_user_id();
            $data["insert_date"]        = DateHelper::Get_datetime();
            $practice_model             = new PracticesModel();
            $practice_model->attributes = $data;

            $practice_model->save( false );

            $new_id = Yii::$app->db->getLastInsertID();

            $this->Set_success_message( Yii::t( "app", "Practice_saved_successfully" ) );

            return Yii::$app->controller->redirect( "item?practice_id=". $new_id );
        }

        $practice_model             = PracticesModel::findOne( $this->practice_id );
        $data["modified_user_id"]   = UserHelper::Get_user_id();
        $data["modify_date"]        = UserHelper::Get_user_id();

        $practice_model->updateAttributes( $data );

        $this->Set_success_message( Yii::t( "app", "Practice_saved_successfully" ) );

        return Yii::$app->controller->redirect( "item?practice_id=". $this->practice_id );
    }

    public function actionDelete()
    {
        $this->practice_id = GetHelper::Get_integer( "practice_id" );

        $this->Validate_and_load_practice();
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

        $practice_model = PracticesModel::findOne( $this->practice_id );

        $practice_model->updateAttributes( $data );
    }

    public function actionDelete_image()
    {
        $this->practice_id = GetHelper::Get_integer( "practice_id" );

        $this->Validate_and_load_practice();
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

        $practice_model = PracticesModel::findOne( $this->practice_id );

        $practice_model->updateAttributes( $data );
    }
}
