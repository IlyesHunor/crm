<?php

namespace app\modules\Practices\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\ImageUploader;
use app\helpers\PdfUploader;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Practices\models\ContractTemplatesModel;
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
class ContractTemplatesController extends FrontSideController
{
    private $template_details;
    private $template_id;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_contract_templates();

        return $this->Render_view( "index" );
    }

    private function Load_contract_templates()
    {
        $this->data["templates"] = ContractTemplatesModel::Get_list();
    }

    public function actionItem()
    {
        $this->Load_template_details();
        $this->Handle_post();

        return $this->Render_view( "item" );
    }

    private function Load_template_details()
    {
        $template_id = GetHelper::Get_integer( "item_id" );

        if( empty( $template_id ) )
        {
            return;
        }

        $this->template_id              = $template_id;
        $this->data["template_details"] = $this->template_details = ContractTemplatesModel::Get_by_item_id( $template_id );
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

        $this->Set_success_message( Yii::t( "app", "Template_saved_successfully" ) );
    }

    private function Validate()
    {
        $validation             = new ContractTemplatesModel();
        $validation->attributes = $_POST;

        if( ! $validation->validate( "name" ) || ! $validation->validate( "text" ) )
        {
            $this->Set_error_message( $validation->getErrorSummary( true ) );

            return false;
        }

        return true;
    }

    private function Save()
    {
        $data = array(
            "name"          => PostHelper::Get( "name" ),
            "text"          => PostHelper::Get( "text" ),
            "is_enabled"    => "1",
            "is_deleted"    => "0"
        );

        if( empty( $this->template_details ) )
        {
            $data["added_user_id"]  = UserHelper::Get_user_id();
            $data["insert_date"]    = DateHelper::Get_datetime();

            $model              = new ContractTemplatesModel();
            $model->attributes  = $data;

            $model->save( false );

            return;
        }

        $data["modified_user_id"]   = UserHelper::Get_user_id();
        $data["modify_date"]        = DateHelper::Get_datetime();

        $model = ContractTemplatesModel::Get_by_item_id( $this->template_id );

        $model->updateAttributes( $data );
    }
}
