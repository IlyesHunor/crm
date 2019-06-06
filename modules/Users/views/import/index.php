<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h3>
        <?php echo Yii::t( "app", "Import" ); ?>
    </h3>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
        <div class="form-group">
            <div>
                <label for="file">
                    <?php echo Yii::t( "app", "Upload_file" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="file" name="file" id="file"/>
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
