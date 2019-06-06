<?php

use yii\helpers\Url;
use app\helpers\PermissionHelper;
?>

<div class="sidebar-header">
    <h3>
        <?php echo Yii::t( "app", "Menu" );?>
    </h3>
</div>

<ul class="list-unstyled components">
    <?php
    if( PermissionHelper::Is_admin() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["/users/invite"] ); ?>">
                <?php echo Yii::t( "app", "Invite_user" ); ?>
            </a>
        </li>

        <li>
            <a href="<?php echo Url::toRoute( ["/users/import"] ); ?>">
                <?php echo Yii::t( "app", "Import" ); ?>
            </a>
        </li>
    <?php
    }
    ?>

    <li>
        <a href="<?php echo Url::toRoute( ["/users"] ); ?>">
            <?php echo Yii::t( "app", "Users" ); ?>
        </a>
    </li>
</ul>