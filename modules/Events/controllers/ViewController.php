<?php

namespace app\modules\Events\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\helpers\GetHelper;
use app\helpers\ImageUploader;
use app\helpers\PdfUploader;
use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Events\models\EventsModel;
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
 * Default controller for the `events` module
 */
class ViewController extends FrontSideController
{
    public $event_id;
    public $event_details;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_event_details();

        return $this->Render_view( "index" );
    }

    private function Load_event_details()
    {
        $this->event_id = GetHelper::Get_integer( "event_id" );

        if( empty( $this->event_id ) )
        {
            $this->Set_error_message( Yii::t( "app", "Event_not_found" ) );
        }

        $event = EventsModel::Get_by_item_id( $this->event_id );

        if( empty( $event ) )
        {
            $this->Set_error_message( Yii::t( "app", "Event_not_found" ) );
        }

        $this->event_details = $this->data["event_details"] = $event;
    }
}
