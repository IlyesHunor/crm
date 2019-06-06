<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\PermissionHelper;
use app\modules\Users\helpers\UserHelper;
use app\modules\Users\models\UsersModel;
use app\modules\Users\models\UserTypesModel;
use Yii;

/**
 * Default controller for the `users` module
 */
class DefaultController extends FrontSideController
{
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

        $this->Load_user_types();
        $this->Load_users();

        return $this->Render_view( "index" );
    }

    private function Check_permission()
    {
        if( ! PermissionHelper::Is_admin() )
        {
            return false;
        }

        return true;
    }

    private function Load_user_types()
    {
        $this->data["user_types"] = UserTypesModel::Get_list();
    }

    private function Load_users()
    {
        if( empty( $this->data["user_types"] ) )
        {
            return;
        }

        $user_types = $this->data["user_types"];

        foreach( $user_types as $user_type )
        {
            $user_type->users = UsersModel::Get_list_by_user_type_id( $user_type->id );
        }
    }
}
