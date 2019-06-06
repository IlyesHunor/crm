<?php
use yii\helpers\Url;
?>

<div class="content home">
    <div class="banner">
        <img src="<?php echo Url::to( "@web/assets/images/banner.jpg" ); ?>" alt=""/>
    </div>

    <h1 style="text-align: center">
        <?php echo Yii::t( "app", "Welcome_text" ); ?>
    </h1>

    <?php
    if( ! empty( $events ) )
    {
    ?>
        <h3 style="text-align: center">
            <?php echo Yii::t( "app", "Events" ); ?>
        </h3>

        <?php echo Yii::$app->controller->renderPartial( "//partials/events_listing", array( "events" => $events ) ); ?>

        <div class="view-all">
            <a href="<?php echo Url::toRoute( ["/events"] ); ?>" class="btn btn-primary">
                <?php echo Yii::t( "app", "View_all_events" ); ?>
            </a>
        </div>
        <?php
    }

    if( ! empty( $practices ) )
    {
    ?>
        <h3 style="text-align: center">
            <?php echo Yii::t( "app", "Practices" ); ?>
        </h3>

        <?php echo Yii::$app->controller->renderPartial( "//partials/practices_listing", array( "practices" => $practices ) ); ?>

        <div class="view-all">
            <a href="<?php echo Url::toRoute( ["/practices"] ); ?>" class="btn btn-primary">
                <?php echo Yii::t( "app", "View_all_practices" ); ?>
            </a>
        </div>

    <?php
    }
    ?>
</div>
