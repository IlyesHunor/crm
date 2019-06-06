<?php

namespace app\modules\Theses\controllers;

use app\controllers\FrontSideController;
use app\helpers\PermissionHelper;
use app\modules\Theses\models\ThesesModel;
use app\modules\Theses\models\ThesisSubscribersModel;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\DepartmentsModel;
use yii\web\Controller;

/**
 * Default controller for the `Theses` module
 */
class DefaultController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_departments_and_theses();

        return $this->Render_view('index');
    }

    private function Load_departments_and_theses( $user_id = null )
    {
        $departments = DepartmentsModel::Get_list();

        if( empty( $departments ) )
        {
            return;
        }

        foreach( $departments as $department )
        {
            if( empty( $user_id ) )
            {
                $department->theses = ThesesModel::Get_list_by_department_id( $department->id );
            }
            else
            {
                $department->theses = ThesesModel::Get_list_by_user_and_department_id(
                    $user_id,
                    $department->id
                );
            }

            if( empty( $department->theses ) )
            {
                continue;
            }

            foreach( $department->theses as $thesis )
            {
                $thesis->subscribers = ThesisSubscribersModel::Get_list_by_thesis_id( $thesis->id );
            }
        }

        $this->data["departments"] = $departments;
    }

    public function actionMine()
    {
        if( PermissionHelper::Is_student() )
        {
            return;
        }

        $this->Load_departments_and_theses( UserHelper::Get_user_id() );

        return $this->Render_view('index');
    }
}
