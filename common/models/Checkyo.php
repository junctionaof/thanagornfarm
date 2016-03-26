<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "checkyo".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $checkyoNo
 * @property string $age
 * @property integer $checkyoNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $checkyoTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 * @property string $yo01
 * @property string $yo02
 * @property string $yo03
 * @property string $yo04
 */
class Checkyo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checkyo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'checkyoNo', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['checkyoNum','numberOf'], 'number'],
            [['checkyoTime', 'createTime', 'lastUpdateTime'], 'safe'],
            [['age', 'name','yo01','yo02','yo03','yo04'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pondId' => 'รหัสรุ่น',
            'checkyoNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'checkyoNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'checkyoTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        	'yo01' => 'yo01',
        	'yo02' => 'yo02',
        	'yo03' => 'yo03',
        	'yo04' => 'yo04',
        ];
    }
    
    const MEAL_one = 1;
    const MEAL_two = 2;
    const MEAL_tree = 3;
    const MEAL_four = 4;
    const MEAL_five = 5;
    const MEAL_six = 6;
    
    public static $arrMeal = [
    		self::MEAL_one => 'มื้อที่ 1',
    		self::MEAL_two => 'มื้อที่ 2',
    		self::MEAL_tree => 'มื้อที่ 3',
    		self::MEAL_four => 'มื้อที่ 4',
    		self::MEAL_five => 'มื้อที่ 5',
    		self::MEAL_six => 'มื้อที่ 6',
    ];
    
    const CHECK_one = 1;
    const CHECK_two = 2;
    const CHECK_tree = 3;
    const CHECK_four = 4;
    const CHECK_five = 5;
    
    public static $arrCheck= [
    		self::CHECK_one => 'หมด',
    		self::CHECK_two => 'เหลือ  1/4',
    		self::CHECK_tree => 'เหลือ  2/4',
    		self::CHECK_four => 'เหลือ  4/4',
    		self::CHECK_five => 'เหลือ  4/4',
    ];
    
}
