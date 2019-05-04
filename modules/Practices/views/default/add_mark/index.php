<?php
use app\helpers\PostHelper;
?>

<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", "Add_mark" ); ?>
    </h1>

    <form action="" method="post" class="contract-template-form">
        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />

        <div class="form-group">
            <div>
                <label for="text">
                    <?php echo Yii::t( "app", "Mark" ); ?>
                </label>
            </div>
            <div>
                <input type="text" name="mark" id="mark" value="<?php PostHelper::Get_integer( "mark", $practice_details ); ?>">
            </div>
        </div>

        <input type="submit" value="<?php echo Yii::t( "app", "Save" ); ?>" class="btn btn-primary">
    </form>
</div>