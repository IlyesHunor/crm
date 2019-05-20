<?php

namespace app\modules\Theses\models;

use Yii;

/**
 * This is the model class for table "theses".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_id
 * @property string $department_id
 * @property string $student_id
 * @property string $company_id
 * @property string $name
 * @property string $description
 * @property string $file
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class ThesesModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'theses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'user_id', 'department_id', 'name'], 'required'],
            [['added_user_id', 'modified_user_id', 'user_id', 'department_id', 'student_id', 'company_id'], 'integer'],
            [['description', 'file'], 'string'],
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
            'user_id'           => 'User ID',
            'department_id'     => 'Department ID',
            'student_id'        => 'Student ID',
            'company_id'        => 'Company ID',
            'name'              => 'Name',
            'description'       => 'Description',
            'file'              => 'File',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_and_user_id( $thesis_id, $user_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"        => $thesis_id,
                    "user_id"   => $user_id,
                    "is_deleted"=> 0,
                )
            )
            ->one();
    }
}
