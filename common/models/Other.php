<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "other".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $otherNo
 * @property string $age
 * @property integer $otherNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $otherTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Other extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'otherNo', 'otherNum', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
            [['otherTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'otherNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'otherNum' => 'ปริมาณ other',
            'numberOf' => 'เบอร์ของ other',
            'createBy' => 'สร้างโดย',
            'otherTime' => 'วันที่วัดค่า',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
