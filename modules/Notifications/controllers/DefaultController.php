<?php

namespace app\modules\Notifications\controllers;

use app\controllers\FrontSideController;
use app\helpers\DateHelper;
use app\modules\Notifications\helpers\NotificationHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Users\helpers\UserHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Default controller for the `notifications` module
 */
class DefaultController extends FrontSideController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->Load_notifications();
        $this->Mark_all_notifications_as_read();

        return $this->Render_view('index' );
    }

    private function Load_notifications()
    {
        $this->data["notifications"] = NotificationHelper::Get_notifications_list();
    }

    private function Mark_all_notifications_as_read()
    {
        if( empty( $this->data["notifications"] ) )
        {
            return;
        }

        $notifications = $this->data["notifications"];

        foreach( $notifications as $notification )
        {
            $model  = NotificationsModel::Get_by_item_id( $notification->id );
            $data   = array(
                "modified_user_id"  => UserHelper::Get_user_id(),
                "is_viewed"         => "1",
                "modify_date"       => DateHelper::Get_datetime(),
            );

            $model->updateAttributes( $data );
        }
    }
}
