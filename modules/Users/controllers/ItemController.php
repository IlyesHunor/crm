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
 * Item controller for the `users` module
 */
class ItemController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionDelete()
    {
        if( ! $this->Check_permission() || ! $this->Validate_user() )
        {
            return;
        }

        $this->Delete();

        $this->redirect( Yii::$app->request->referrer );
    }

    private function Delete()
    {
        if( empty( $this->data["user"] ) )
        {
            return;
        }


        $data = array(
            "modified_user_id"  => UserHelper::Get_user_id(),
            "modify_date"       => DateHelper::Get_datetime(),
            "is_deleted"        => 1,
        );

        $model = UsersModel::findOne( $this->data["user"]->id );

        $model->updateAttributes( $data );
    }
}
