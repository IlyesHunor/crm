<?php

namespace app\modules\Events\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "event_categories".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $name
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class EventCategoriesModel extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'modified_user_id'], 'integer'],
            [['insert_date', 'modify_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['is_enabled', 'is_deleted'], 'string', 'max' => 4],
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
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_id( $event_category_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"            => $event_category_id,
                    "is_deleted"    => 0,
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
}
