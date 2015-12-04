<?php

namespace common\models;
use common\models\Content;
use Yii;

/**
 * This is the model class for table "Typelist".
 *
 * @property integer $id
 * @property string $pondId
 * @property string $foodNo
 * @property integer $age
 * @property string $foodNum
 * @property string $numberOf
 * @property string $createBy
 * @property string $foodTime
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
        return 'food';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pondId','foodNo','age','foodNum','numberOf','createBy','lastUpdateBy'], 'integer'],
            [['foodTime','createTime','lastUpdateTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pondId' => 'pondId',
        	'foodNo' => 'foodNo',
        	'age' => 'age',	
            'foodNum' => 'foodNum',
            'numberOf' => 'numberOf',
        	'foodTime' => 'foodTime',
        	'createBy' => 'createBy',
            'createTime' => 'Create Time',
        	'lastUpdateTime' => 'lastUpdateTime',
        	'lastUpdateBy' => 'lastUpdateBy',	
        ];
    }
    
    
}
