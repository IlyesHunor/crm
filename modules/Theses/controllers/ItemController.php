<?php

namespace app\modules\Theses\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\ImageUploader;
use app\helpers\PdfUploader;
use app\helpers\PostHelper;
use app\modules\Theses\models\ThesesModel;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\CompaniesModel;
use app\modules\Users\models\DepartmentsModel;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `theses` module
 */
class ItemController extends FrontSideController
{
    public $thesis_id;
    public $thesis_details;

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
        $this->thesis_id = GetHelper::Get_integer( "thesis_id" );

        $this->Load_departments();
        $this->Load_companies();
        $this->Validate_and_load_thesis();
        $this->Handle_post();

        return $this->Render_view( 'index' );
    }

    private function Load_departments()
    {
        $this->data["departments"] = DepartmentsModel::Get_list();
    }

    private function Load_companies()
    {
        $this->data["companies"] = CompaniesModel::Get_list();
    }

    private function Validate_and_load_thesis()
    {
        if( empty( $this->thesis_id ) )
        {
            return;
        }

        $this->thesis_details = ThesesModel::Get_by_item_and_user_id(
            $this->thesis_id,
            UserHelper::Get_user_id()
        );

        if( empty( $this->thesis_details ) )
        {
            $this->Set_error_message( "Thesis_not_found" );
        }

        $this->data["thesis_details"] = $this->thesis_details;
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

        $this->Save_thesis();
    }

    private function Validate()
    {
        $validation                 = new ThesesModel();
        $validation->attributes     = $_POST;
        $validation->added_user_id  = UserHelper::Get_user_id();
        $validation->user_id        = UserHelper::Get_user_id();

        $validation->validate();

        if( $validation->hasErrors() )
        {
            $errors = $validation->getErrorSummary( false );

            $this->Set_error_message( $errors );

            return false;
        }

        if( empty( $this->thesis_details ) )
        {
            return true;
        }

        return $this->Validate_and_upload_file();
    }

    private function Validate_and_upload_file()
    {
        if( empty( $_FILES["file"]["name"] ) )
        {
            return true;
        }

        $model          = new PdfUploader();
        $model->file    = UploadedFile::getInstanceByName( 'file' );

        $model->validate();

        if( $model->hasErrors() )
        {
            $errors = $model->getErrorSummary( true );

            $this->Set_error_message( $errors );

            return false;
        }

        $directory_path = FileHelper::createDirectory(
            Yii::getAlias( "@imgPath" )."/theses/".$this->thesis_id,
            $mode = 0775,
            $recursive = true
        );

        if( empty( $directory_path ) )
        {
            $this->Set_error_message( Yii::t( "app", "Server_error" ) );

            return false;
        }

        $file_path = Yii::getAlias( "@imgPath" )."/theses/".$this->thesis_id.'/'.$_FILES["file"]["name"];

        $model->file->saveAs( $file_path );

        $this->data["file_path"] = "/theses/" . $this->thesis_id . '/' . $_FILES["file"]["name"];

        return true;
    }

    private function Save_thesis()
    {
        $data = array(
            "user_id"           => UserHelper::Get_user_id(),
            "department_id"     => PostHelper::Get_integer( "department_id" ),
            "company_id"        => PostHelper::Get_integer( "company_id" ),
            "name"              => PostHelper::Get( "name" ),
            "description"       => PostHelper::Get( "description" ),
            "is_enabled"        => 1,
            "is_deleted"        => 0
        );

        if( ! empty( $this->data["file_path"] ) )
        {
            $data["file"] = $this->data["file_path"];
        }

        if( empty( $this->thesis_id ) )
        {
            $data["added_user_id"]      = UserHelper::Get_user_id();
            $data["insert_date"]        = DateHelper::Get_datetime();
            $thesis_model               = new ThesesModel();
            $thesis_model->attributes   = $data;

            $thesis_model->save( false );

            $new_id = Yii::$app->db->getLastInsertID();

            $this->Set_success_message( Yii::t( "app", "Thesis_saved_successfully" ) );

            return Yii::$app->controller->redirect( "item?thesis_id=". $new_id );
        }

        $thesis_model               = ThesesModel::findOne( $this->thesis_id );
        $data["modified_user_id"]   = UserHelper::Get_user_id();
        $data["modify_date"]        = UserHelper::Get_user_id();

        $thesis_model->updateAttributes( $data );

        $this->Set_success_message( Yii::t( "app", "Thesis_saved_successfully" ) );

        return Yii::$app->controller->redirect( "item?thesis_id=". $this->thesis_id );
    }

    public function actionDelete()
    {
        $this->thesis_id = GetHelper::Get_integer( "thesis_id" );

        $this->Validate_and_load_thesis();
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

        $thesis_model = ThesesModel::findOne( $this->thesis_id );

        $thesis_model->updateAttributes( $data );
    }

    public function actionDelete_file()
    {
        $this->thesis_id = GetHelper::Get_integer( "thesis_id" );

        $this->Validate_and_load_thesis();
        $this->Delete_file();

        return Yii::$app->controller->redirect( Yii::$app->request->referrer );
    }

    private function Delete_file()
    {
        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "file"              => "",
        );

        $thesis_model = ThesesModel::findOne( $this->thesis_id );

        $thesis_model->updateAttributes( $data );
    }
}
