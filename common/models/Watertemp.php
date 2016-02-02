<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "watertemp".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $watertempNo
 * @property string $age
 * @property integer $watertempNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $watertempTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Watertemp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'watertemp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'watertempNo', 'watertempNum', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['watertempNum'], 'number'],
            [['watertempTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'watertempNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'watertempNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'watertempTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
