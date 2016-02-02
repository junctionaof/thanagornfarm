<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "oxygen".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $oxygenNo
 * @property string $age
 * @property integer $oxygenNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $oxygenTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Oxygen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oxygen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'oxygenNo','numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['oxygenNum'], 'number'],
            [['oxygenTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'oxygenNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'oxygenNum' => 'ปริมาณอาหาร',
            'numberOf' => 'เบอร์ของอาหาร',
            'createBy' => 'สร้างโดย',
            'oxygenTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
