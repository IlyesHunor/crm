<?php

namespace app\modules\Practices\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "practices_users_assn".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $practice_id
 * @property string $user_id
 * @property int $mark
 * @property string $report
 * @property string $contract
 * @property string $teacher_sign
 * @property string $company_sign
 * @property int $company_rating
 * @property int $user_rating
 * @property string $insert_date
 * @property int $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class PracticesUsersAssnModel extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practices_users_assn';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'modified_user_id', 'practice_id', 'user_id', 'mark', 'company_rating', 'user_rating', 'modify_date'], 'integer'],
            [['report'], 'string'],
            [['insert_date'], 'safe'],
            [['is_enabled', 'is_deleted'], 'integer', 'max' => 1],
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
            'practice_id'       => 'Practice ID',
            'user_id'           => 'User ID',
            'mark'              => 'Mark',
            'report'            => 'Report',
            'company_rating'    => 'Company Rating',
            'user_rating'       => 'User Rating',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_practice_id_and_user_id( $practice_id, $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "practice_id"   => $practice_id,
                    "user_id"       => $user_id,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Get_by_practice_id( $practice_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "practice_id"   => $practice_id,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Get_by_user_id_where_is_enabled( $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "user_id"       => $user_id,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Get_by_item_id( $assn_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"            => $assn_id,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }
}
