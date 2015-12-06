<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alkalinity".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $alkalinityNo
 * @property string $age
 * @property integer $alkalinityNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $foodTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Alkalinity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alkalinity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'alkalinityNo', 'alkalinityNum', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
            [['alkalinityTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'alkalinityNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'alkalinityNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'foodTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
