<?php
use app\helpers\DateHelper;
use app\helpers\ImageHelper;
use app\helpers\PermissionHelper;
use app\modules\Events\helpers\EventHelper;
use app\modules\Practices\helpers\PracticeHelper;

if( empty( $practices ) )
{
    return;
}
?>

<ul class="listing">
    <?php
    foreach( $practices as $practice )
    {
        $start_date     = DateHelper::Show_date_in_format( $practice->start_date );
        $end_date       = DateHelper::Show_date_in_format( $practice->end_date );
        $edit_url       = PracticeHelper::Get_edit_url( $practice );
        $view_url       = PracticeHelper::Get_view_url( $practice );
        $delete_url     = PracticeHelper::Get_delete_url( $practice );
        $subscribe_url  = PracticeHelper::Get_subrscribe_url( $practice );
        ?>

        <li class="item">
            <div class="image-preview">
                <a href="<?php echo $view_url; ?>">
                    <img src="<?php echo ImageHelper::Get_image( $practice ); ?>" alt=""/>
                </a>
            </div>

            <div class="description">
                <div>
                    <span class="title">
                        <?php echo $practice->name; ?>
                    </span>
                </div>

                <div class="intro">
                    <span>
                        <?php echo $practice->description; ?>
                    </span>
                </div>

                <div class="dates">
                    <span>
                        <?php echo Yii::t( "app", "Start" ) . " : " . $start_date; ?>
                    </span>
                    <span>
                        <?php echo Yii::t( "app", "End_date" ) . " : " . $end_date; ?>
                    </span>
                </div>
            </div>

            <div>
                <?php
                if( PermissionHelper::Can_modify( $practice ) )
                {
                ?>
                    <a href="<?php echo $edit_url; ?>" class="btn btn-primary">
                        <?php echo Yii::t( "app", "Modify" ); ?>
                    </a>
                    <a href="<?php echo $delete_url; ?>" class="btn btn-danger confirm-delete">
                        <?php echo Yii::t( "app", "Delete" ); ?>
                    </a>
                <?php
                }
                ?>

                <a href="<?php echo $view_url; ?>" class="btn btn-info">
                    <?php echo Yii::t( "app", "View" ); ?>
                </a>

                <?php
                if( PermissionHelper::Is_student() )
                {
                    if( PracticeHelper::Is_subscribed( $practice ) )
                    {
                    ?>
                        <span class="btn btn-success">
                            <?php echo Yii::t( "app", "Subscribed" ); ?>
                        </span>
                        <?php
                    }
                    else
                    {
                    ?>
                        <a href="<?php echo $subscribe_url; ?>" class="btn btn-primary">
                            <?php echo Yii::t( "app", "Subscribe" ); ?>
                        </a>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </div>
        </li>
    <?php
    }
    ?>
</ul>