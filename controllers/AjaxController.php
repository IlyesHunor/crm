<?php

namespace app\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Practices\models\PracticesModel;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Theses\models\ThesesModel;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\DepartmentsModel;
use app\modules\Users\models\UsersModel;
use Yii;
use Dompdf;
use yii\helpers\FileHelper;

require_once Yii::$app->basePath . "/dompdf/lib/html5lib/Parser.php";
require_once Yii::$app->basePath . "/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php";
require_once Yii::$app->basePath . "/dompdf/lib/php-svg-lib/src/autoload.php";
require_once Yii::$app->basePath . "/dompdf/src/Autoloader.php";

Dompdf\Autoloader::register();

/**
 * Default controller for the `notifications` module
 */
class AjaxController extends FrontSideController
{
    private $notification;
    private $practice_assn_id;
    private $thesis;

    public function actionSet_notification_read()
    {
        $result = array( "status" => "error" );

        $this->Validate_and_load_notification();

        if( ! empty( $this->notification ) && empty( $this->notification->is_viewed ) )
        {
            $this->Set_notification_as_read();
        }

        $result["status"]   = "success";
        $result["link"]     = ( ! empty( $this->notification ) ? $this->notification->link : "" );

        $this->Show_result_with_json( $result );
    }

    private function Validate_and_load_notification()
    {
        $notification_id = PostHelper::Get_integer( "notification_id" );

        if( empty( $notification_id ) )
        {
            return false;
        }

        $this->notification = NotificationsModel::Get_by_item_id( $notification_id );
    }

    private function Set_notification_as_read()
    {
        $model  = NotificationsModel::Get_by_item_id( $this->notification->id );
        $data   = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "is_viewed"         => "1",
            "modify_date"       => DateHelper::Get_datetime(),
        );

