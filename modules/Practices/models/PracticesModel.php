<?php

namespace app\modules\Practices\models;

use Yii;

/**
 * This is the model class for table "practises".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_id
 * @property string $company_id
 * @property string $name
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $description
 * @property string $image
 * @property int $max_participants
 * @property string $start_date
 * @property string $end_date
 * @property string $deadline_date
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class PracticesModel extends \yii\db\ActiveRecord
{
    public $assn_details;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'modified_user_id', 'user_id', 'company_id', 'max_participants'], 'integer'],
            [['name'], 'required'],
            [['description', 'image'], 'string'],
            [['start_date', 'end_date', 'deadline_date', 'insert_date', 'modify_date'], 'date', 'format' => 'php:Y-m-d'],
            [['name', 'country', 'city', 'address'], 'string', 'max' => 255],
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
            'user_id'           => 'User ID',
            'company_id'        => 'Company ID',
            'name'              => 'Name',
            'country'           => 'Country',
            'city'              => 'City',
            'address'           => 'Address',
            'description'       => 'Description',
            'image'             => 'Image',
            'max_participants'  => 'Max Participants',
            'start_date'        => 'Start Date',
            'end_date'          => 'End Date',
            'deadline_date'     => 'Deadline Date',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_id( $practice_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"            => $practice_id,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Get_by_item_and_user_id( $practice_id, $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"        => $practice_id,
                    "user_id"   => $user_id,
                    "is_deleted"=> 0,
                )
            )
            ->one();
    }

    public static function Get_list_where_is_enabled()
    {
        return self::find()
            ->andOnCondition(
                array(
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->all();
    }

    public static function Get_list_by_user_id( $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "user_id"   => $user_id,
                    "is_enabled"=> 1,
                    "is_deleted"=> 0,
                )
            )
            ->all();
    }

    public static function Count_where_is_enabled()
    {
        return self::find()
            ->andOnCondition(
                array(
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->count();
    }
}
