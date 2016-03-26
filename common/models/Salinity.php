<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "salinity".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $salinityNo
 * @property string $age
 * @property integer $salinityNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $salinityTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Salinity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salinity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'salinityNo', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['salinityNum', 'numberOf'], 'number'],
            [['salinityTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'salinityNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'salinityNum' => 'ปริมาณ salinity',
            'numberOf' => 'เบอร์ของ salinity',
            'createBy' => 'สร้างโดย',
            'salinityTime' => 'วันที่วัดค่า',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
