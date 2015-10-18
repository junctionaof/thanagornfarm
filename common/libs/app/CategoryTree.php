<?php
namespace app;


use common\models\ObjectCategory;

class CategoryTree {
	
	private static $_rootNode;
	private static $_allNodes;
	
	public $id;
	public $parentId;
	public $title;
	public $titleEn;
	public $orderNo;
	
	public $forContent;
	public $forNews;
	public $forColumn;
	public $forNcs;
	public $forSocial;
	
	public $credit;
	public $keywords;
	public $description;
	
	public $children;
		
	public function __construct($fields) {
		if (isset($fields['id'])) {
			$this->id = $fields['id'];
			$this->parentId = $fields['parentId'];
			$this->title = $fields['title'];
			$this->titleEn = $fields['titleEn'];
			$this->forContent = $fields['forContent'];
			$this->forNews = $fields['forNews'];
			$this->forColumn = $fields['forColumn'];
			$this->forNcs = $fields['forNcs'];
			$this->forSocial = $fields['forSocial'];
		
			$this->credit = $fields['credit'];
			$this->keywords = $fields['keywords'];
			$this->description = $fields['description'];
		}
		
		$this->children = array();
	}
	
	/**
	 * สร้าง child node
	 * @param CategoryTree $child
	 * @return void
	 */
	public function addChild(CategoryTree $child) {
		$this->children[$child->id] = $child;
	}

	/**
	 * ตรวจสอบว่า node ปัจจุบันที่ระบุสามารถใช้กับ content ที่ระบุได้หรือไม่ โดยสามารถดูลงไปถึงระดับลูกหลานได้ด้วย
	 * @param string $contentType ประเภทของ content
	 * @param bool $deepSearch ค้นหาแบบลึกหรือไม่
	 * @return bool
	 */
	public function forContent($contentType = null, $deepSearch = true) {
		$retVal = false;
		switch($contentType) {
			case 'all':
				$retVal = true;
				break;
			case 'news':
				if ($this->forNews)
					$retVal = true;
				break;
			case 'column':
				if ($this->forColumn)
					$retVal = true;
				break;
			case 'ncs':
				if ($this->forNcs)
					$retVal = true;
				break;
			case 'social':
				if ($this->forSocial)
					$retVal = true;
				break;
			default:
				$retVal = (bool)$this->forContent;
		}
		
		if (!$retVal && $deepSearch) {
			foreach($this->children as $child) {
				if ($child->forContent($contentType)) {
					$retVal = true;
					break;
				}
			}			
		}
		
		return $retVal;
	}
	

	/**
	 * ส่งค่า parent node ทั้งหมดภายในโครงสร้าง Tree 
	 * @return array
	 */
	public static function getAllRootNode($type = NULL) {
		$items = array();
		if (!is_array(self::$_allNodes))
			self::init();
	
		foreach (self::$_allNodes as $obj){
			if(!$obj->parentId){
				if($obj->forContent($type, false))
					$items[$obj->id] = $obj->title;
			}
		}

		return $items;
	}
	
	/**
	 * ส่งค่า children node ทั้งหมดภายในโครงสร้าง Tree ตาม contentType ที่ระบุ
	 * @param string $contentType ชนิดของ content [news, article, person, ncs]
	 * @param bool $bIncludeSelf
	 * @return array
	 */
	public function getChildren($contentType = null, $bIncludeSelf = true) {
		if (!is_array(self::$_allNodes))
			self::init();

		if ($bIncludeSelf && $this->forContent($contentType, false))
			$arrRetVal = array($this->id);
		else
			$arrRetVal = array();
			
		foreach($this->children as $child) {
			$arrRetVal = array_merge($arrRetVal, $child->getChildren($contentType, true));
		}
		return $arrRetVal;
	}
	
	/**
	 * ให้ค่า string ของ description ตามหมวดที่ระบุ หากไม่มีใน category ปัจจุบัน จะดึงจาก parent มาให้แทน
	 * @return string
	 */
	public function getDescription() {
		if ($this->description)
			return $this->description;
		elseif ($this->parentId) {
			$node = self::getNode($this->parentId);
			return $node->getDescription();
		}
		
		return null;
	}
	
	/**
	 * คืนค่า Sting ระบุเส้นทางของ node ปัจจุบัน
	 * @param string $lang ภาษาที่ต้องการ [TH, other]
	 * @param string $separator ตัวคั่นระหว่าง node
	 * @return string
	 */
	public function getFullPath($lang = 'TH', $separator = '/') {
		$arrParentId = $this->getParents();
		$arrParentTitle = array();
		foreach($arrParentId as $id) {
			$node = self::getNode($id);
			$arrParentTitle[] = $lang == 'TH'?$node->title:$node->titleEn;
		}

		return join($separator, $arrParentTitle);
	}
	
