<?php
if( empty( $events ) )
{
    //todo: load no items on this page from partials
}
else
{
?>

    <div class="content">
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
