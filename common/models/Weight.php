<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "weight".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $weightNo
 * @property string $age
 * @property integer $weightNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $weightTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Weight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weight';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'weightNo', 'weightNum', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
            [['weightTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'weightNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'weightNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'weightTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