	/**
	 * สร้าง html tag option จากตัว node และ children ทั้งหมด
	 * @param int $level ระดับของ node ปัจจุบัน ใช้สำหรับทำการย่อหน้าข้อความ
	 * @param int $selectedValue ค่าที่ถูกเลือกปัจจุบัน
	 * @param string $contentType ประเภทของ content
	 * @return string
	 */
	public function getHtmlOptionString($level, $selectedValue, $contentType = null) {
		if ($contentType && !$this->forContent($contentType)){
		
			return '';
		}
		$disabled = $this->forContent($contentType, false)?'':' disabled="disabled"';
		$selected = $this->id == $selectedValue?' selected="selected"':'';
		$strRetVal = "<option value=\"{$this->id}\"{$disabled}{$selected}>" . str_repeat(' &nbsp;', $level * 2) . $this->title . '</option>';
		foreach($this->children as $child) {
			$strRetVal .= $child->getHtmlOptionString($level + 1, $selectedValue, $contentType);
		}
		
		return $strRetVal;
	}
	
	/**
	 * ให้ค่า array ของ keyword สำหรับใส่ใน meta keyword tag โดยดึงจาก keyword parent ไล่ลำดับลงมา
	 * @return array
	 */
	public function getKeywords() {
		$arrKeyword = preg_split('/\s*,\s*/', $this->keywords, -1, PREG_SPLIT_NO_EMPTY);
		if ($this->parentId) {
			$ParentNode = self::getNode($this->parentId);
			return array_merge($ParentNode->getKeywords(), $arrKeyword);
		}
		else {
			return $arrKeyword;
		}
	}
	
	/**
	 * ให้ค่า array ที่ประกอบไปด้วย Id ของ parent node ทั้งหมด รวมทั้ง id ของ node เองด้วย
	 * @return array
	 */
	public function getParents() {
		if ($this->parentId != null) {
			$ParentNode = self::getNode($this->parentId);
			$arrParent = $ParentNode->getParents();
		}
		else
			$arrParent = array();
		
		$arrParent[] = $this->id;
		
		return $arrParent;
	}
	
	/**
	 * สร้าง format string ตาม pattern ต่างๆ ที่ระบุ
	 * @param string $strTreeFormat รูปแบบของ string ของตัว node เอง หาก node นั้นสามารถใช้ content type ที่ระบุได้
	 * @param string $strDisabledTreeFormat รูปแบบของ string ของตัว node เอง หาก node นั้นไม่สามารถใช้ content type ที่ระบุ
	 * @param string $strSubTreeFormat รูปแบบของ string ของ subtree
	 * @param string $contentType ประเภทของ content
	 * @return string
	 */
	public function getString($strTreeFormat='[title]', $strDisabledTreeFormat = '[title]', $strSubTreeFormat = null, $contentType = null) {
		if ($contentType && !$this->forContent($contentType))
			return '';
		
		$strRetVal = $this->forContent($contentType, false)?$strTreeFormat:$strDisabledTreeFormat;
		$strRetVal = str_replace('[title]', $this->title, $strRetVal);
		$strRetVal = str_replace('[path]', $this->getFullPath(), $strRetVal);
		$strRetVal = str_replace('[id]', $this->id, $strRetVal);
		$strRetVal = str_replace('[credit]', $this->credit, $strRetVal);
		$strSubTree = '';
		foreach($this->children as $child) {
			$strSubTree .= $child->getString($strTreeFormat, $strDisabledTreeFormat, $strSubTreeFormat, $contentType);
		}
		if ($strSubTree != '')
				$strSubTree = str_replace('[subTree]', $strSubTree, $strSubTreeFormat);
		$strRetVal = str_replace('[subTree]', $strSubTree, $strRetVal);
		
		return $strRetVal;
	}
	
	/**
	 * หาค่าของ option ใน <select> โดยมีการจัดลำดับตาม tree นับจาก node ที่ระบุ id
	 * @param int $nodeId รหัสของ node ที่ต้องการ
	 * @param int $selected id ของ node ที่เลือกปัจจุบัน
	 * @param string $contentType ประเภทของ content
	 * @return string
	 */
	public static function getHtmlListOption($nodeId = null, $selected = null, $contentType = null) {
		if (!is_array(self::$_allNodes))
			self::init();

		$strRetVal = '';
		if ($nodeId) {
			$node = self::getNode($nodeId);
			$strRetVal .= $node->getHtmlOptionString(0, $selected, $contentType);
		}
		else {
			foreach(self::$_rootNode->children as $node) {
				$strRetVal .= $node->getHtmlOptionString(0, $selected, $contentType);
			}
		}
		
		return $strRetVal;
	}
	
