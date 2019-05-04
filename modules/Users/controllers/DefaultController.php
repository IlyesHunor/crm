<?php

namespace app\modules\Users\controllers;

use app\controllers\FrontSideController;

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
        return $this->render('index');
    }
}
