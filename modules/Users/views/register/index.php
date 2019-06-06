<?php
use app\helpers\PostHelper;

if( empty( $user_details ) )
{
    return;
}
?>

<div class="content">
    <h3>
        <?php echo Yii::t( "app", "Registration" ); ?>
    </h3>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
        <div class="form-group">
            <div>
                <label for="email">
                    <?php echo Yii::t( "app", "Email" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="email" id="email" value="<?php echo PostHelper::Get( "email", $user_details ); ?>"
                    disabled="disabled"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="first_name">
                    <?php echo Yii::t( "app", "First_name" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="first_name" id="first_name" value="<?php echo PostHelper::Get( "first_name", $user_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="last_name">
                    <?php echo Yii::t( "app", "Last_name" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="last_name" id="last_name" value="<?php echo PostHelper::Get( "last_name", $user_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="password">
                    <?php echo Yii::t( "app", "Password" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="password" name="password" id="password"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="confirm_password">
                    <?php echo Yii::t( "app", "Confirm_password" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="password" name="confirm_password" id="confirm_password"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="confirm_password">
                    <a href="">
                        <?php echo Yii::t( "app", "Terms_and_conditions" ); ?>
                    </a>
                </label>
            </div>
            <div class="input-text">
                <input type="checkbox" name="gdpr" id="confirm_password"/>
            </div>
        </div>

        <div>
            <input type="submit" class="btn btn-success" value="<?php echo Yii::t( "app", "Save" ); ?>">
            <a href="<?php echo \yii\helpers\Url::toRoute(["/users"]); ?>" class="btn btn-danger">
                <?php echo Yii::t( "app", "Back" ); ?>
            </a>
        </div>
    </form>
</div>