	/**
	 * หาค่า node ตาม id
	 * @param int $id รหัสของ node ที่ต้องการ
	 * @return CategoryTree
	 */
	public static function getNode($id) {
		if (!is_array(self::$_allNodes))
			self::init();

		return self::$_allNodes[$id];
	}
	
	/**
	 * หา node ที่ระบุตาม Path (ตาม titleEn)
	 * @param string $path เส้นทางของ Node ที่ต้องการ
	 * @return CategoryTree
	 */
	public static function getNodeByPath($path) {
		if (!isset(self::$_rootNode))
			self::init();		
		
		$parts = preg_split('/\//', $path);
		$found = false;
		foreach(self::$_rootNode->children as $node) {
			if ($node->titleEn == $parts[0]) {
				$found = true;
				break;
			}
		}
		if (!$found)
			return;
		array_shift($parts);

		foreach($parts as $titleEn) {
			$found = false;
			foreach($node->children as $child) {
				if ($child->titleEn == $titleEn) {
					$node = $child;
					$found = true;
					break;
				}
			}
			if (!$found) {
				break;
			}
		}
		if ($found)
			return $node;
		else
			return null;
	}
	
	/**
	 * ให้ค่า root node
	 * @return CategoryTree
	 */
	public static function getRootNode() {
		if (!isset(self::$_rootNode))
			self::init();
			
		return self::$_rootNode;
	}
	
	/**
	 * หา parent node ระดับสูงสุดตาม id ของ node ที่ระบุ
	 * @param int $id id ของ node ที่ต้องการหา top level node
	 * @return CategoryTree
	 */
	public static function getTopNode($id) {
		if (!is_array(self::$_allNodes))
			self::init();
			
		$node = self::getNode($id);
		while($node != null && $node->parentId != null) {
			$node = self::getNode($node->parentId);
		}
		
		return $node;
	}
	
	/**
	 * ให้ค่า string โดยการ traverse ไปตาม subtree
	 * @param int $nodeId รหัสของ node ที่ระบุ
	 * @param string $strTreeFormat รูปแบบของ string ที่ต้องการ
	 * @param string $strSubTreeFormat รูปแบบของ string ที่ใช้สร้าง subtree
	 * @return string
	 */
	public static function getTreeString($nodeId = null, $strTreeFormat='[title]', $strDisabledTreeFormat, $strSubTreeFormat = null, $contentType = NULl) {
		if (!is_array(self::$_allNodes))
			self::init();

		$strRetVal = '';
		if ($nodeId) {
			$node = self::getNode($nodeId);
			$strRetVal .= $node->getString($strTreeFormat, $strDisabledTreeFormat, $strSubTreeFormat, $contentType);
		}
		else {
			foreach(self::$_rootNode->children as $node) {
				$strRetVal .= $node->getString($strTreeFormat, $strDisabledTreeFormat, $strSubTreeFormat, $contentType);
			}
		}
		
		return $strRetVal;
	}
	
	/**
	 * สร้าง Tree structure จากการ query ในตาราง ObjectCategory
	 * @return void
	 */
	public static function init() {
		self::$_rootNode = new CategoryTree(array('title' => 'หน้าหลัก'));
		self::$_allNodes = array();
		
		$query = ObjectCategory::find();
		$query->orderBy(['parentId'=>SORT_DESC, 'orderNo'=>SORT_ASC]);
		$lst = $query->all();
		//$sql = 'select * from ObjectCategory order by parentId, orderNo';
		//$lst = Yii::app()->db->createCommand($sql)->queryAll();
		
		$nodeCount = 0;
		while ($nodeCount != count($lst)) {
			$nodeCount = count($lst);

			foreach($lst as $index=>$fields) {
				$node = new CategoryTree($fields);
				if (is_null($fields['parentId'])) {
					// root level nodes	
					self::$_rootNode->addChild($node);
					unset($lst[$index]);
				}
				else {
					// find parent node
					foreach(self::$_allNodes as $parentId=>$parentNode) {
						if ($fields['parentId'] == $parentId) {
							$parentNode->addChild($node);
							unset($lst[$index]);
						}
					}
				}
				self::$_allNodes[$node->id] = $node;
			}
		}
	}

}
?>