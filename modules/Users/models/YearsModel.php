<?php

namespace app\modules\Users\models;

use Yii;

/**
 * This is the model class for table "years".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $department_id
 * @property string $code
 * @property string $name
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class YearsModel extends \yii\db\ActiveRecord
{
    public $students;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'years';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'code', 'name'], 'required'],
            [['added_user_id', 'modified_user_id', 'department_id'], 'integer'],
            [['insert_date', 'modify_date'], 'safe'],
            [['code', 'name'], 'string', 'max' => 255],
            [['is_enabled', 'is_deleted'], 'string', 'max' => 1],
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
            'department_id' => 'Department ID',
            'code' => 'Code',
            'name' => 'Name',
            'insert_date' => 'Insert Date',
            'modify_date' => 'Modify Date',
            'is_enabled' => 'Is Enabled',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public static function Get_by_item_id( $year_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"        => $year_id,
                    "is_enabled"=> 1,
                    "is_deleted"=> 0
                )
            )
            ->one();
    }

    public static function Get_list_by_department_id( $deparmtent )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "department_id" => $deparmtent,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0
                )
            )
            ->all();
    }
}
