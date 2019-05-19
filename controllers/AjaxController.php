<?php

namespace app\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\PostHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Users\helpers\UserHelper;
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

        $this->Validate_practice_assn( $result );

        $pdf = $this->Generate_pdf();

        $this->Save_pdf( $pdf, $result );

        $result["status"] = "success";

        $this->Show_result_with_json( $result );
    }

    private function Validate_practice_assn( & $result )
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
            return;
        }

        $pdf    = new Dompdf\Dompdf();
        $html   = $this->data["practice_assn"]->contract;
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
}
