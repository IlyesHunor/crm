<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;
use app\modules\Users\models\LoginForm;
use Yii;
/**
 * Default controller for the `users` module
 */
class LoginController extends FrontSideController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if( ! Yii::$app->user->isGuest ) 
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
