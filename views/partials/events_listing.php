<?php
use app\helpers\DateHelper;
use app\helpers\ImageHelper;
use app\helpers\PermissionHelper;
use app\modules\Events\helpers\EventHelper;

if( empty( $events ) )
{
    return;
}
?>

<ul class="listing">
    <?php
    foreach($events as $event )
    {
        $start_date = DateHelper::Show_date_in_format( $event->start_date );
        $end_date   = DateHelper::Show_date_in_format( $event->end_date );
        $edit_url   = EventHelper::Get_edit_url( $event );
        $view_url   = EventHelper::Get_view_url( $event );
        $delete_url = EventHelper::Get_delete_url( $event );
    ?>
        <li class="event">
            <div class="image-preview">
                <a href="<?php echo $view_url; ?>">
                    <img src="<?php echo ImageHelper::Get_image( $event ); ?>" alt=""/>
                </a>
            </div>

            <div>
                <div>
                    <span class="title">
                        <?php echo $event->name; ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo $event->description; ?>
                    </span>
                </div>

                <div>
                    <span>
                        <?php echo $start_date . " : " . $end_date; ?>
                    </span>
                </div>
            </div>

            <div>
                <a href="<?php echo $view_url; ?>" class="btn btn-info">
                    <?php echo Yii::t( "app", "View" ); ?>
                </a>
                <?php
                if( PermissionHelper::Can_modify( $event ) )
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
            </div>
        </li>
    <?php
    }
    ?>
</ul>