<?php
?>
<div class="content home">
    <h1>
        <?php echo Yii::t( "app", "Welcome_text" ); ?>
    </h1>
    <?php
    if( ! empty( $events ) )
    {
    ?>
        <h3>
            <?php echo Yii::t( "app", "Events" ); ?>
        </h3>

        <?php
        echo Yii::$app->controller->renderPartial( "//partials/events_listing", array( "events" => $events ) );
    }

    if( ! empty( $practices ) )
    {
    ?>
        <h3>
            <?php echo Yii::t( "app", "Practices" ); ?>
        </h3>

        <?php
        echo Yii::$app->controller->renderPartial( "//partials/practices_listing", array( "practices" => $practices ) );
    }
    ?>
</div>
