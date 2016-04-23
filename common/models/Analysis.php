<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%analysis}}".
 *
 * @property integer $id
 * @property integer $pondId
 * @property string $name
 * @property string $age
 * @property string $pickDate
 * @property double $results
 * @property double $size
 * @property double $density
 * @property double $survivalRate
 * @property double $quantity
 * @property double $fcr
 * @property double $receipts
 * @property double $costShrimp
 * @property double $costFood
 * @property double $costWage
 * @property double $costEnergy
 * @property double $costOther
 * @property double $profits
 * @property double $yields
 * @property string $suggestion
 * @property integer $createBy
 * @property string $analysisTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 */
class Analysis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%analysis}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'createBy', 'lastUpdateBy'], 'integer'],
            [['pickDate', 'analysisTime', 'createTime', 'lastUpdateTime'], 'safe'],
            [['results', 'size', 'density', 'survivalRate', 'quantity', 'fcr', 'receipts', 'costShrimp', 'costFood', 'costWage', 'costEnergy', 'costOther', 'profits', 'yields'], 'number'],
            [['createBy'], 'required'],
            [['name', 'age', 'suggestion'], 'string', 'max' => 255]
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
            'name' => 'ชื่อ บ่อและรุ่น',
            'age' => 'อายุลูกกุ้ง',
            'pickDate' => 'วันที่จับกุ้ง',
            'results' => 'ผลผลิตที่ได้ (กิโลกรัม)',
            'size' => 'ขนาดกุ้งที่จับ (ตัว/กิโลกรัม)',
            'density' => 'ความหนาแน่น (ตัว/ตารางเมตร)',
            'survivalRate' => 'อัตรารอดตาย (%)',
            'quantity' => 'ปริมานอาหารที่ใช้ร่วม (กิโลกรัม)',
            'fcr' => 'อัตราการแลกเนื้อ',
            'receipts' => 'รายรับ',
            'costShrimp' => 'ต้นทุนลูกกุ้ง',
            'costFood' => 'ต้นทุนอาหาร',
            'costWage' => 'ต้นทุนค่าจ้าง',
            'costEnergy' => 'ต้นทุนค่าพลังงาน',
            'costOther' => 'ต้นทุนอื่นๆ',
            'profits' => 'กำไรขั้นต้น',
            'yields' => 'ผลผลิตต่อไร่',
            'suggestion' => 'ข้อเสนอแนะ',
            'createBy' => 'สร้างโดย',
            'analysisTime' => 'วันที่ให้อาหาร',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
        ];
    }
}
