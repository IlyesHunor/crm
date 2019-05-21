<?php

use app\helpers\PermissionHelper;
use app\helpers\PostHelper;
use app\modules\Practices\helpers\PracticeHelper;
use \app\modules\Users\helpers\UserHelper;
use \app\helpers\CompanyHelper;
use yii\helpers\Url;

if( empty( $departments ) )
{
    //todo:nothing to list
    return;
}
?>

<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <?php
    foreach( $departments as $department )
    {
        if( ! empty( $department->years ) )
        {
        ?>
            <h2>
                <?php echo Yii::t( "app", $department->name ); ?>
            </h2>
            <?php
            foreach( $department->years as $year )
            {
                if( ! empty( $year->students ) )
                {
                ?>
                    <h3>
                        <?php echo Yii::t( "app", $year->name ); ?>
                    </h3>
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">
                                    <?php echo Yii::t( "app", "Nr" ); ?>
                                </th>
                                <th scope="col">
                                    <?php echo Yii::t( "app", "Name" ); ?>
                                </th>
                                <th scope="col">
                                    <?php echo Yii::t( "app", "Company" ); ?>
                                </th>
                                <th scope="col">
                                    <?php echo Yii::t( "app", "Mark" ); ?>
                                </th>
                                <th scope="col">
                                    <?php echo Yii::t( "app", "Actions" ); ?>
                                </th>
                            </tr>
                        </thead>
                        <?php
                        foreach( $year->students as $index => $student )
                        {
                            $company_id = ! empty( $student->practice_details->company_id ) ? $student->practice_details->company_id : 0;
                            ?>
                            <tr>
                                <td scope="col">
                                    <?php echo $index+1; ?>
                                </td>
                                <td scope="col">
                                    <?php echo UserHelper::Get_user_name_by_id( $student->id ); ?>
                                </td>
                                <td scope="col">
                                    <?php echo CompanyHelper::Get_company_name( $company_id ); ?>
                                </td>
                                <td scope="col">
                                    <?php
                                    if( PermissionHelper::Is_coordinator( $department ) && ! empty( $student->practice_assn ) )
                                    {
                                    ?>
                                        <input type="number" name="mark" min="1" max="10" style="width: 50px"
                                            value="<?php echo ( ! empty( $student->practice_assn->mark ) ? $student->practice_assn->mark : "" ); ?>">
                                        <a href="javascript:void(0);" class="btn btn-primary save-mark"
                                            data-practice-assn-id="<?php echo $student->practice_assn->id; ?>"
                                            data-department-id="<?php echo $department->id; ?>">
                                            <?php echo Yii::t( "app", "Add_mark" ); ?>
                                        </a>
                                    <?php
                                    }
                                    else
                                    {
                                        echo ( ! empty( $student->practice_assn->mark ) ? $student->practice_assn->mark : "" );
                                    }
                                    ?>
                                </td>
                                <td scope="col">
                                    <?php
                                    if( PermissionHelper::Is_head_of_department() && ! empty( $student->practice_assn ) )
                                    {
                                        $class = "btn-danger";
                                        $title = Yii::t( "app", "Generate_contract" );

                                        if( PracticeHelper::Is_contract_generated( $student->practice_assn ) )
                                        {
                                            $class = "btn-success";
                                            $title = Yii::t( "app", "Contract_generated" );
                                        }
                                        ?>
                                        <a href="<?php echo PracticeHelper::Get_practice_contract_action_url( $student->practice_assn ); ?>"
                                            class="btn <?php echo $class; ?>" title="<?php echo $title; ?>">
                                            <?php echo Yii::t( "app", "Contract" ); ?>
                                        </a>
                                    <?php
                                    }

                                    if( ! empty( $student->practice_assn ) )
                                    {
                                        if( ! empty( $student->practice_assn->report ) )
                                        {
                                        ?>
                                            <a href="<?php echo Yii::getAlias( "@imgUrl" ).$student->practice_assn->report; ?>"
                                               class="btn btn-success" data-fancybox="gallery"
                                               title="<?php echo Yii::t( "app", "Download_report" ); ?>">
                                                <?php echo Yii::t( "app", "Report" ); ?>
                                            </a>
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                            <span class="btn btn-danger" title="<?php echo Yii::t( "app", "Report_not_uploaded" ); ?>">
                                                <?php echo Yii::t( "app", "Report" ); ?>
                                            </span>
                                        <?php
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                <?php
                }
            }
        }
        ?>
    <?php
    }
    ?>
</div>