<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/responsive.css',
        'assets/jquery-ui-1.12.1/jquery-ui.css',
        'assets/jquery-ui-1.12.1/jquery-ui.structure.css',
        'assets/jquery-ui-1.12.1/jquery-ui.theme.css'
    ];
    public $js = [
        'assets/jquery-ui-1.12.1/jquery-ui.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
