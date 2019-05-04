<?php

namespace app\modules\Practices\models;

use Yii;

/**
 * This is the model class for table "contract_templates".
 *
 * @property int $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $name
 * @property string $text
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class ContractTemplatesModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'added_user_id', 'name', 'text'], 'required'],
            [['id', 'added_user_id', 'modified_user_id'], 'integer'],
            [['text'], 'string'],
            [['insert_date', 'modify_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['is_enabled', 'is_deleted'], 'string', 'max' => 1],
            [['id'], 'unique'],
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
            'name' => 'Name',
            'text' => 'Text',
            'insert_date' => 'Insert Date',
            'modify_date' => 'Modify Date',
            'is_enabled' => 'Is Enabled',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public static function Get_list()
    {
        return self::find()
            ->andOnCondition(
                array(
                    "is_deleted"=> 0,
                )
            )
            ->all();
    }

    public static function Get_by_item_id( $template_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "id"            => $template_id,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }
}
