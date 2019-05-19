<?php

namespace app\modules\Practices\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\ImageUploader;
use app\helpers\PdfUploader;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Practices\models\PracticesModel;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\CompaniesModel;
use app\modules\Users\models\DepartmentsModel;
use app\modules\Users\models\UsersModel;
use app\modules\Users\models\YearsModel;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `practises` module
 */
class ViewController extends FrontSideController
{
    public $practice_id;
    public $practice_details;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_practice_details();

        if( $this->Check_is_owner( $this->practice_details ) )
        {
            $this->Load_subscriptions_for_practice();
        }

        return $this->Render_view( "index" );
    }

    private function Load_practice_details()
    {
        $this->practice_id = GetHelper::Get_integer( "practice_id" );

        if( empty( $this->practice_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "Practice_not_found" ) );
        }

        $practice = PracticesModel::Get_by_item_id( $this->practice_id );

        if( empty( $practice ) )
        {
            $this->Set_error_message( Yii::t( "app", "Practice_not_found" ) );
        }

        $this->practice_details = $this->data["practice_details"] = $practice;
    }

    private function Load_subscriptions_for_practice()
    {
        if( empty( $this->practice_id ) )
        {
            return;
        }

        $subscriptions = PracticeSubscribersModel::Get_list_by_practice_id( $this->practice_id );

        if( empty( $subscriptions ) )
        {
            return;
        }

        foreach( $subscriptions as $index => $subscription )
        {
            if( ! empty( $subscription->is_accepted ) )
            {
                $subscription->practice_assn = PracticesUsersAssnModel::Get_by_practice_id_and_user_id(
                    $subscription->practice_id,
                    $subscription->user_id
                );
            }
        }

        $this->data["subscriptions"] = $subscriptions;
    }

    public function actionMy_practice()
    {
        $this->Load_my_practice();
        $this->Load_user_details();
        $this->Load_student_details();
        $this->Load_company_details();

        $this->Handle_post();

        return $this->Render_view( "/view/my_practice/index" );
    }

    private function Load_my_practice()
    {
        $user_id = UserHelper::Get_user_id();

        if( empty( $user_id ) )
        {
            return;
        }

        $assn = PracticesUsersAssnModel::Get_by_user_id_where_is_enabled( $user_id );

        if( empty( $assn ) )
        {
            return;
        }

        $this->practice_details                 = PracticesModel::Get_by_item_id( $assn->practice_id );
        $this->practice_details->assn_details   = $assn;
        $this->data["practice_details"]         = $this->practice_details;
    }

    private function Load_user_details()
    {
        $user_id = UserHelper::Get_user_id();

        if( empty( $user_id ) )
        {
            return;
        }

        $this->data["user_details"] = UsersModel::Get_by_item_id( $user_id );
    }

    private function Load_student_details()
    {
        if( empty( $this->data["user_details"] ) )
        {
            return;
        }

        $user_details = $this->data["user_details"];

        $user_details->year_details = YearsModel::Get_by_item_id( $user_details->year_id );

        if( empty( $user_details->year_details ) )
        {
            return;
        }

        $user_details->department_details = DepartmentsModel::Get_by_item_id(
            $user_details->year_details->department_id
        );

        $this->data["user_details"] = $user_details;
    }

    private function Load_company_details()
    {
        if( empty( $this->practice_details ) )
        {
            return;
        }

        $this->data["company_details"] = CompaniesModel::Get_by_item_id( $this->practice_details->company_id );
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
        if( empty( $_FILES["file"]["name"] ) )
        {
           return true;
        }

        return $this->Validate_report();
    }

    private function Validate_report()
    {
        $model      = new PdfUploader();
        $model->file= UploadedFile::getInstanceByName( 'file' );

        $model->validate();

        if( $model->hasErrors() )
        {
            $errors = $model->getErrorSummary( true );

            $this->Set_error_message( $errors );

            return false;
        }

        $directory_path = FileHelper::createDirectory(
            Yii::getAlias( "@imgPath" )."/practice_user_assn/".$this->practice_details->assn_details->id,
            $mode = 0775,
            $recursive = true
        );

        if( empty( $directory_path ) )
        {
            $this->Set_error_message( Yii::t( "app", "Server_error" ) );

            return false;
        }

        $file_path = Yii::getAlias( "@imgPath" )."/practice_user_assn/".$this->practice_details->assn_details->id.'/'.$_FILES["file"]["name"];

        $model->file->saveAs( $file_path );

        $this->data["file_path"] = "/practice_user_assn/".$this->practice_details->assn_details->id.'/'.$_FILES["file"]["name"];

        return true;
    }

    private function Save()
    {
        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "user_rating"       => PostHelper::Get_integer( "rating" ),
            "modify_date"       => DateHelper::Get_datetime(),
        );

        if( ! empty( $this->data["file_path"] ) )
        {
            $data["report"] = $this->data["file_path"];
        }

        $model = PracticesUsersAssnModel::Get_by_item_id( $this->practice_details->assn_details->id );

        $model->updateAttributes( $data );

        return Yii::$app->controller->redirect( Yii::$app->request->referrer );
    }
}
