<?php

namespace common\models;
use common\models\Content;
use Yii;

/**
 * This is the model class for table "Typelist".
 *
 * @property integer $id
 * @property string $name
 * @property string $pondId
 * @property string $foodNo
 * @property integer $age
 * @property string $foodNum
 * @property string $numberOf
 * @property string $createBy
 * @property string $foodTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'food';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId','foodNo','createBy','lastUpdateBy'], 'integer'],
        	[['foodNum','numberOf'], 'number'],
            [['foodTime','createTime','lastUpdateTime'], 'safe'],
        	[['name','age'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        	'name' => 'Name',
            'pondId' => 'pondId',
        	'foodNo' => 'เบอร์อาหาร',
        	'age' => 'อายุลูกกุ้ง',	
            'foodNum' => 'ปริมาณอาหารที่ให้',
            'numberOf' => 'มื้อที่',
        	'foodTime' => 'วันที่ให้อาหาร',
        	'createBy' => 'createBy',
            'createTime' => 'Create Time',
        	'lastUpdateTime' => 'lastUpdateTime',
        	'lastUpdateBy' => 'lastUpdateBy',	
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
    
    const NUMBER_zero = 0;
    const NUMBER_one = 1;
    const NUMBER_two = 2;
    const NUMBER_tree = 3;
    const NUMBER_treeS = 31;
    const NUMBER_treeP = 32;
    const NUMBER_four = 4;
    const NUMBER_fourS = 41;
    const NUMBER_fourP = 42;
    const NUMBER_five = 5;
    
    public static $arrNumberOf = [
    		self::NUMBER_zero => 'เบอร์ 0',
    		self::NUMBER_one => 'เบอร์  1',
    		self::NUMBER_two => 'เบอร์  2',
    		self::NUMBER_tree => 'เบอร์  3',
    		self::NUMBER_treeS => 'เบอร์  3s',
    		self::NUMBER_treeP => 'เบอร์  3p',
    		self::NUMBER_four => 'เบอร์  4',
    		self::NUMBER_fourS => 'เบอร์  4s',
    		self::NUMBER_fourP => 'เบอร์  4p',
    		self::NUMBER_five => 'เบอร์  5',
    ];
    
    
}
