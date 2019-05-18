<?php

use app\modules\Practices\helpers\NotificationHelper;
use yii\helpers\Url;
use app\helpers\PermissionHelper;
use app\modules\Users\helpers\UserHelper;

?>

<div class="sidebar-header">
    <h3>
        <?php echo Yii::t( "app", "Menu" );?>
    </h3>
</div>

<ul class="list-unstyled components">
    <?php
    if( ! PermissionHelper::Is_student() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["/events/item"] ); ?>">
                <?php echo Yii::t( "app", "Add_new_event" ); ?>
            </a>
        </li>
    <?php
    }
    ?>

    <li>
        <a href="<?php echo Url::toRoute( ["/events"] ); ?>">
            <?php echo Yii::t( "app", "Events" ); ?>
        </a>
    </li>

    <?php
    if( ! PermissionHelper::Is_student() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["default/mine"] ); ?>">
                <?php echo Yii::t( "app", "Added_events" ); ?>
            </a>
        </li>
    <?php
    }
    ?>
</ul>