<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "waterchange".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $waterchangeNo
 * @property string $age
 * @property integer $ waterchangeNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $waterchangeTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Waterchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'waterchange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'waterchangeNo', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['waterchangeNum', 'numberOf'], 'number'],
            [['waterchangeTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'waterchangeNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'waterchangeNum' => 'ปริมาณ waterchange',
            'numberOf' => 'เบอร์ของ waterchange',
            'createBy' => 'สร้างโดย',
            'waterchangeTime' => 'วันที่วัดค่า',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
