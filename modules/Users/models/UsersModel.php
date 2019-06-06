<?php

namespace app\modules\Users\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property string $id
 * @property string $added_user_id
 * @property string $modified_user_id
 * @property string $user_type_id
 * @property string $year_id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $auth_key
 * @property string $insert_date
 * @property string $modify_date
 * @property int $is_student
 * @property int $is_enabled
 * @property int $is_deleted
 */
class UsersModel extends ActiveRecord implements IdentityInterface
{
    public $year_details;
    public $department_details;
    public $practice_details;
    public $practice_assn;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added_user_id', 'modified_user_id', 'user_type_id', 'is_enabled', 'is_deleted'], 'integer'],
            [['password'], 'required'],
            [['insert_date', 'modify_date'], 'safe'],
            [['email', 'password', 'first_name', 'last_name', 'auth_key'], 'string', 'max' => 255],
            [['email'], 'email'],
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
            'user_type_id'      => 'User Type ID',
            'email'             => 'Email',
            'password'          => 'Password',
            'first_name'        => 'First Name',
            'last_name'         => 'Last Name',
            'auth_key'          => 'Auth Key',
            'insert_date'       => 'Insert Date',
            'modify_date'       => 'Modify Date',
            'is_enabled'        => 'Is Enabled',
            'is_deleted'        => 'Is Deleted',
        ];
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey( $authKey )
    {
        $this->auth_key === $this->auth_key;
    }

    public static function findIdentity($id)
    {
        return self::findOne( $id );
    }

    public static function findIdentityByAccessToken( $token, $type = null )
    {
        throw new NotSupportedException();
    }

    public function validatePassword( $password )
    {
        return $this->password === md5( $password );
    }

    public static function Get_by_email( $email )
    {
        return self::find()
            ->andOnCondition(
                array(
                    "email"         => $email,
                    "is_enabled"    => 1,
                    "is_deleted"    => 0
                )
            )
            ->one();
    }

    public static function Get_by_item_id( $user_id )
    {
        return self::find()
        ->andOnCondition(
            array(
                "id"        => $user_id,
                "is_enabled"=> 1,
                "is_deleted"=> 0
            )
        )
        ->one();
    }

    public static function Get_by_item_id_for_admin( $user_id )
    {
        return self::find()
        ->andOnCondition(
            array(
                "id"        => $user_id,
                "is_deleted"=> 0
            )
        )
        ->one();
    }

    public static function Get_list_by_year_id( $year_id )
    {
        return self::find()
        ->andOnCondition(
            array(
                "year_id"       => $year_id,
                "is_student"    => 1,
                "is_enabled"    => 1,
                "is_deleted"    => 0
            )
        )
        ->all();
    }

    public static function Get_list_by_user_type_id( $user_type_id )
    {
        return self::find()
        ->andOnCondition(
            array(
                "user_type_id"  => $user_type_id,
                "is_deleted"    => 0
            )
        )
        ->all();
    }

    public static function Get_head_of_department()
    {
        return self::find()
            ->alias( "u" )
            ->andOnCondition(
                array(
                    "u.is_enabled"  => 1,
                    "u.is_deleted"  => 0,
                    "ut.name"       => "Head_of_department",
                )
            )
            ->leftJoin( "user_types ut", "u.user_type_id = ut.id" )
            ->one();
    }
}
