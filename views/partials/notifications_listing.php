<?php

use yii\helpers\Url;

if( empty( $notifications ) )
{
    return;
}
?>

<div class="notifications-listing">
    <ul>
        <?php
        foreach( $notifications as $notification )
        {
        ?>
            <li class="<?php echo ! empty( $notification->is_viewed ) ? "readed" : "unreaded" ?>">
                <a href="javascript:void(0)" class="read" data-id="<?php echo $notification->id; ?>">
                    <span class="notification-name">
                        <?php echo $notification->name; ?>
                    </span>
                    <span class="notification-title">
                        <?php echo $notification->title; ?>
                    </span>
                </a>
            </li>
        <?php
        }
        ?>
    </ul>
    <a href="<?php echo Url::toRoute( ["/notifications/default"] ) ?>" class="view-all-notifications">
        <?php echo Yii::t( "app", "View_all_notifications" ); ?>
    </a>
</div>