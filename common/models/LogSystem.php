<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "LogSystem".
 *
 * @property integer $id
 * @property integer $entityType
 * @property integer $refId
 * @property string $categories
 * @property string $status
 * @property string $ts
 * @property integer $userId
 * @property string $message
 */
class LogSystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'LogSystem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityType', 'refId', 'userId'], 'integer'],
            [['ts'], 'safe'],
            [['message'], 'string'],
            [['categories'], 'string', 'max' => 155],
            [['status'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'รหัส',
            'entityType' => 'ประเภท',
            'refId' => 'รหัสอ้างอิง',
            'categories' => 'หมวดหมู่',
            'status' => 'สถานะ',
            'ts' => 'เวลา',
            'userId' => 'ผู้รับผิดชอบ',
            'message' => 'ข้อความ',
        ];
    }
}
