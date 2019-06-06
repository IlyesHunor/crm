<?php

use app\modules\Practices\helpers\PracticeHelper;
use yii\helpers\Url;

if( empty( $practice_details ) )
{
    return;
}

$accept_url = Url::toRoute( ["/practices/accept?practice_id=".$practice_details->id] );
$delete_url = Url::toRoute( ["/practices/accept/delete?practice_id=".$practice_details->id] );
?>

<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo $practice_details->name; ?>
    </h1>

    <div>
        <img src="<?php echo Yii::getAlias( "@imgUrl" ) . $practice_details->image; ?>" alt="">
    </div>

    <div>
        <?php echo $practice_details->description; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "Start_date" ) . ": " . $practice_details->start_date; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "End_date" ) . ": " . $practice_details->end_date; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "Max_participants" ) . ": " . $practice_details->max_participants; ?>
    </div>

    <div>
        <?php echo Yii::t( "app", "Deadline_date" ) . ": " . $practice_details->deadline_date; ?>
    </div>

    <?php
    if( ! empty( $subscriptions ) )
    {
    ?>
        <h3>
            <?php echo Yii::t( "app", "Subscribed_students" ); ?>
        </h3>

        <div>
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
                        <?php echo Yii::t( "app", "Subscription_date" ); ?>
                    </th>
                    <th scope="col">
                        <?php echo Yii::t( "app", "Actions" ); ?>
                    </th>
                    <th scope="col">
                        <?php echo Yii::t( "app", "Contract" ); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                if( ! empty( $subscriptions ) )
                {
                    foreach( $subscriptions as $index => $subscription )
                    {
                    ?>
                        <tr>
                            <th scope="row">
                                <?php echo $index + 1; ?>
                            </th>
                            <td>
                                <?php echo $subscription->last_name . " " . $subscription->first_name; ?>
                            </td>
                            <td>
                                <?php echo $subscription->insert_date; ?>
                            </td>
                            <td>
                                <?php
                                if( empty( $subscription->is_accepted ) )
                                {
                                    ?>
                                    <a href="<?php echo $accept_url . "&user_id=" . $subscription->user_id; ?>"
                                       class="btn btn-primary">
                                        <i class="icon-save">&nbsp;</i>
                                        <?php echo Yii::t( "app", "Accept" ); ?>
                                    </a>
                                    <?php
                                }
                                else
                                {
                                ?>
                                    <span class="btn btn-success">
                                        <?php echo Yii::t( "app", "Accepted" ); ?>
                                    </span>
                                <?php
                                }
                                ?>
                                <a href="<?php echo $delete_url . "&user_id=" . $subscription->user_id; ?>" class="btn btn-danger confirm-delete">
                                    <i class="icon-delete">&nbsp;</i>
                                    <?php echo Yii::t( "app", "Delete" ); ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                if( empty( $subscription->practice_assn ) )
                                {
                                    echo "-";
                                }
                                else
                                {
                                    $class = "btn-danger";
                                    $title = Yii::t("app", "Generate_contract");

                                    if( PracticeHelper::Is_contract_signed_by_company( $subscription->practice_assn ) )
                                    {
                                        $class = "btn-success";
                                        $title = Yii::t("app", "Contract_generated");
                                    }
                                    ?>

                                    <a href="<?php echo "default/" . PracticeHelper::Get_practice_contract_action_url( $subscription->practice_assn ); ?>"
                                       class="btn <?php echo $class; ?>" title="">
                                        <?php echo Yii::t("app", "Contract"); ?>
                                    </a>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php
    }
    ?>
</div>