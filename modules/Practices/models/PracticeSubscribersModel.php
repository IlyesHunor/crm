<?php

namespace app\modules\Practices\models;

use Yii;

/**
 * This is the model class for table "practice_subscribers".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $practice_id
 * @property string $user_id
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_enabled
 * @property int $is_deleted
 */
class PracticeSubscribersModel extends \yii\db\ActiveRecord
{
    public $practice_name;
    public $first_name;
    public $last_name;
    public $practice_assn;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practice_subscribers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id'], 'required'],
            [['added_user_id', 'modified_user_id', 'practice_id', 'user_id'], 'integer'],
            [['insert_date', 'modify_date'], 'safe'],
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
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public static function Get_by_user_and_practice_id( $user_id , $practice_id )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "user_id"       => $user_id,
                    "practice_id"   => $practice_id,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0,
                )
            )
            ->one();
    }

    public static function Get_list_by_user_id( $user_id )
    {
        return self::find()
            ->alias( "ps" )
            ->andOnCondition(
                array(
                    "ps.user_id"       => $user_id,
                    "ps.is_enabled"    => 1,
                    "ps.is_deleted"    => 0,
                )
            )
            ->select( "ps.*, p.name as practice_name" )
            ->leftJoin( "practices p", "p.id = ps.practice_id" )
            ->all();
    }

    public static function Get_list_by_practice_id( $practice_id )
    {
        return self::find()
            ->alias( "ps" )
            ->andOnCondition(
                array(
                    "ps.practice_id"=> $practice_id,
                    "ps.is_enabled" => 1,
                    "ps.is_deleted" => 0,
                )
            )
            ->select( "ps.*, u.first_name as first_name, u.last_name as last_name" )
            ->leftJoin( "users u", "u.id = ps.user_id" )
            ->all();
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
}
