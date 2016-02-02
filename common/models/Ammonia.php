<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ammonia".
 *
 * @property integer $id
 * @property integer $pondId
 * @property integer $ammoniaNo
 * @property string $age
 * @property integer $ammoniaNum
 * @property integer $numberOf
 * @property integer $createBy
 * @property string $ammoniaTime
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 * @property string $name
 */
class Ammonia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ammonia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId', 'ammoniaNo','numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
        	[['ammoniaNum'], 'number'],
            [['ammoniaTime', 'createTime', 'lastUpdateTime'], 'safe'],
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
            'ammoniaNo' => 'มื้อที่',
            'age' => 'อายุลูกกุ้ง',
            'ammoniaNum' => 'ปริมาณ ammonia',
            'numberOf' => 'เบอร์ของ ammonia',
            'createBy' => 'สร้างโดย',
            'ammoniaTime' => 'วันที่วัดค่า',
            'createTime' => 'สร้างเมื่อ',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'name' => 'Name',
        ];
    }
}
