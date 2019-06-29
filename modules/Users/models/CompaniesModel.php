<?php

namespace app\modules\Users\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "companies".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_id
 * @property string $name
 * @property string $code
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class CompaniesModel extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'name', 'code'], 'required'],
            [['added_user_id', 'modified_user_id'], 'integer'],
            [['insert_date', 'modify_date'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
            [['is_enabled', 'is_deleted'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'added_user_id'     => 'Added User ID',
            'modified_user_id'  => 'Modified User ID',
            'name'              => 'Name',
            'code'              => 'Code',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_id( $company_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"        => $company_id,
                    "is_enabled"=> 1,
                    "is_deleted"=> 0
                )
            )
            ->one();
    }

    public static function Get_by_user_id( $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "user_id"   => $user_id,
                    "is_enabled"=> 1,
                    "is_deleted"=> 0
                )
            )
            ->one();
    }

    public static function Get_list()
    {
        return self::find()
            ->andOnCondition(
                array(
                    "is_enabled"=> 1,
                    "is_deleted"=> 0
                )
            )
            ->all();
    }
}
