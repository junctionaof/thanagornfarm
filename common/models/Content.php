<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use app\Entity;
use app\Workflow;

use common\models\Media;
/**
 * This is the model class for table "Content".
 *
 * @property string $id
 * @property string $title
 * @property integer $type
 * @property string $categoryId
 * @property string $tpbsId
 * @property integer $status
 * @property integer $version
 * @property string $abstract
 * @property string $content
 * @property string $tags
 * @property string $credit
 * @property integer $published
 * @property double $latitude
 * @property double $longitude
 * @property string $createBy
 * @property string $createTime
 * @property string $lastUpdateBy
 * @property string $lastUpdateTime
 * @property string $publishTime
 * @property string $expireTime
 * @property integer $previewEntity
 * @property string $previewRefId
 * @property integer $viewCount
 * @property integer $hasComment
 * @property integer $hasGallery
 * @property integer $hasVideo
 * @property string $comment
 * @property string $props
 *
 * @property ContentRef[] $contentRefs
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'categoryId', 'status', 'version', 'content', 'createBy', 'createTime'], 'required'],
            [['type', 'categoryId', 'tpbsId', 'status', 'version', 'published', 'createBy', 'lastUpdateBy', 'previewEntity', 'hasComment', 'hasGallery', 'hasVideo', 'previewRefId', 'viewCount'], 'integer'],
            [['abstract', 'content', 'tags', 'comment', 'props'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['createTime', 'lastUpdateTime', 'publishTime', 'expireTime'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['credit'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'รหัส',
            'title' => 'หัวเรื่อง',
            'type' => 'ประเภท',
            'categoryId' => 'หมวดหมู่',
            'tpbsId' => 'รหัส TPBSFocus',
            'status' => 'สถานะ',
            'version' => 'เวอร์ชัน',
            'abstract' => 'โปรย',
            'content' => 'เนื้อหา',
            'tags' => 'แท็ก',
            'credit' => 'เครดิต',
            'published' => 'Published',
            'latitude' => 'ละติจูด',
            'longitude' => 'ลองติจูด',
            'createBy' => 'ผู้สร้าง',
            'createTime' => 'เวลาที่สร้าง',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'publishTime' => 'เวลาเปิดแสดงผล',
            'expireTime' => 'ตั้งเวลาปิดการแสดงผล',
            'previewEntity' => 'ชนิดของ preview',
            'previewRefId' => 'รหัสของ preview',
            'viewCount' => 'จำนวนคนดู',
            'hasComment' => 'เปิด/ปิด comment',
            'hasGallery' => 'มีอัลบั้มรูปประกอบ',
            'hasVideo' => 'มีวีดีโอประกอบ',
            'comment' => 'หมายเหตุการแก้ไข',
            'props' => 'ข้อมูลอื่นๆ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentRefs()
    {
        return $this->hasMany(ContentRef::className(), ['contentId' => 'id']);
    }
    
 	public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createBy']);
    }
    
    public function getLastUpdateBy()
    {
    	return $this->hasOne(User::className(), ['id' => 'lastUpdateBy']);
    }
    
    const RELATIONTYPE_GENERAL = 1;
    
    const TYPE_ONLINE_NEWS = 1;
    const TYPE_ARTICLE = 2;
    const TYPE_CELEB = 3;
    const TYPE_SCOOP = 4;
    const TYPE_PR = 5;
    const TYPE_ADVERTORIAL = 6;
    const TYPE_COMPONENT = 7;
    const TYPE_SPECIAL_REPORT = 8;
    
    public static $arrType = array(self::TYPE_ONLINE_NEWS=> 'ข่าวออนไลน์',
    		self::TYPE_ARTICLE=>'คอลัมน์',
    		self::TYPE_CELEB=>'คนดังนั่งเขียน',
    		self::TYPE_SCOOP=>'สกู๊ป',
    		self::TYPE_PR=>'ข่าว PR',
    		self::TYPE_ADVERTORIAL=>'Advertorial',
    		self::TYPE_COMPONENT => 'ข่าวประกอบ',
    );
    
    public static $arrTypeTpbs = array(self::TYPE_ONLINE_NEWS=> 'ข่าว',
    		self::TYPE_SPECIAL_REPORT=>'รายงานพิเศษ',
    		self::TYPE_PR=>'ข่าว PR',	
    );
    
    public function getPreview($options = array()) {
    	
    	if (empty($this->previewEntity)) {
    
    		$model = new Media();
    		$media = $model->findByParams(array(
    				Media::ENCODE_ENTITY => Entity::TYPE_CONTENT,
    				Media::ENCODE_ID => $this->id,
    				Media::ENCODE_ITEMNO => $this->previewRefId,
    		));
    	
    		if ($media == null){
    			return null;
    		}

            $imgClass = ['lazyload', 'img-responsive'];

            if(isset($options['class'])){
                if(is_array($options['class'])){
                    // merge array
                    $imgClass = yii\helpers\ArrayHelper::merge($imgClass, $options['class']);
                }else{
                    $imgClass[] = $options['class'];
                }
            }
    
            $imgTag = Html::img(\Yii::getAlias('@web') . '/media/' . $media->getPublishUri($options), array('alt'=> Html::encode($this->title), 'data-src'=>\Yii::getAlias('@web') . '/media/' . $media->getPublishUri($options), 'class'=>implode(' ', $imgClass)));
    
    		return $imgTag;
    
    	}
    	else {
    		$model = Entity::getInstance($this->previewEntity, $this->previewRefId);
    		if ($model != null)
    			return $model->getPreview($options);
    	}
    }
    
    public function getPreviewUrl($options = array()) {
    	 
    	if (empty($this->previewEntity)) {
    
    		$model = new Media();
    		$media = $model->findByParams(array(
    				Media::ENCODE_ENTITY => Entity::TYPE_CONTENT,
    				Media::ENCODE_ID => $this->id,
    				Media::ENCODE_ITEMNO => $this->previewRefId,
    		));
    		 
    		if ($media == null){
    			return null;
    		}
    
    		return \Yii::getAlias('@web') . '/media/' . $media->getPublishUri($options);
    

    	}
    	else {
    		$model = Entity::getInstance($this->previewEntity, $this->previewRefId);
    		if ($model != null)
    			return $model->getPreview($options);
    	}
    }
    public function getTags(){
        if (!empty($this->tags)) {
            $txt = $this->tags;
            $txt = '##@' . $txt . '##@';
            $txt = str_replace(array(', ,', ',,'), ',', $txt);
            $txt = str_replace(array('##@,', ',##@'), '', $txt);
            return $txt;
    	}
    	else {
            return $this->tags;
    	}
    }
}
