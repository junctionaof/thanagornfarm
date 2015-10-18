<?php
/**
 * Mongodb Wrapper
 * @author JJoi <jjoi@ni11.com>
 * @version 1.0 2014-01-08
 */
class YMongo extends CApplicationComponent {
	private $_mongo;

	public $connectionString;
	public $dbname;
	public $options;
	public $rp;

	public function init() {
		parent::init();

		if (!empty($this->options))
			$this->_mongo = new MongoClient($this->connectionString, $this->options);
		else
			$this->_mongo = new MongoClient($this->connectionString);
		if ($this->rp)
			$this->_mongo->setReadPreference($this->rp);
	}

	public function __call($method, $args) {
		return call_user_func_array(array($this->_mongo->{$this->dbname}, $method), $args);
	}

	public function __get($name) {
		return $this->_mongo->{$this->dbname}->$name;
	}

	/**
	 *
	 * แปลงข้อมูลจาก mongodb ให้เหมาะสมกับการใช้งาน
	 * @param unknown_type $doc
	 */
	public function parse($doc) {
		foreach($doc as $key=>$val) {
			if (is_object($val)) {
				if ($val instanceof MongoId)
					$doc[$key] = (string)$val;
				elseif ($val instanceof MongoDate)
					$doc[$key] = date('c', $val->sec);
			}
		}

		return $doc;
	}
}
?>