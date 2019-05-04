<?php
namespace app\modules\Practices\helpers;

use app\helpers\PermissionHelper;
use app\modules\Practices\models\PracticeSubscribersModel;
use app\modules\Users\helpers\UserHelper;
use Yii;
use yii\helpers\Url;

class PracticeHelper
{
    public static function Get_url()
    {
        return Url::toRoute( ["/practice"] );
    }
    public static function Get_view_url( $practice )
    {
        if( empty( $practice ) )
        {
            return false;
        }

        return Url::toRoute( ["/practices/view?practice_id=$practice->id"] );
    }

    public static function Get_edit_url( $practice )
    {
        if( ! empty( $practice ) )
        {
            return Url::toRoute( ["/practices/item?practice_id=$practice->id"] );
        }

        return Url::toRoute( ["/practices/item"] );
    }

    public static function Get_delete_url( $practice )
    {
        if( empty( $practice ) )
        {
            return false;
        }

        return Url::toRoute( ["/practices/item/delete?practice_id=$practice->id"] );
    }

    public static function Get_image_delete_url( $practice )
    {
        if( empty( $practice ) )
        {
            return false;
        }

        return Url::toRoute( ["/practices/item/delete_image/?practice_id=$practice->id"] );
    }

    public static function Get_subrscribe_url( $practice )
    {
        if( empty( $practice ) )
        {
            return;
        }

        return Url::toRoute( ["/practices/subscribe?practice_id=$practice->id"] );
    }

    public static function Get_subrscribed_practices_url()
    {
        return Url::toRoute( ["/practices/default/subscribed_practices"] );
    }

    public static function Get_my_practice_url()
    {
        return Url::toRoute( ["/practices/view/my_practice"] );
    }

    public static function Is_subscribed( $practice )
    {
        $user_id = UserHelper::Get_user_id();

        if( empty( $user_id ) || empty( $practice ) )
        {
            return false;
        }

        $subscription = PracticeSubscribersModel::Get_by_user_and_practice_id(
            UserHelper::Get_user_id(),
            $practice->id
        );

        if( empty( $subscription ) )
        {
            return false;
        }

        return true;
    }

    public static function Get_practice_contract_action_url( $practice_assn )
    {
        if( empty( $practice_assn ) )
        {
            return false;
        }

        if( self::Is_contract_signed_by_teacher( $practice_assn ) && ! self::Is_contract_signed_by_company( $practice_assn ) )
        {
            return "sign_company";
        }

        return "generate_contract?practice_id=" . $practice_assn->practice_id;
    }

    public static function Is_contract_generated( $practice_assn )
    {
        if( empty( $practice_assn ) )
        {
            return false;
        }

        if( empty( $practice_assn->contract ) )
        {
            return false;
        }

        return true;
    }

    public static function Is_contract_signed_by_teacher( $practice_assn )
    {
        if( empty( $practice_assn ) )
        {
            return false;
        }

        if( empty( $practice_assn->teacher_sign ) )
        {
            return false;
        }

        return true;
    }

    public static function Is_contract_signed_by_company( $practice_assn )
    {
        if( empty( $practice_assn ) )
        {
            return false;
        }

        if( empty( $practice_assn->company_sign ) )
        {
            return false;
        }

        return true;
    }
}