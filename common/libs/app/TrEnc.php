<?php
namespace  app;

class TrEnc {
	private $td;
	private $key;
	private $iv;
	
	public function __construct($iv, $key) {
		$this->td = mcrypt_module_open('rijndael-256', '', 'cfb', '');
		$this->iv = $iv;
		$this->key = $key;
	}
	
	public function __destruct() {
		mcrypt_module_close($this->td);
	}
	
	/**
	 * เปลี่ยน string ที่ encode ด้วย base62 ให้เป็น long integer string 
	 * @param string $str
	 * @return string
	 */
	public function base62Decode($str) {
		$arr = str_split($str, 1);
		$num = 0;
		foreach($arr as $chr) {
			$num = bcmul($num, 62);
			$ord = ord($chr);
			if ($ord >= 65 && $ord <= 90)
				$ord -= 65;
			elseif($ord >= 97 && $ord <= 122)
				$ord -= 71;
			else
				$ord += 4;
			$num = bcadd($num, $ord);			
		}
		
		return $num;
	}
	
	/**
	 * เปลี่ยน long integer ให้เป็น string equivalent ด้วย base62 encoding (a-zA-Z0-9)
	 * @param integer $num
	 * @return string
	 */
	public function base62Encode($num) {
		$result = '';
		if ($num == 0) return 'A';
		while ($num >= 1) {
			$mod = bcmod($num, 62);
			if ($mod <  26)
				$result = chr(65 + $mod) . $result;
			elseif($mod < 52)
				$result = chr(71 + $mod) . $result;
			else
				$result = chr($mod - 4) . $result;
		
			$num = bcdiv($num, 62);
		}
		
		return $result;
	}
	
	/**
	 * เปลี่ยน dec string ให้เป็น hex string
	 * @param string $str
	 * @return string
	 */
	public function dechex_bc($str) {
		$hex = '';
		while($str != '0') {
			$mod = bcmod($str, 16);
			if ($mod < 10)
				$hex = (string)$mod . $hex;
			else
				$hex = chr($mod + 87) . $hex;
			$str = bcdiv($str, 16);
		}
		
		return $hex;
	}
	
	/**
	 * เปลี่ยนค่า encode string ให้กลับเป็น original object (array based)
	 * @param string $str
	 * @return string
	 */
	public function decode($str) {				
		$result = $this->base62Decode($str);
		
		$result = $this->dechex_bc($result);
		$result = pack('H*', $result);
		mcrypt_generic_init($this->td, $this->key, $this->iv);
		$result = mdecrypt_generic($this->td, $result);
		mcrypt_generic_deinit($this->td);
		
		return json_decode($result, true);
	}
	
	/**
	 * encode object (array based) ให้เป็น string
	 * @param string $obj
	 * @return string
	 */
	public function encode($obj) {
		mcrypt_generic_init($this->td, $this->key, $this->iv);
		$str = mcrypt_generic($this->td, json_encode($obj));
		mcrypt_generic_deinit($this->td);
		
		$data = unpack('H*', $str);

		return $this->base62Encode($this->hexdec_bc($data[1]));
	}
		
	/**
	 * แปลง hex string ให้เป็นค่าตัวเลข 
	 * มีการใช้งาน BCMath เพื่อให้สามารถใช้กับเลขจำนวนขนาดใหญ่มากๆ ได้
	 * @param string $hex
	 * @return integer
	 */
	public function hexdec_bc($hex) {
		$arr = str_split($hex, 2);
		$num = 0;
		foreach($arr as $data) {
			if ($num != 0) $num = bcmul($num, 256);
			$num = bcadd($num, hexdec($data));
		}
	
		return $num;
	}
}