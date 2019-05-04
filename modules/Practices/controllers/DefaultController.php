<?php

namespace app\modules\Practices\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Practices\models\ContractTemplatesModel;
use app\modules\Practices\models\PracticesModel;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Practices\models\PracticesUsersAssnModel;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\DepartmentsModel;
use app\modules\Users\models\UsersModel;
use app\modules\Users\models\YearsModel;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `practises` module
 */
class DefaultController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_practices();

        return $this->Render_view('index');
    }

    private function Load_practices()
    {
        $this->data["total_items"]  = PracticesModel::Count_where_is_enabled();
        $this->data["practices"]    = PracticesModel::Get_list_where_is_enabled();
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

    public function actionAdd_mark()
    {
        if( ! $this->Validate_practice_assn() )
        {
            return;
        }

        return $this->Render_view( "add_mark/index" );
    }

    private function Validate_practice_assn()
    {
        $practice_id = $this->Get_id_from_post_or_get( "practice_id" );

        if( empty( $practice_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "Practice_not_found" ) );
            return false;
        }

        $practice = PracticesUsersAssnModel::Get_by_item_id( $practice_id );

        if( empty( $practice ) )
        {
            $this->Set_error_message( Yii::t( "app", "Practice_not_found" ) );

            return false;
        }

        $this->data["practice_details"] = $practice;

        return true;
    }

    public function actionGenerate_contract()
    {
        if( ! PermissionHelper::Is_head_of_department() )
        {
            return;
        }

        if( ! $this->Validate_practice() )
        {
            return;
        }

        $this->Load_practice_assn_details();
        $this->Load_contract_template();
        $this->Handle_post_generate();

        return $this->Render_view( "generate_contract/index" );
    }

    private function Load_practice_assn_details()
    {
        $practice_id                    = $this->Get_id_from_post_or_get( "practice_id" );
        $this->data["practice_assn"]    = PracticesUsersAssnModel::Get_by_practice_id( $practice_id );
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

        $this->Save_generated_contract();
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

    private function Save_generated_contract()
    {
        $practice_id = $this->Get_id_from_post_or_get( "practice_id" );
        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "contract"          => PostHelper::Get( "text" ),
            "modify_date"       => DateHelper::Get_datetime()
        );

        $model = PracticesUsersAssnModel::Get_by_practice_id( $practice_id );

        if( empty( $model ) )
        {
            return;
        }

        $model->updateAttributes( $data );
    }
}
