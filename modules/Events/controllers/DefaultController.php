<?php

namespace app\modules\Events\controllers;

use app\controllers\FrontSideController;
use app\modules\Events\models\EventsModel;
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

    private function Load_events()
    {
        $this->data["total_items"]  = EventsModel::Count_where_is_enabled_and_public();
        $this->data["events"]       = EventsModel::Get_list_where_is_enabled_and_public();
    }
}
