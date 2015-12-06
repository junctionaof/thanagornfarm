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
            [['pondId', 'checkyoNo', 'checkyoNum', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
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
}
