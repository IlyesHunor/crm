<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;
use app\helpers\CsvUploader;
use app\helpers\DateHelper;
use app\helpers\ImageUploader;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\InvitationsModel;
use app\modules\Users\models\UsersModel;
use app\modules\Users\models\UserTypesModel;
use DateTime;
use Yii;
use yii\helpers\BaseUrl;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Import controller for the `users` module
 */
class ImportController extends FrontSideController
{
    private $code;
    private $user;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( ! $this->Check_permission() )
        {
            return;
        }

        $this->Handle_post();

        return $this->Render_view( "/import/index" );
    }

    private function Check_permission()
    {
        if( ! PermissionHelper::Is_admin() )
        {
            return false;
        }

        return true;
    }

    private function Handle_post()
    {
        if( empty( $_POST ) )
        {
            return;
        }

        if( ! $this->Validate_and_upload_csv() )
        {
            return;
        }

        $this->Try_to_save_users_and_invitations();
    }

    private function Validate_and_upload_csv()
    {
        if( empty( $_FILES["file"]["name"] ) )
        {
            return true;
        }

        $model          = new CsvUploader();
        $model->file    = UploadedFile::getInstanceByName( 'file' );

        $model->validate();

        if( $model->hasErrors() )
        {
            $errors = $model->getErrorSummary( true );

            $this->Set_error_message( $errors );

            return false;
        }

        $directory_path = FileHelper::createDirectory(
            Yii::getAlias( "@imgPath" )."/users/import",
            $mode = 0775,
            $recursive = true
        );

        if( empty( $directory_path ) )
        {
            $this->Set_error_message( Yii::t( "app", "Server_error" ) );

            return false;
        }

        $csv_path = Yii::getAlias( "@imgPath" )."/users/import/import_" . DateHelper::Get_date( "Y-m-d-H-i-s" ) . ".csv";

        $model->file->saveAs( $csv_path );

        $this->data["file_path"] = $csv_path;

        return true;
    }

    private function Try_to_save_users_and_invitations()
    {
        $this->Load_csv_header();
        $this->Load_data_from_csv();

        if( empty( $this->data["users_data"] ) )
        {
            return;
        }

        foreach( $this->data["users_data"] as $user_data )
        {
            $this->Save_user( $user_data );
            $this->Generate_code();
            $this->Save_invitation();
            $this->Send_mail( $user_data["email"] );
        }
    }

    private function Load_csv_header()
    {
        if( empty( $this->data["file_path"] ) )
        {
            return;
        }

        $row = 0;

        if( ( $handle = fopen( $this->data["file_path"], "r" ) ) !== FALSE )
        {
            while( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE )
            {
                $size = count( $data );

                for( $index = 0; $index < $size; $index++ )
                {
                    if( $row > 0 ) // header
                    {
                        break;
                    }

                    $this->data["header"][$index] = $data[$index];
                }

                $row++;
            }

            fclose( $handle );
        }
    }

    private function Load_data_from_csv()
    {
        if( empty( $this->data["file_path"] ) )
        {
            return;
        }

        $row = 0;

        if( ( $handle = fopen( $this->data["file_path"], "r" ) ) !== FALSE )
        {
            while( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE )
            {
                $size = count( $data );

                for( $index = 0; $index < $size; $index++ )
                {
                    if( $row == 0 ) // header
                    {
                        continue;
                    }

                    $this->data["users_data"][$row][$this->data["header"][$index]] = $data[$index];
                }

                $row++;
            }

            fclose( $handle );
        }
    }

    private function Save_user( $user_data )
    {
        $data = array(
            "added_user_id" => UserHelper::Get_user_id(),
            "user_type_id"  => $user_data["user_type_id"],
            "email"         => $user_data["email"],
            "password"      => "",
            "first_name"    => "",
            "last_name"     => "",
            "insert_date"   => DateHelper::Get_datetime(),
            "is_enabled"    => "0"
        );

        $model              = new UsersModel();
        $model->attributes  = $data;

        $model->save( false );

        $this->user = $model;
    }

    private function Generate_code()
    {
        if( empty( $this->user ) )
        {
            return;
        }

        $date   = new DateTime( $this->user->insert_date );
        $time   = $date->getTimestamp();
        $number = $time * $this->user->id;

        $this->code = md5( $number );
    }

    private function Save_invitation()
    {
        $data = array(
            "added_user_id"     => UserHelper::Get_user_id(),
            "user_id"           => $this->user->id,
            "code"              => $this->code,
            "expiration_date"   => date( "Y-m-d H:i:s", strtotime( "+2 days", strtotime( DateHelper::Get_datetime() ) ) ),
            "insert_date"       => DateHelper::Get_datetime(),
        );

        $model              = new InvitationsModel();
        $model->attributes  = $data;

        $model->save( false );
    }

    private function Send_mail( $to )
    {
        $url = BaseUrl::base( true ) . "/users/register/?code=" . $this->code;

        Yii::$app->mailer->compose( "layouts/invitation", array( "url" => $url ) )
            ->setFrom( $this->site_mail )
            ->setTo( $to )
            ->setSubject( Yii::t( "app", "Invitation" ) )
            ->send();
    }
}
