<?php

namespace app\modules\Events\controllers;

use app\controllers\FrontSideController;
use app\helpers\PermissionHelper;
use app\modules\Events\models\EventsModel;
use app\modules\Users\helpers\UserHelper;
use yii\web\Controller;

/**
 * Default controller for the `events` module
 */
class DefaultController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_events();

        return $this->Render_view( 'index' );
    }

    public function actionMine()
    {
        if( PermissionHelper::Is_student() )
        {
            return;
        }

        $this->Load_added_events();

        return $this->Render_view('index');
    }

    private function Load_added_events()
    {
        $this->data["events"] = EventsModel::Get_list_by_user_id( UserHelper::Get_user_id() );
    }
}
