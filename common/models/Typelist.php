<?php

namespace common\models;
use common\models\Content;
use Yii;

/**
 * This is the model class for table "Typelist".
 *
 * @property integer $id
 * @property string $name
 * @property string $size
 * @property integer $type
 * @property string $status
 * @property integer $createBy
 * @property string $createTime
 * @property string $keeper
 */
class Typelist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Typelist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	
        return [
            [['createBy','lastUpdateBy','type'], 'integer'],
        	[['size'], 'string', 'max' => 100],
            [['createTime','lastUpdateTime'], 'safe'],
            [['name'], 'string', 'max' => 100],
        	[['keeper'], 'string', 'max' => 100]

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
        	'keeper' => 'Keeper',
        	'type' => 'Type',	
            'status' => 'Status',
            'createBy' => 'Create By',
            'createTime' => 'Create Time',
        ];
    }
    
    
}
