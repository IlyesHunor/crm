<?php

namespace app\modules\Users\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "invitations".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_id
 * @property string $code
 * @property string $expiration_date
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_deleted
 */
class InvitationsModel extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invitations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'user_id', 'code', 'expiration_date'], 'required'],
            [['added_user_id', 'modified_user_id', 'user_id'], 'integer'],
            [['code'], 'string'],
            [['expiration_date', 'insert_date', 'modify_date'], 'safe'],
            [['is_deleted'], 'string', 'max' => 4],
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
            'code'              => 'Code',
            'expiration_date'   => 'Expiration Date',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_item_code( $code )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "code"      => $code,
                    "is_deleted"=> 0
                )
            )
            ->one();
    }
}
