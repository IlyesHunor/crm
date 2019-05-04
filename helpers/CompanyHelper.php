<?php
namespace app\helpers;

use app\controllers\FrontSideController;
use app\modules\Users\models\CompaniesModel;
use Yii;

class CompanyHelper extends FrontSideController
{
    public static function Get_company_name( $company_id )
    {
        if( empty( $company_id ) )
        {
            return "";
        }

        return CompaniesModel::Get_by_item_id( $company_id )->name;
    }
}