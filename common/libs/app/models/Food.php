<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%food}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $size
 * @property integer $type
 * @property integer $status
 * @property integer $createBy
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property string $lastUpdateBy
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%food}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'createBy', 'lastUpdateBy'], 'integer'],
            [['status'], 'required'],
            [['createTime', 'lastUpdateTime'], 'safe'],
            [['name', 'size'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'size' => 'Size',
            'type' => 'ประเภท',
            'status' => 'สถานะ',
            'createBy' => 'Create By',
            'createTime' => 'Create Time',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
        ];
    }
}
