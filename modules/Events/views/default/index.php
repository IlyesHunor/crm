<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "sidebar" ); ?>
</nav>

<?php
if( empty( $events ) )
{
?>
    <div class="content with-sidebar no-result">
        <?php echo Yii::t( "app", "No_items_on_this_page" ); ?>
    </div>

    <?php
    return;
}
?>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", "Events" ); ?>
    </h1>

    <div class="events-listing">
        <?php echo Yii::$app->controller->renderPartial( "events_listing", [ "events" => $events ] ); ?>
    </div>
</div>


