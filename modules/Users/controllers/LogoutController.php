<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;
use app\modules\Users\models\LoginForm;
use Yii;
use yii\web\Response;

/**
 * Default controller for the `users` module
 */
class LogoutController extends FrontSideController
{
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionIndex()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
