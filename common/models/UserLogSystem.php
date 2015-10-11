<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "UserLogSystem".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $type
 * @property string $ts
 * @property string $message
 */
class UserLogSystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserLogSystem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'type'], 'integer'],
            [['ts'], 'safe'],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'รหัส',
            'userId' => 'ผู้รับผิดชอบ',
            'type' => 'ประเภท',
            'ts' => 'เวลา',
            'message' => 'ข้อความ',
        ];
    }
}
