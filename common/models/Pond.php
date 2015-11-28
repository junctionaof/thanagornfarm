<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use app\Entity;
use app\Workflow;

use common\models\Media;
/**
 * This is the model class for table "Pond".
 *
 * @property string $id
 * @property string $title
 * @property integer $type
 * @property string $categoryId
 * @property string $tpbsId
 * @property integer $status
 * @property string $abstract
 * @property string $content
 * @property string $tags
 * @property integer $published
 * @property string $createBy
 * @property string $createTime
 * @property string $lastUpdateBy
 * @property string $lastUpdateTime
 * @property string $publishTime
 * @property integer $previewEntity
 * @property string $comment
 * @property string $props
 *
 * @property ContentRef[] $contentRefs
 */
class Pond extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Pond';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'categoryId', 'createBy', 'createTime'], 'required'],
            [['type', 'categoryId', 'status', 'version', 'published', 'createBy', 'lastUpdateBy',], 'integer'],
            [['abstract', 'content', 'tags', 'comment'], 'string'],
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
            'status' => 'สถานะ',
            'abstract' => 'โปรย',
            'content' => 'เนื้อหา',
            'tags' => 'แท็ก',
            'published' => 'Published',
            'createBy' => 'ผู้สร้าง',
            'createTime' => 'เวลาที่สร้าง',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'publishTime' => 'เวลาเปิดแสดงผล',
            'comment' => 'หมายเหตุการแก้ไข',
            'props' => 'ข้อมูลอื่นๆ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
 	public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createBy']);
    }
    
    public function getLastUpdateBy()
    {
    	return $this->hasOne(User::className(), ['id' => 'lastUpdateBy']);
    }

}
