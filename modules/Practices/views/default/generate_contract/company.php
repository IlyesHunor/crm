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

        <div class="form-group">
            <div>
                <label for="sign">
                    <?php echo Yii::t( "app", "Sign" ); ?>
                </label>
            </div>
            <div class="signing-box">
                <?php
                if( ! empty( $practice_assn->company_sign ) )
                {
                ?>
                    <img src="<?php echo Yii::getAlias( "@imgUrl" ) . $practice_assn->company_sign; ?>" alt=""/>
                 <?php
                }
                else
                {
                ?>
                    <canvas id="sign" data-type="university"></canvas>
                    <a href="javascript:void(0)" class="clear-signing-box">
                        <img src="<?php echo Url::toRoute( "/assets/images/x-mark.png" ); ?>" alt=""/>
                    </a>
                <?php
                }
                ?>
            </div>
        </div>

        <input type="hidden" value="" name="signing-image">
        <input type="hidden" value="company_sign" name="type">
        <input type="submit" value="<?php echo Yii::t( "app", "Save" ); ?>" class="btn btn-primary">
    </form>
</div>