<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

use app\Entity;
use app\Workflow;

use common\models\Media;

/**
 * This is the model class for table "Widget".
 *
 * @property integer $id
 * @property string $title
 * @property string $titleEn
 * @property string $url
 * @property string $description
 * @property integer $categoryId
 * @property string $tags
 * @property integer $status
 * @property string $publishTime
 * @property string $createBy
 * @property string $createTime
 * @property string $lastUpdateBy
 * @property string $lastUpdateTime
 * @property integer $previewEntity
 * @property string $previewRefId
 * @property string $viewCount
 * @property string $props
 */
class Widget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Widget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'titleEn', 'tags', 'status', 'createBy', 'createTime'], 'required'],
            [['url', 'description', 'tags', 'props'], 'string'],
            [['categoryId', 'status', 'createBy', 'lastUpdateBy', 'previewEntity', 'previewRefId', 'viewCount'], 'integer'],
            [['publishTime', 'createTime', 'lastUpdateTime'], 'safe'],
            [['title', 'titleEn'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'titleEn' => 'Title En',
        	'url' => 'Url',
            'description' => 'Description',
            'categoryId' => 'Category ID',
            'tags' => 'Tags',
            'status' => 'Status',
            'publishTime' => 'Publish Time',
            'createBy' => 'ผู้สร้าง',
            'createTime' => 'เวลาที่สร้าง',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'previewEntity' => 'ชนิดของ preview',
            'previewRefId' => 'รหัสของ preview',
            'viewCount' => 'จำนวนคนดู',
            'props' => 'ข้อมูลอื่นๆ',
        ];
    }
    
    public function getPreview($options = array()) {
    	if (empty($this->previewEntity)) {
    
    		$model = new Media();
    		$media = $model->findByParams(array(
    				Media::ENCODE_ENTITY => Entity::TYPE_WIDGET,
    				Media::ENCODE_ID => $this->id,
    				Media::ENCODE_ITEMNO => $this->previewRefId,
    		));
    
    		if ($media == null){
    			return null;
    		}
    
    		$imgTag = Html::img(\Yii::getAlias('@web') . '/media/' . $media->getPublishUri($options), array());
    
    		return $imgTag;
    
    	}
    	else {
    		$model = Entity::getInstance($this->previewEntity, $this->previewRefId);
    		if ($model != null)
    			return $model->getPreview($options);
    	}
    }
}
