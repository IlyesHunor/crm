<?php

use app\helpers\PermissionHelper;
use app\modules\Theses\helpers\ThesisHelper;
use app\modules\Users\helpers\UserHelper;

if( empty( $user_type ) )
{
    return;
}

if( ! empty( $user_type->users ) )
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
                <?php echo Yii::t( "app", "Status" ); ?>
            </th>
            <th scope="col">
                <?php echo Yii::t( "app", "Actions" ); ?>
            </th>
        </tr>
        </thead>

        <?php
        foreach( $user_type->users as $user )
        {
            if( empty( $user->password ) )
            {
                continue;
            }

            $edit_url   = UserHelper::Get_edit_url( $user );
            $delete_url = UserHelper::Get_delete_url( $user );
            ?>
            <tr>
                <td scope="col">
                    <?php echo $user->id; ?>
                </td>
                <td scope="col">
                    <?php echo UserHelper::Get_user_name_by_id( $user->id ); ?>
                </td>
                <td scope="col">
                    <a href="javascript:void(0)"
                        class="btn <?php echo ( ! empty( $user->is_enabled ) ? "btn-success" : "btn-danger" ); ?> enable-user"
                        data-user-id="<?php echo $user->id; ?>">
                        <?php echo Yii::t( "app", ( ! empty( $user->is_enabled ) ? "Enabled" : "Disabled" ) ); ?>
                    </a>
                </td>
                <td scope="col">
                    <a href="<?php echo $edit_url; ?>"
                       class="btn btn-primary">
                        <?php echo Yii::t( "app", "Modify" ); ?>
                    </a>
                    <a href="<?php echo $delete_url; ?>" class="btn btn-danger confirm-delete">
                        <?php echo Yii::t( "app", "Delete" ); ?>
                    </a>
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