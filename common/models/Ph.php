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
 * @property double $phNum
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
            [['pondId', 'phNo', 'numberOf', 'createBy', 'lastUpdateBy'], 'integer'],
            [['phNum'], 'number'],
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
            'pondId' => 'Pond ID',
            'phNo' => 'Ph No',
            'age' => 'Age',
            'phNum' => 'Ph Num',
            'numberOf' => 'Number Of',
            'createBy' => 'Create By',
            'phTime' => 'Ph Time',
            'createTime' => 'Create Time',
            'lastUpdateTime' => 'Last Update Time',
            'lastUpdateBy' => 'Last Update By',
            'name' => 'Name',
        ];
    }
}
