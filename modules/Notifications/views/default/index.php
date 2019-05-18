<?php

use app\helpers\DateHelper;
use yii\helpers\Url;

if( empty( $notifications ) )
{
    return;
}
?>
<h2>
    <?php echo Yii::t( "app", "Notifications" ); ?>
</h2>
<div class="notifications-listing-big">
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">
                <?php echo Yii::t( "app", "Nr" ); ?>
            </th>
            <th scope="col">
                <?php echo Yii::t( "app", "Name" ); ?>
            </th>
            <th scope="col">
                <?php echo Yii::t( "app", "Title" ); ?>
            </th>
            <th scope="col">
                <?php echo Yii::t( "app", "Date" ); ?>
            </th>
        </tr>
        <?php
        foreach( $notifications as $index => $notification )
        {
        ?>
        <tr>
            <td scope="col">
                <?php echo $index+1; ?>
            </td>
            <td scope="col">
                <?php echo $notification->name; ?>
            </td>
            <td scope="col">
                <?php echo $notification->title; ?>
            </td>
            <td scope="col">
                <?php echo DateHelper::Show_date_in_format( $notification->insert_date ); ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
