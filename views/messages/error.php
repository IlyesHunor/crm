<?php
if( Yii::$app->session->hasFlash( 'error' ) )
{
?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

        <h4><i class="icon fa fa-check"></i>Saved!</h4>

        <?php echo Yii::$app->session->getFlash( 'error', "", false ); ?>
    </div>
<?php
}