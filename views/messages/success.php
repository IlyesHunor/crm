<?php
if( Yii::$app->session->hasFlash( 'success' ) )
{
?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

        <h4><i class="icon fa fa-check"></i>Saved!</h4>

        <?php Yii::$app->session->getFlash( 'success', "", false ); ?>
    </div>
<?php
}