<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "sidebar" ); ?>
</nav>
<?php
if( empty( $events ) )
{
    //todo: load no items on this page from partials
}
else
{
?>
    <div class="content with-sidebar">
        <h1>
            <?php echo Yii::t( "app", "Events" ); ?>
        </h1>

        <div class="events-listing">
            <?php echo Yii::$app->controller->renderPartial( "events_listing", [ "events" => $events ] ); ?>
        </div>
    </div>
<?php
}
?>
