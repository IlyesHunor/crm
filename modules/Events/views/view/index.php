<?php

use app\modules\Practices\helpers\PracticeHelper;
use yii\helpers\Url;

if( empty( $event_details ) )
{
    return;
}
?>

<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo $event_details->name; ?>
    </h1>

    <div>
        <img src="<?php echo Yii::getAlias( "@imgUrl" ) . $event_details->image; ?>" alt="" style="max-width: 100%">
    </div>

    <div>
        <?php echo $event_details->description; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "Start_date" ) . ": " . $event_details->start_date; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "End_date" ) . ": " . $event_details->end_date; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "Helyszín" ) . ": " . $event_details->city . " " . $event_details->address; ?>
    </div>
</div>