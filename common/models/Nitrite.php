<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "nitrite".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $nitriteNo
 * @property string $age
 * @property integer $nitriteNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $nitriteTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Nitrite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nitrite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'nitriteNo', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['nitriteNum', 'numberOf'], 'number'],
            [['nitriteTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'nitriteNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'nitriteNum' => 'ปริมาณ nitrite',
            'numberOf' => 'เบอร์ของ nitrite',
            'createBy' => 'สร้างโดย',
            'nitriteTime' => 'วันที่วัดค่า',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
