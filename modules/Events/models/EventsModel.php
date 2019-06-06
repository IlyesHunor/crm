<?php

namespace app\modules\Events\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "events".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_id
 * @property string $event_category_id
 * @property string $name
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $institution
 * @property string $description
 * @property string $image
 * @property string $start_date
 * @property string $end_date
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_public
 * @property int $is_enabled
 * @property int $is_deleted
 */
class EventsModel extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'modified_user_id', 'user_id', 'event_category_id'], 'integer'],
            [['name', 'event_category_id'], 'required'],
            [['description', 'image'], 'string'],
            [['start_date', 'end_date', 'insert_date', 'modify_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['name', 'country', 'city', 'address', 'institution'], 'string', 'max' => 255],
            [['is_public', 'is_enabled', 'is_deleted'], 'string', 'max' => 1],
            [['name', 'description', 'country', 'city', 'address', 'institution'], 'xss_clean']
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
            'event_category_id' => 'Event Category ID',
            'name'              => 'Name',
            'country'           => 'Country',
            'city'              => 'City',
            'address'           => 'Address',
            'institution'       => 'Institution',
            'description'       => 'Description',
            'image'             => 'Image',
            'start_date'        => 'Start Date',
            'end_date'          => 'End Date',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_public'         => 'Is Public',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_id( $event_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"            => $event_id,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Get_list_where_is_enabled_and_public( $limit = null )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "is_enabled"    => 1,
                    "is_public"     => 1,
                    "is_deleted"    => 0,
                )
            )
            ->limit( $limit )
            ->all();
    }

    public static function Get_by_item_and_user_id( $event_id, $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"            => $event_id,
                    "user_id"       => $user_id,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Count_where_is_enabled_and_public()
    {
        return self::find()
            ->andOnCondition(
                array(
                    "is_enabled"    => 1,
                    "is_public"     => 1,
                    "is_deleted"    => 0,
                )
            )
            ->count();
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
}
