<?php

use app\helpers\PermissionHelper;
use app\modules\Theses\helpers\ThesisHelper;
use app\modules\Users\helpers\UserHelper;

if( empty( $department ) )
{
    return;
}

if( ! empty( $department->theses ) )
{
    ?>
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
                <?php echo Yii::t( "app", "Teacher" ); ?>
            </th>
            <th scope="col">
                <?php echo Yii::t( "app", "Student" ); ?>
            </th>
            <th scope="col">
                <?php echo Yii::t( "app", "Actions" ); ?>
            </th>
        </tr>
        </thead>

        <?php
        foreach( $department->theses as $index => $thesis )
        {
            $edit_url       = ThesisHelper::Get_edit_url( $thesis );
            $subscribe_url  = ThesisHelper::Get_subrscribe_url( $thesis );
            $unsubscribe_url= ThesisHelper::Get_unsubrscribe_url( $thesis );
            ?>

            <tr>
                <td scope="col">
                    <?php echo $index + 1; ?>
                </td>
                <td scope="col">
                    <?php echo $thesis->name; ?>
                </td>
                <td scope="col">
                    <?php echo UserHelper::Get_user_name_by_id( $thesis->user_id ); ?>
                </td>
                <td scope="col">
                    <?php
                    if( PermissionHelper::Is_head_of_department() )
                    {
                    ?>
                        <select style="width: 200px;" class="select-student">
                            <option></option>
                            <?php
                            if( ! empty( $thesis->subscribers ) )
                            {
                                foreach( $thesis->subscribers as $subscriber )
                                {
                                ?>
                                    <option value="<?php echo $subscriber->user_id; ?>"
                                        <?php echo ( $subscriber->user_id == $thesis->student_id ? 'selected="selected"' : "" ); ?>>
                                        <?php echo UserHelper::Get_user_name_by_id( $subscriber->user_id ); ?>
                                    </option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                        <a href="javascript:void(0)" class="btn btn-success add-student-to-thesis"
                            data-thesis-id="<?php echo $thesis->id; ?>">
                            <i class="icon-save">&nbsp;</i>
                            <?php echo Yii::t( "app", "Select" ); ?>
                        </a>
                    <?php
                    }
                    else
                    {
                        echo ( ! empty( $thesis->student_id ) ? UserHelper::Get_user_name_by_id( $thesis->student_id ) : "-" );
                    }
                    ?>
                </td>
                <td scope="col">
                    <?php
                    if( PermissionHelper::Can_modify( $thesis ) )
                    {
                    ?>
                        <a href="<?php echo $edit_url; ?>" class="btn btn-primary">
                            <i class="icon-edit">&nbsp;</i>
                            <?php echo Yii::t( "app", "Modify" ); ?>
                        </a>
                    <?php
                    }

                    if( PermissionHelper::Is_student() )
                    {
                        if( ThesisHelper::Is_subscription_used( $thesis ) )
                        {
                            if( ThesisHelper::Is_subscribed( $thesis ) )
                            {
                            ?>
                                <a href="<?php echo $unsubscribe_url; ?>" class="btn btn-danger confirm-delete">
                                    <i class="icon-delete">&nbsp;</i>
                                    <?php echo Yii::t( "app", "Unsubscribe" ); ?>
                                </a>
                            <?php
                            }
                            else
                            {
                            ?>
                                <a href="<?php echo $subscribe_url; ?>" class="btn btn-success">
                                    <i class="icon-subscribe">&nbsp;</i>
                                    <?php echo Yii::t( "app", "Subscribe" ); ?>
                                </a>
                            <?php
                            }
                        }
                        else
                        {
                        ?>
                            <span>
                                <?php echo Yii::t( "app", "Thesis_already_reserved" ); ?>
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
    return;
}
?>

<div>
    <?php echo Yii::t( "app", "No_result" ); ?>
</div>