        $model->updateAttributes( $data );
    }

    public function actionGenerate_pdf()
    {
        $result = array( "status" => "error" );

        $this->Validate_practice_assn_ajax( $result );

        $pdf = $this->Generate_pdf();

        $this->Save_pdf( $pdf, $result );

        $result["status"] = "success";

        $this->Show_result_with_json( $result );
    }

    private function Validate_practice_assn_ajax( & $result )
    {
        $practice_id = PostHelper::Get_integer( "practice_assn_id" );

        if( empty( $practice_id ) )
        {
            $result["message"] = Yii::t( "app", "Practice_not_found" );

            $this->Show_result_with_json( $result );
        }

        $practice = PracticesUsersAssnModel::Get_by_item_id( $practice_id );

        if( empty( $practice ) )
        {
            $result["message"] = Yii::t( "app", "Practice_not_found" );

            $this->Show_result_with_json( $result );
        }

        $this->data["practice_assn"]    = $practice;
        $this->practice_assn_id         = $practice->id;
    }

    private function Generate_pdf()
    {
        if( empty( $this->data["practice_assn"] ) )
        {
            return false;
        }

        $pdf    = new Dompdf\Dompdf();
        $html   = $this->Get_html_for_contract();
        $options= new Dompdf\Options();

        $options->setIsRemoteEnabled( true );
        $pdf->setOptions( $options );
        $pdf->loadHtml( $html );
        $pdf->setPaper( "A4", "portrait" );
        $pdf->render();
        //$pdf->stream();

        return $pdf->output();
    }

    private function Save_pdf( $pdf, & $result )
    {
        if( empty( $pdf ) || empty( $this->practice_assn_id ) )
        {
            return;
        }

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

        $pdf_path = Yii::getAlias( "@imgPath" )."/practice_user_assn/".$this->practice_assn_id.'/contract.pdf';

        file_put_contents( $pdf_path, $pdf );

        $result["download_url"] = Yii::getAlias( "@imgUrl" ) . $path ."contract.pdf";
    }

    private function Get_html_for_contract()
    {
        $html = $this->data["practice_assn"]->contract;

        $html .= Yii::$app->controller->renderPartial(
            "/partials/signing_html",
            array( "data" => $this->data["practice_assn"] )
        );

        return $html;
    }

    public function actionSave_mark()
    {
        $result = array( "status" => "error" );

        $this->Check_if_has_rights_to_modify( $result );
        $this->Validate_practice_assn_ajax( $result );
        $this->Validate_mark( $result );
        $this->Save_mark();

        $result["status"] = "success";

        $this->Show_result_with_json( $result );
    }

    private function Check_if_has_rights_to_modify( & $result )
    {
        $department_id  = PostHelper::Get_integer( "department_id" );
        $department     = DepartmentsModel::Get_by_item_id( $department_id );

        if( empty( $department ) )
        {
            $result["message"] = Yii::t( "app", "Department_not_found" );

            $this->Show_result_with_json( $result );
        }

        if( $department[0]->coordinator_user_id != UserHelper::Get_user_id() )
        {
            $result["message"] = Yii::t( "app", "You_dont_have_rights_to_modify" );

            $this->Show_result_with_json( $result );
        }
    }

    private function Validate_mark( & $result )
    {
        $mark = PostHelper::Get_integer( "mark" );

        if( empty( $mark ) )
        {
            $result["message"] = Yii::t( "app", "Mark_cant_be_empty" );

            $this->Show_result_with_json( $result );
        }

        if( $mark < 1 || $mark > 10 )
        {
            $result["message"] = Yii::t( "app", "Invalid_mark" );

            $this->Show_result_with_json( $result );
        }
    }

    private function Save_mark()
    {
        $practice_assn_id   = PostHelper::Get_integer( "practice_assn_id" );
        $model              = PracticesUsersAssnModel::Get_by_item_id( $practice_assn_id );

        if( empty( $model ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "mark"              => PostHelper::Get_integer( "mark" ),
            "modify_date"       => DateHelper::Get_datetime()
        );

        $model->updateAttributes( $data );
    }

    public function actionAdd_student_to_thesis()
    {
        $result = array( "status" => "error" );

        if( ! PermissionHelper::Is_head_of_department() )
        {
            $result["message"] = Yii::t( "app", "Dont_have_permission" );

            $this->Show_result_with_json( $result );
        }

        $this->Validate_and_load_thesis( $result );
        $this->Validate_student( $result );
        $this->Add_student_to_thesis();

        $result["status"] = "success";

        $this->Show_result_with_json( $result );
    }

    private function Validate_and_load_thesis( $result )
    {
        $thesis_id = PostHelper::Get_integer( "thesis_id" );

        if( empty( $thesis_id ) )
        {
            $result["message"] = Yii::t( "app", "Thesis_not_found" );

            $this->Show_result_with_json( $result );
        }

        $thesis = ThesesModel::Get_by_item_id( $thesis_id );

        if( empty( $thesis ) )
        {
            $result["message"] = Yii::t( "app", "Thesis_not_found" );

            $this->Show_result_with_json( $result );
        }

        $this->thesis = $thesis;
    }

    private function Validate_student( $result )
    {
        $student_id = PostHelper::Get_integer( "student_id" );

        if( empty( $student_id ) )
        {
            $result["message"] = Yii::t( "app", "Student_not_found" );

            $this->Show_result_with_json( $result );
        }

        $student = UsersModel::Get_by_item_id( $student_id );

        if( empty( $student ) )
        {
            $result["message"] = Yii::t( "app", "Student_not_found" );

            $this->Show_result_with_json( $result );
        }
    }

    private function Add_student_to_thesis()
    {
        if( empty( $this->thesis ) )
        {
            return;
        }

        $model = ThesesModel::Get_by_item_id( $this->thesis->id );

        if( empty( $model ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "student_id"        => PostHelper::Get_integer( "student_id" ),
            "modify_date"       => DateHelper::Get_datetime()
        );

        $model->updateAttributes( $data );
    }

    public function actionChange_user_status()
    {
        $result = array( "status" => "error" );


        $this->Validate_and_load_user( $result );
        $this->Change_user_status();

        $result["status"] = "success";

        $this->Show_result_with_json( $result );
    }

    private function Validate_and_load_user( $result )
    {
        $user_id = PostHelper::Get_integer( "user_id" );

        if( empty( $user_id ) )
        {
            $result["message"] = Yii::t( "app", "User_not_found" );

            $this->Show_result_with_json( $result );
        }

        $user = UsersModel::Get_by_item_id_for_admin( $user_id );

        if( empty( $user ) )
        {
            $result["message"] = Yii::t( "app", "User_not_found" );

            $this->Show_result_with_json( $result );
        }

        $this->data["user"] = $user;
    }

    private function Change_user_status()
    {
        if( empty( $this->data["user"] ) )
        {
            return;
        }

        $model = UsersModel::Get_by_item_id_for_admin( $this->data["user"]->id );

        if( empty( $model ) )
        {
            return;
        }

        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_enabled"        => ! intval( $this->data["user"]->is_enabled )
        );

        $model->updateAttributes( $data );
    }
}
