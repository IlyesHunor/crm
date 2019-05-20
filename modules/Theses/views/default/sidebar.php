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
    if( ! PermissionHelper::Is_company() && ! PermissionHelper::Is_student() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["/theses/item"] ); ?>">
                <?php echo Yii::t( "app", "Add_new_thesis" ); ?>
            </a>
        </li>
    <?php
    }
    ?>

    <li>
        <a href="<?php echo Url::toRoute( ["/theses"] ); ?>">
            <?php echo Yii::t( "app", "Theses" ); ?>
        </a>
    </li>

    <?php
    if( ! PermissionHelper::Is_company() && ! PermissionHelper::Is_student() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["default/mine"] ); ?>">
                <?php echo Yii::t( "app", "Added_theses" ); ?>
            </a>
        </li>
    <?php
    }
    ?>
</ul>