<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ph".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $phNo
 * @property string $age
 * @property integer $phNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $phTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Ph extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ph';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'phNo', 'phNum', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
            [['phTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'phNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'phNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'phTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
