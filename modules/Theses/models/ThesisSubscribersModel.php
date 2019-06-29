<?php

namespace app\modules\Theses\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "thesis_subscribers".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $thesis_id
 * @property string $user_id
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_accepted
 * @property int $is_enabled
 * @property int $is_deleted
 */
class ThesisSubscribersModel extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'thesis_subscribers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'thesis_id', 'user_id'], 'required'],
            [['added_user_id', 'modified_user_id', 'thesis_id', 'user_id'], 'integer'],
            [['insert_date', 'modify_date'], 'safe'],
            [['is_accepted', 'is_enabled', 'is_deleted'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'added_user_id' => 'Added User ID',
            'modified_user_id' => 'Modified User ID',
            'thesis_id' => 'Thesis ID',
            'user_id' => 'User ID',
            'insert_date' => 'Insert Date',
            'modify_date' => 'Modify Date',
            'is_accepted' => 'Is Accepted',
            'is_enabled' => 'Is Enabled',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public static function Get_by_thesis_id_and_user_id( $thesis_id, $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "thesis_id" => $thesis_id,
                    "user_id"   => $user_id,
                    "is_deleted"=> 0,
                )
            )
            ->one();
    }

    public static function Get_list_by_thesis_id( $thesis_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "thesis_id" => $thesis_id,
                    "is_deleted"=> 0,
                )
            )
            ->all();
    }
}
