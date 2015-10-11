<?php

namespace common\models;

use Yii;

use app\Entity;
use app\Workflow;
use app\TrEnc;

/**
 * This is the model class for table "Document".
 *
 * @property integer $type
 * @property string $refId
 * @property integer $itemNo
 * @property string $caption
 * @property string $tags
 * @property integer $status
 * @property string $srcPath
 * @property string $mime
 * @property string $fileName
 */
class Document extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Document';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
				[['type', 'refId', 'itemNo', 'status', 'fileName'], 'required'],
				[['type', 'itemNo', 'status'], 'integer'],
				[['tags'], 'string'],
				[['refId'], 'string', 'max' => 15],
				[['caption'], 'string', 'max' => 255],
				[['srcPath', 'mime'], 'string', 'max' => 100],
				[['fileName'], 'string', 'max' => 50]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'type' => 'Type',
				'refId' => 'Ref ID',
				'itemNo' => 'Item No',
				'caption' => 'Caption',
				'tags' => 'Tags',
				'status' => 'Status',
				'srcPath' => 'Src Path',
				'mime' => 'Mime',
				'fileName' => 'File Name',
		];
	}
	
	//custom code
    /**
     * สร้าง record(document) ของ  document ใหม่ จากข้อมูลที่ระบุ
     * @param int $entity
     * @param string $refId
     * @param string $documentPath path ของไฟล์ต้นทาง
     * @param string $mode รูปแบบการ move file [upload, cp]
     * @return void|Document
     */
    public function newItem($entity, $refId, $documentPath, $documentName, $mode = 'upload') {
    	if (!file_exists($documentPath)) return;
    	$model = Entity::getInstance($entity, $refId);
    
    	if ($model == null) return;
    	$itemNo = \yii::$app->db->createCommand("select max(itemNo) from Document
    			where type=:type and refId=:id",
    			['type' => $entity,
    					'id' => $refId])->queryScalar();
    
    			$itemNo++;
    			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
    			$mime = finfo_file($finfo, $documentPath);
    			finfo_close($finfo);
    			
    			$document = new Document();
    			$document->type = $entity;
    			$document->refId = $refId;
    			$document->itemNo = $itemNo;
    			$document->status = Workflow::STATUS_PUBLISHED;
    			$document->mime = $mime;
    			$document->fileName = $documentName;
    			
    			$arr = explode(".", $documentName);
    			$extension = $arr[count($arr) - 1];
    			$document->srcPath = date('Y/m/d', strtotime($model->createTime)) . "/{$refId}_{$itemNo}.$extension";
    
    			$retries = 0;
    			do {
    				try {
    					if ($document->save()) break;
    				}
    				catch(Exception $ex) {
    					$document->itemNo++;
    					$document->srcPath = date('Y/m/d', strtotime($model->createTime)) . "/{$refId}_{$document->itemNo}.$extension";
    					$retries++;
    					usleep(1000);
    				}
    				
    			}while($retries < 10);
    
    			if ($retries >= 10)
    				$document = null;
    
    			if (!empty($document)) {
    				$destination =  $document->getFullPath();
    
    				if (!is_readable(dirname($destination))) mkdir(dirname($destination), 0755, true);
    				if ($mode == 'cp'){
    					copy($documentPath, $destination);
    				}elseif ($mode == 'mv'){
    					rename($documentPath, $destination);
    				}else{
    					move_uploaded_file($documentPath, $destination);
    				}
    			}
    
    			return $document;
    }
    /**
     * ดึงข้อมูลจาก model ที่ระบุด้วย $params
     * @param array $params เงื่อนไขการดึงข้อมูล
     * @return Document
     */
    public function findByParams($params) {
    	$query = Document::find();
    	$query->where(['type' => $params[self::ENCODE_ENTITY],
    			'refId' => $params[self::ENCODE_ID],
    			'itemNo' => $params[self::ENCODE_ITEMNO]]);
    	$model = $query->one();
    
    	return $model;
    }
    
    
    /**
     * ให้ค่า full path ของไฟล์ต้นฉบับ
     * @return string
     */
    public function getFullPath() {
    	$path = \Yii::$app->params['document.basePath'] . $this->getPrefix($this->type) . '/' . $this->srcPath;
    	return $path;
    }
    
    
    public function getInfo($options) {
    	return $this->getAttributes() + array('objectType' => Entity::$arrType[$this->type], 'fullPath' => $this->getPublishUri($options));
    }
    
    /**
     * ให้ค่า prefix path ของแต่ละ entity type
     * @param int $type
     * @return string
     */
    public function getPrefix($type) {
    	return Entity::$arrType[$type];
    }
    
    
    /**
     * ให้ค่า url สำหรับเรียกใช้งาน  document
     * @return string
     */
    public function getPublishUri($options = array()) {
    	 
    	$encodeId = $this->refId;
    	if(is_numeric($this->refId))
    		$encodeId = strpos($this->refId, '.')?strval($this->refId):intval($this->refId);
    
    	$params = array(
    			self::ENCODE_ENTITY => (int)$this->type,
    			self::ENCODE_ID => $encodeId,
    			self::ENCODE_ITEMNO => (int)$this->itemNo,
    	) + $options;
    	$enc = new TrEnc(\Yii::$app->params['crypto'][0][0], \Yii::$app->params['crypto'][0][1]);
    	$arr = explode(".", $this->fileName);
    	$extension = $arr[count($arr) - 1];
    	$retVal =  $enc->encode($params) . '.' . $extension;
    	return $retVal;
    }
    
    
    // media encoding & shortening service
    const ENCODE_ENTITY = 1;
    const ENCODE_ID = 2;
    const ENCODE_ITEMNO = 3;
   
    const ENCODE_HIGHLIGHT = 9;	// ไม่ใช้ในการ generate URL จริงๆ แต่ใช้เป็นเงื่อนไขในการหา itemNo
    
    
}
