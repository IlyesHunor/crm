<?php

use app\modules\Practices\helpers\PracticeHelper;
use yii\helpers\Url;
use app\helpers\PermissionHelper;
use app\modules\Users\helpers\UserHelper;

$subscribed_practices_url   = PracticeHelper::Get_subrscribed_practices_url();
$my_practice_url            = PracticeHelper::Get_my_practice_url();
?>

<div class="sidebar-header">
    <h3>
        <?php echo Yii::t( "app", "Menu" );?>
    </h3>
</div>

<ul class="list-unstyled components">
    <?php
    if( PermissionHelper::Is_company( ) )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["/practices/item"] ); ?>">
                <?php echo Yii::t( "app", "Add_new_practice" ); ?>
            </a>
        </li>
    <?php
    }
    ?>

    <li>
        <a href="<?php echo Url::toRoute( ["/practices"] ); ?>">
            <?php echo Yii::t( "app", "Practices" ); ?>
        </a>
    </li>

    <?php
    if( PermissionHelper::Is_company( ) )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["default/mine"] ); ?>">
                <?php echo Yii::t( "app", "Added_practises" ); ?>
            </a>
        </li>
    <?php
    }
    ?>

    <?php
    if( PermissionHelper::Is_student( ) )
    {
    ?>
        <li>
            <a href="<?php echo $my_practice_url; ?>">
                <?php echo Yii::t( "app", "My_practice" ); ?>
            </a>
        </li>

        <li>
            <a href="<?php echo $subscribed_practices_url; ?>">
                <?php echo Yii::t( "app", "Subscribed_practices" ); ?>
            </a>
        </li>
    <?php
    }

    if( PermissionHelper::Is_coordinator() || PermissionHelper::Is_head_of_department() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["default/student_practices"] ); ?>">
                <?php echo Yii::t( "app", "Student_practice" ); ?>
            </a>
        </li>
    <?php
    }

    if( PermissionHelper::Is_head_of_department() )
    {
    ?>
        <li>
            <a href="<?php echo Url::toRoute( ["contract-templates/index"] ); ?>">
                <?php echo Yii::t( "app", "Contract_templates" ); ?>
            </a>
        </li>
    <?php
    }
    ?>
</ul>