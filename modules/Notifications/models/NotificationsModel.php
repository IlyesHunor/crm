<?php

namespace app\modules\Notifications\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_id
 * @property string $name
 * @property string $title
 * @property string $link
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_viewed
 * @property int $is_deleted
 */
class NotificationsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'user_id', 'name', 'link'], 'required'],
            [['added_user_id', 'modified_user_id', 'user_id'], 'integer'],
            [['title'], 'string'],
            [['insert_date', 'modify_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 512],
            [['is_viewed', 'is_deleted'], 'string', 'max' => 4],
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
            'name'              => 'Name',
            'title'             => 'Title',
            'link'              => 'Link',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_viewed'         => 'Is Viewed',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_id( $notification_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"        => $notification_id,
                    "is_deleted"=> 0
                )
            )
            ->one();
    }

    public static function Count_where_is_unreaded_by_user_id( $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "user_id"   => $user_id,
                    "is_viewed" => 0,
                    "is_deleted"=> 0,
                )
            )
            ->count();
    }

    public static function Get_notifications_list_by_user_id( $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "user_id"   => $user_id,
                    "is_deleted"=> 0,
                )
            )
            ->limit( 5 )
            ->all();
    }
}
