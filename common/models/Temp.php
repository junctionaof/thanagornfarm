<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "temp".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $tempNo
 * @property string $age
 * @property integer $tempNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $tempTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Temp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'tempNo',  'createBy', 'lastUpdateBy'], 'integer'],
        	[['tempNum', 'numberOf'], 'number'],
            [['tempTime', 'createTime', 'lastUpdateTime'], 'safe'],
            [['age', 'name'], 'string', 'max' => 255]
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
            'tempNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'tempNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'tempTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
