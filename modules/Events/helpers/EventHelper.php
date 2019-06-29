<?php
namespace app\modules\Events\helpers;

use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\helpers\Url;

class EventHelper
{
    public static function Get_url()
    {
        return Url::toRoute( ["/events"] );
    }
    public static function Get_view_url( $event )
    {
        if( empty( $event ) )
        {
            return false;
        }

        return Url::toRoute( ["/events/view?event_id=$event->id"] );
    }

    public static function Get_edit_url( $event )
    {
        if( ! empty( $event ) )
        {
            return Url::toRoute( ["/events/item?event_id=$event->id"] );
        }

        return Url::toRoute( ["/events/item"] );
    }

    public static function Get_delete_url( $event )
    {
        if( empty( $event ) )
        {
            return false;
        }

        return Url::toRoute( ["/events/item/delete?event_id=$event->id"] );
    }

    public static function Get_image_delete_url( $event )
    {
        if( empty( $event ) )
        {
            return false;
        }

        return Url::toRoute( ["/events/item/delete_image/?event_id=$event->id"] );
    }
}