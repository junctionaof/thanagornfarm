<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $type
 * @property string $firstName
 * @property string $lastName
 * @property string $nickName
 * @property integer $status
 * @property string $position
 * @property string $section
 * @property string $phone
 * @property string $email
 * @property string $bankAccount
 * @property string $address
 * @property string $provinceId
 * @property integer $createBy
 * @property string $createTime
 * @property integer $previewEntity
 * @property integer $previewRefId
 * @property string $lastUpdateTime
 * @property integer $lastUpdateBy
 */
class User extends ActiveRecord implements IdentityInterface
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 10;
	const ROLE_USER = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'firstName'], 'required'],
            [['type', 'status', 'position', 'section', 'provinceId', 'createBy', 'lastUpdateBy'], 'integer'],
            [['address'], 'string'],
            [['createTime', 'lastUpdateTime'], 'safe'],
            [['username'], 'string', 'max' => 30],
            [['password', 'auth_key'], 'string', 'max' => 36],
            [['password_hash'], 'string', 'max' => 100],
            [['firstName', 'lastName', 'nickName', 'phone', 'email'], 'string', 'max' => 45],
            [['bankAccount'], 'string', 'max' => 20],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'รหัสผู้ใช้',
            'username' => 'ชื่อสำหรับ login',
            'password' => 'รหัสผ่าน',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'type' => 'Type',
            'firstName' => 'ชื่อ',
            'lastName' => 'นามสกุล',
            'nickName' => 'ชื่อเล่น',
            'status' => 'สถานะ',
            'position' => 'ตำแหน่ง',
            'section' => 'สังกัด',
            'phone' => 'Phone',
            'email' => 'Email',
            'bankAccount' => 'Bank Account',
            'address' => 'Address',
            'provinceId' => 'Province ID',
            'createBy' => 'Create By',
            'createTime' => 'Create Time',
            'lastUpdateTime' => 'Last Update Time',
            'lastUpdateBy' => 'Last Update By',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
    	try {
    		$identity = static::findOne(['username' => str_replace('u:', '', $id), 'status' => self::STATUS_ACTIVE]);
    	}
    	catch(\Exception $ex) {
    		$identity = null;
    	}
    	 
    	return $identity;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
    	$expire = Yii::$app->params['user.passwordResetTokenExpire'];
    	$parts = explode('_', $token);
    	$timestamp = (int) end($parts);
    	if ($timestamp + $expire < time()) {
    		// token expired
    		return null;
    	}
    
    	return static::findOne([
    			'password_reset_token' => $token,
    			'status' => self::STATUS_ACTIVE,
    			]);
    }
    
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    /* public static function isPasswordResetTokenValid($token)
     {
    if (empty($token)) {
    return false;
    }
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    $parts = explode('_', $token);
    $timestamp = (int) end($parts);
    return $timestamp + $expire >= time();
    } */
    
    /**
    * @inheritdoc
    */
    public function getId()
    {
    return 'u:' . (string)$this->username;
    }
    
    /**
    * @inheritdoc
    	*/
    	public function getAuthKey()
    	{
    	return $this->auth_key;
    }
    
    /**
    * @inheritdoc
    */
    	public function validateAuthKey($authKey)
    		{
    		return $this->getAuthKey() === $authKey;
    }
	
	/**
	 * Validates password
	 *
	 * @param string $password
	 *        	password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return Yii::$app->security->validatePassword ( $password, $this->password_hash );
	}
	
	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password        	
	 */
	public function setPassword($password) {
		$this->password_hash = Yii::$app->security->generatePasswordHash ( $password );
	}
	
	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey() {
		$this->auth_key = Yii::$app->security->generateRandomString ();
	}
	
	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken() {
		$this->password_reset_token = Yii::$app->security->generateRandomString () . '_' . time ();
	}
	
	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken() {
		$this->password_reset_token = null;
	}    
	
	public static function getPeremission($username){
		$query = AuthAssignment::find();
    	$query->where(['userid' => $username]);
    	$model = $query->one();
    	return $model;
	}
	
	const position_admin = 1;
	const position_editor = 2;
	const position_journalist = 3;
	
	public static $arrPosition = array(
			self::position_admin => 'ผู้ดูแลระบบเว็บ',
			self::position_editor => 'ผู้ดูแลฟาร์ม',
			self::position_journalist => 'พนักงานกรอกข้อมูล',
	);
	
	public static $arrStatus = array(
			self::STATUS_ACTIVE => 'ยืนยัน',
			self::STATUS_DELETED => 'ลบ',
	);	
	
}
