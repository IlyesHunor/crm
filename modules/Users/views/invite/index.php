<nav id="sidebar">
    <?php use app\helpers\PostHelper;

    echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h3>
        <?php echo Yii::t( "app", "Invite" ); ?>
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
                <input type="text" name="email" id="email" value="<?php echo PostHelper::Get( "email" ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="user_type_id">
                    <?php echo Yii::t( "app", "Name" ); ?>
                </label>
            </div>
            <div class="input-text">
                <select name="user_type_id" id="user_type_id">
                    <?php
                    if( ! empty( $user_types ) )
                    {
                        foreach( $user_types as $user_type )
                        {
                        ?>
                            <option value="<?php echo $user_type->id; ?>">
                                <?php echo Yii::t( "app", $user_type->name ); ?>
                            </option>
                        <?php
                        }
                    }
                    ?>
                </select>
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
