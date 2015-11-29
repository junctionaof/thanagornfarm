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
 * @property string $tpbsId
 * @property integer $status
 * @property string $abstract
 * @property string $pond
 * @property string $createBy
 * @property string $createTime
 * @property string $lastUpdateBy
 * @property string $lastUpdateTime
 * @property integer $previewEntity
 * @property string $comment
 * @property string $props
 * @property string $larvae
 * @property integer $larvaeType
 * @property integer $larvaePrice
 * @property string $larvaeCompany
 * @property string $releaseTime
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
            [['title', 'type', 'createBy', 'createTime'], 'required'],
            [['type', 'status','createBy', 'lastUpdateBy','larvae','larvaeType','larvaePrice'], 'integer'],
            [['abstract', 'pond', 'comment','larvaeCompany'], 'string'],
            [['createTime', 'lastUpdateTime','releaseTime'], 'safe'],
            [['title'], 'string', 'max' => 100],
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
            'status' => 'สถานะ',
            'abstract' => 'โปรย',
            'pond' => 'เนื้อหา',
            'createBy' => 'ผู้สร้าง',
            'createTime' => 'เวลาที่สร้าง',
            'lastUpdateBy' => 'ผู้แก้ไขล่าสุด',
            'lastUpdateTime' => 'เวลาแก้ไขล่าสุด',
            'comment' => 'หมายเหตุการแก้ไข',
            'props' => 'ข้อมูลอื่นๆ',
        	'larvaePrice'=>'ราคาลูกกุ้งตัวละ',
        	'larvaeCompany'=>'บริษัทฟาร์มลูกกุ้ง',
        	'larvae'=>'จำนวนลูกกุ้งที่ปล่อยลงบ่อเลี้่ยง ',
        	'larvaeType'=>'ชนิดของลูกกุ้ง',
        	'releaseTime'=>'วันที่ปล่อยลูกกุ้ง'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
 
    const larvae_01 = 1;
    const larvae_02 = 2;
    const larvae_03 = 3;
    
    public static $larvaeType = array(
    		self::larvae_01 => 'ลูกกุ้งชนิดที่ 1',
    		self::larvae_02 => 'ลูกกุ้งชนิดที่ 2',
    		self::larvae_03 => 'ลูกกุ้งชนิดที่  3',
    );
    
 	public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createBy']);
    }
    
    public function getLastUpdateBy()
    {
    	return $this->hasOne(User::className(), ['id' => 'lastUpdateBy']);
    }

}
