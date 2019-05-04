<?php
$template_details = ( ! empty( $template_details ) ? $template_details : "" );
use app\helpers\PostHelper;
use yii\helpers\Url;

?>
<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", empty( $template_details ) ? "Add_new_template" : "Modify_template" ); ?>
    </h1>

    <form action="" method="post" class="contract-template-form">
        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />

        <div class="form-group">
            <div>
                <label for="name">
                    <?php echo Yii::t( "app", "Name" ); ?>
                </label>
            </div>
            <div class="input-text">
                <input type="text" name="name" id="name"
                    value="<?php echo PostHelper::Get( "name", $template_details ); ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="text">
                    <?php echo Yii::t( "app", "Text" ); ?>
                </label>
            </div>
            <div class="textarea">
                <textarea name="text" id="text"><?php echo PostHelper::Get( "text", $template_details ); ?></textarea>
            </div>
        </div>

        <input type="submit" value="<?php echo Yii::t( "app", "Save" ); ?>" class="btn btn-primary">
    </form>
</div>