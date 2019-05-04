<?php
$practices = ( ! empty( $practices ) ? $practices : "" );
?>
<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", "Practices" ); ?>
    </h1>
    <?php echo Yii::$app->controller->renderPartial( "practices_listing", [ "practices" => $practices ] ); ?>
</div>