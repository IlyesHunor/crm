<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\modules\Notifications\helpers\NotificationHelper;
use app\modules\Notifications\models\NotificationsModel;
use app\modules\Users\helpers\UserHelper;use app\widgets\Alert;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo Html::csrfMetaTags() ?>
    <title><?php echo Html::encode($this->title) ?></title>
    <script type="text/javascript">
        const base_url = "<?php echo BaseUrl::base() ?>";
    </script>
    <?php $this->head() ?>
</head>
<body>
<?php
$this->beginBody();

$has_notifications  = NotificationHelper::Has_unreaded_notifications();
$notifications      = NotificationHelper::Get_notifications_list();
$class              = "notifications";
$class              .= ( ! empty( $has_notifications ) ? " has-notifications" : "" );
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'id'    => 'menu',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => Yii::t( "app", "Home" ), 'url' => ['/site/index']],
            ['label' => Yii::t( "app", "Practices" ), 'url' => ['/practices']],
            ['label' => Yii::t( "app", "Events" ), 'url' => ['/events']],
            '<li class="'. $class .'">'.
                '<a id="notification-button">'.
                    Yii::t( "app", "Notifications" ).
                '</a>'.
                Yii::$app->controller->renderPartial(
                    "//partials/notifications_listing",
                    array(
                        "notifications" => $notifications
                    )
            ).
            '</li>',
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/users/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/users/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . UserHelper::Get_user_name() . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?php echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php
        echo Alert::widget();
        echo Yii::$app->controller->renderPartial( "//messages/error" );
        echo Yii::$app->controller->renderPartial( "//messages/success" );
        echo Yii::$app->controller->renderPartial( "//partials/overlay" );
        echo $content
        ?>
    </div>`
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?php echo date('Y') ?></p>

        <p class="pull-right"><?php echo Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

