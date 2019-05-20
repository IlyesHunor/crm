<?php

namespace app\modules\Practices\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\ImageUploader;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Notifications\helpers\NotificationHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Practices\helpers\PracticeHelper;
use app\modules\Practices\models\ContractTemplatesModel;
use app\modules\Practices\models\PracticesModel;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Users\helpers\UserHelper;
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
class DefaultController extends FrontSideController
{
    private $practice_assn_id;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_practices();

        return $this->Render_view('index');
    }

    public function actionSubscribed_practices()
    {
        $this->Load_suscribed_practices();

        return $this->Render_view( "/subscribed/index" );
    }

    private function Load_suscribed_practices()
    {
        $this->data["practices"] = PracticeSubscribersModel::Get_list_by_user_id(
            UserHelper::Get_user_id()
        );
    }

    public function actionMine()
    {
        if( ! PermissionHelper::Is_company() )
        {
            return;
        }

        $this->Load_added_practices();

        return $this->Render_view('index');
    }

    private function Load_added_practices()
    {
        $this->data["practices"] = PracticesModel::Get_list_by_user_id( UserHelper::Get_user_id() );
    }

    public function actionStudent_practices()
    {
        $this->Load_departments();
        $this->Load_years();

        return $this->Render_view( "student_practice/index" );
    }

    private function Load_departments()
    {
        if( PermissionHelper::Is_head_of_department() )
        {
            $this->data["departments"] = DepartmentsModel::Get_list();

            return;
        }

        if( PermissionHelper::Is_coordinator() )
        {
            $department = DepartmentsModel::Get_by_coordinator_id( UserHelper::Get_user_id() );

            $this->data["departments"] = DepartmentsModel::Get_by_item_id( $department->id );
        }
    }

    private function Load_years()
    {
        if( empty( $this->data["departments"] ) )
        {
            return;
        }

        $departments = $this->data["departments"];

        foreach( $departments as $department )
        {
            $years = YearsModel::Get_list_by_department_id( $department->id );

            if( empty( $years ) )
            {
                continue;
            }

            foreach( $years as $year )
            {
                $students = UsersModel::Get_list_by_year_id( $year->id );

                if( empty( $students ) )
                {
                    continue;
                }

                foreach( $students as $student )
                {
                    $practice_assn = PracticesUsersAssnModel::Get_by_user_id_where_is_enabled( $student->id );

                    if( empty( $practice_assn ) )
                    {
                        continue;
                    }

                    $practice = PracticesModel::Get_by_item_id( $practice_assn->practice_id );

                    $student->practice_details  = $practice;
                    $student->practice_assn     = $practice_assn;
                }

                $year->students = $students;
            }

            $department->years = $years;
        }
    }

    public function actionGenerate_contract()
    {
        if( ! $this->Validate_practice_assn() )
        {
            return;
        }

        if( ! $this->Check_is_companies_practice() && ! PermissionHelper::Is_head_of_department() )
        {
            return;
        }

        $this->Load_practice_assn_details();
        $this->Load_contract_template();
        $this->Handle_post_generate();

        $template = ( PermissionHelper::Is_head_of_department() ? "index" : "company" );

        return $this->Render_view( "generate_contract/". $template );
    }

    private function Check_is_companies_practice()
    {
        if( empty( $this->data["practice_details"] ) )
        {
            return false;
        }

        $practice = PracticesModel::Get_by_item_id( $this->data["practice_details"]->practice_id );

        if( empty( $practice ) )
        {
            return false;
        }

        if( $practice->user_id != UserHelper::Get_user_id() )
        {
            return false;
        }

        return true;
    }

    private function Load_practice_assn_details()
    {
        $practice_assn_id               = $this->Get_id_from_post_or_get( "practice_id" );
        $this->data["practice_assn"]    = PracticesUsersAssnModel::Get_by_item_id( $practice_assn_id );
        $this->practice_assn_id         = $this->data["practice_assn"]->id;
    }

    private function Load_contract_template()
    {
        $this->data["template_details"] = ContractTemplatesModel::Get_by_item_id( 1 );
    }

    private function Handle_post_generate()
    {
        if( empty( $_POST ) )
        {
            return;
        }

        if( ! $this->Validate_generate() )
        {
            return;
        }

        $this->Save_signing_image();
        $this->Save_generated_contract();
        $this->Try_to_send_notification();

        return Yii::$app->response->redirect( Yii::$app->request->referrer );
    }

    private function Validate_generate()
    {
        $validation             = new ContractTemplatesModel();
        $validation->attributes = $_POST;

        if( ! $validation->validate( "text" ) )
        {
            $this->Set_error_message( $validation->getErrorSummary( true ) );

            return false;
        }

        return true;
    }

    private function Save_signing_image()
    {
        $image          = PostHelper::Get( "signing-image" );
        $signing_type   = PostHelper::Get( "type" );

        if( empty( $image ) )
        {
            return;
        }

        list( $type, $image )   = explode( ";", $image );
        list( , $image )        = explode( ",", $image );
        $image                  = base64_decode( $image );
        $path                   = "/practice_user_assn/".$this->practice_assn_id . "/" ;
        $directory_path         = FileHelper::createDirectory(
            Yii::getAlias( "@imgPath" )."/practice_user_assn/".$this->practice_assn_id,
            $mode = 0775,
            $recursive = true
        );

        if( empty( $directory_path ) )
        {
            $this->Set_error_message( Yii::t( "app", "Server_error" ) );

            return false;
        }

        $image_path = Yii::getAlias( "@imgPath" )."/practice_user_assn/".$this->practice_assn_id.'/'.$signing_type.'.png';

        file_put_contents( $image_path, $image );

        $this->data[$signing_type] = $path . $signing_type . ".png";
    }

    private function Save_generated_contract()
    {
        $practice_id = $this->Get_id_from_post_or_get( "practice_id" );
        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "contract"          => PostHelper::Get( "text" ),
            "modify_date"       => DateHelper::Get_datetime()
        );

        if( ! empty( $this->data["teacher_sign"] ) )
        {
            $data["teacher_sign"] = $this->data["teacher_sign"];
        }

        if( ! empty( $this->data["company_sign"] ) )
        {
            $data["company_sign"] = $this->data["company_sign"];
        }

        $model = PracticesUsersAssnModel::Get_by_item_id( $practice_id );

        if( empty( $model ) )
        {
            return;
        }

        $model->updateAttributes( $data );
    }

    private function Try_to_send_notification()
    {
        if ( empty( $this->data["practice_assn"] ) )
        {
            return;
        }

        $practice_details = PracticesModel::Get_by_item_id( $this->data["practice_assn"]->practice_id );

        if( empty( $practice_details ) )
        {
            return;
        }

        $this->data["practice_details"] = $practice_details;

        $this->Send_notification();
    }

    private function Send_notification()
    {
        if( empty( $this->data["practice_details"] ) || empty( $this->data["practice_assn"] ) )
        {
            return;
        }

        $student_details    = UsersModel::Get_by_item_id( $this->data["practice_assn"]->user_id );
        $user_id            = $this->data["practice_details"]->user_id;

        if( ! PermissionHelper::Is_head_of_department() )
        {
            $user_id = UserHelper::Get_head_of_department_user_id();
        }

        $data = array(
            "added_user_id" => $this->data["practice_details"]->user_id,
            "user_id"       => $user_id,
            "name"          => NotificationHelper::Get_notification_name_for_generated_contract(),
            "title"         => NotificationHelper::Get_notification_title( $this->data["practice_details"], $student_details ),
            "link"          => PracticeHelper::Get_view_url( $this->data["practice_details"] ),
            "insert_date"   => DateHelper::Get_datetime(),
            "is_viewed"     => 0,
            "is_deleted"    => 0,
        );

        $notification               = new NotificationsModel();
        $notification->attributes   = $data;

        $notification->save( false );
    }
}