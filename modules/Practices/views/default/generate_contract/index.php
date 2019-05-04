<?php
$template_details   = ( ! empty( $template_details ) ? $template_details : "" );
$contract_template  = ( ! empty( $practice_assn ) && ! empty( $practice_assn->contract ) ? $practice_assn->contract : $template_details->text );
use app\helpers\PostHelper;
use yii\helpers\Url;

?>
<nav id="sidebar">
    <?php echo Yii::$app->controller->renderPartial( "/default/sidebar" ); ?>
</nav>

<div class="content with-sidebar">
    <h1>
        <?php echo Yii::t( "app", empty( $practice_assn ) ? "Generate_contract" : "Modify_contract" ); ?>
    </h1>

    <form action="" method="post" class="contract-template-form">
        <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />

        <div class="form-group">
            <div>
                <label for="text">
                    <?php echo Yii::t( "app", "Text" ); ?>
                </label>
            </div>
            <div class="textarea">
                <textarea name="text" id="text"><?php echo $contract_template; ?></textarea>
            </div>
        </div>

        <input type="submit" value="<?php echo Yii::t( "app", "Save" ); ?>" class="btn btn-primary">
    </form>
</div>