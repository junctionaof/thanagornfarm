<?php
namespace app;

/**
 * Library ข้อความภาษาไทย
 * @author JJoi
 *
 */
class ThaiText {
	
	/**
	 * 
	 * รายการคำศัพท์ภาษาไทย
	 * @var array รายการ string ของศัพท์ภาษาไทย
	 */
	private static $_dictionary = array();	
	
	/**
	 * 
	 * @var array นิยามตัวอักษรภาษาไทย
	 * แต่ละ element bitfield นิยามคุณสมบัติพิเศษของแต่ละตัว
	 */	
	private static $_letterDefinitions = array();
	
	private static function _init() {		
		$arr = array (
			0x0E26	=>	self::CHAR_NOTSTOP,		//THAI CHARACTER LU
			0x0E2F	=>	self::CHAR_NOTSTOP,		//THAI CHARACTER PAIYANNOI
			0x0E30	=>	self::CHAR_NOTSTART,		//THAI CHARACTER SARA A
			0x0E31	=>	self::CHAR_INNERVOWEL | self::CHAR_ZEROWIDTH,		//THAI CHARACTER MAI HAN-AKAT
			0x0E32	=>	self::CHAR_ENDINGVOWEL,		//THAI CHARACTER SARA AA
			0x0E33	=>	self::CHAR_ENDINGVOWEL, 	//THAI CHARACTER SARA AM
			0x0E34	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER SARA I
			0x0E35	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER SARA II
			0x0E36	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER SARA UE
			0x0E37	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER SARA UEE
			0x0E38	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER SARA U
			0x0E39	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER SARA UU
			0x0E3A	=>	self::CHAR_ENDINGVOWEL | self::CHAR_ZEROWIDTH,		//THAI CHARACTER PHINTHU
			0x0E40	=>	self::CHAR_MUSTSTART,		//THAI CHARACTER SARA E
			0x0E41	=>	self::CHAR_MUSTSTART,		//THAI CHARACTER SARA AE
			0x0E42	=>	self::CHAR_MUSTSTART,		//THAI CHARACTER SARA O
			0x0E43	=>	self::CHAR_MUSTSTART,		//THAI CHARACTER SARA AI MAIMUAN
			0x0E44	=>	self::CHAR_MUSTSTART,		//THAI CHARACTER SARA AI MAIMALAI
			0x0E45	=>	self::CHAR_MUSTSTOP,		//THAI CHARACTER LAKKHANGYAO
			//0x0E46	=>	self::CHAR_MUSTSTOP,		//THAI CHARACTER MAIYAMOK
			0x0E47	=>	self::CHAR_INNERVOWEL,		//THAI CHARACTER MAITAIKHU
			0x0E48	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER MAI EK
			0x0E49	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER MAI THO
			0x0E4A	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER MAI TRI
			0x0E4B	=>	self::CHAR_NOTSTART | self::CHAR_ZEROWIDTH,		//THAI CHARACTER MAI CHATTAWA
			0x0E4C	=>	self::CHAR_MUSTSTOP | self::CHAR_ZEROWIDTH,		//THAI CHARACTER THANTHAKHAT
			0x0E4D	=>	self::CHAR_MUSTSTOP | self::CHAR_ZEROWIDTH,		//THAI CHARACTER NIKHAHIT
		);

		foreach($arr as $u => $definition) {
			self::$_letterDefinitions[self::unicode2mb($u)] = $definition;
		}
		
		mb_regex_encoding('UTF-8');
		mb_internal_encoding('UTF-8');
	}
	
	public static function breakWord($str, $stopAt = null) {
		$arr = mb_split('\s', $str);
		$retVal = array();
		foreach($arr as $str) {
			$retVal = array_merge($retVal, array(' '), self::breakWordInner($str));
			if ($stopAt && count($retVal) > $stopAt) break;
		}
		array_shift($retVal);

		return $retVal;
	}
	
	public static function breakWordInner($str) {
		if (empty(self::$_letterDefinitions)) self::_init();

		$arr = self::splitWord($str);
				
		$index = 0;
		$arrChars = array();
		$arrLength = array();
		for ($i = 0; $i < count($arr); $i++) {
			$utf8 = $arr[$i];
			$utf8Next = $arr[$i+1];
				
			$arrChars[$index] .= $utf8;
			$arrLength[$index]++;
			
			$definition = self::$_letterDefinitions[$utf8];
			if ($utf8Next)
				$nextDefinition = self::$_letterDefinitions[$utf8Next];

			if ($definition & self::CHAR_NOTSTOP) $index--;
			elseif ($definition & self::CHAR_MUSTSTART) $index--;
			elseif ($nextDefinition & self::CHAR_NOTSTART) $index--;
			elseif ($nextDefinition & self::CHAR_MUSTSTOP) $index--;			
			
			$index++;
			$definition = $nextDefinition;
		}
		
		$index = 0;
		while($index < count($arrChars) - 1) {
			// get possible longest chars
			$charLength = $arrLength[$index];
			$endIndex = $index;
			while($charLength < self::MAX_WORD_LENGTH && $endIndex < count($arrLength)) {
				$endIndex++;
				$charLength += $arrLength[$endIndex];
			}
			
			$dictFound = false;
			while($endIndex > $index && !$dictFound) {
				$lookupStr = join('', array_slice($arrChars, $index, $endIndex - $index + 1));
				if (self::wordDefined($lookupStr)) {
					$arrChars[$index] = $lookupStr;
					$arrLength[$index] = mb_strlen($lookupStr, 'UTF-8');
					array_splice($arrChars, $index + 1, $endIndex - $index);
					array_splice($arrLength, $index + 1, $endIndex - $index);
					$dictFound = true;
					break;
				}
				$endIndex--;
			}
			
			$index++;
		}

		return $arrChars;
	}
	
	public static function loadDictionary($filePath = null) {	
		/* $content = Yii::app()->cache->get('thaiText.wordlist');
		if (empty($content)) {
			$filePath = Yii::app()->runtimePath . '/wordlist.txt';
			$content = file_get_contents($filePath);
			self::$_dictionary = array_flip(preg_split('/\r?\n/', $content));
			Yii::app()->cache->set('thaiText.wordlist', $content, 1800);
		} */
	}
	
	private static function splitWord($str) {
		$encoding = mb_detect_encoding($str);		
		$ucsStr = mb_convert_encoding($str, 'UCS-2', $encoding);
		$tmp = str_split($ucsStr, 2);
		foreach($tmp as $ucs) {
			$arr[] = mb_convert_encoding($ucs, 'UTF-8', 'UCS-2');
		}

		return $arr;
	}
	
	public static function truncate($str, $width, $truncateString = '...') {
		$arr = self::breakWord($str, $width);

		$wordWidth = 0;
		foreach($arr as $index =>$word) {
			$wordWidth += self::wordWidth($word);
			if ($wordWidth > $width) break;
		}

		if ($index < count($arr) - 1)
			array_splice($arr, $index);
		else
			$truncateString = '';
					
		return join('', $arr) . $truncateString;
	}
	
	public static function ucs2unicode($ucs) {
		$arr = unpack('n', $ucs);
		return $arr[1];
	}
	
	public static function unicode2mb($u, $encoding = 'UTF-8') {
		return mb_convert_encoding(pack('n', $u), $encoding, 'UCS-2');
	}
	
	public static function wordWidth($word) {
		if (empty(self::$_letterDefinitions)) self::_init();
		
		$width = 0;
		$arr = self::splitWord($word);
		foreach($arr as $chr) {
			$definition = self::$_letterDefinitions[$chr];
			$width += $definition?(($definition & self::CHAR_ZEROWIDTH)?0:1):1;
		}
		
		return $width;
	}
	
	protected static function wordDefined($word) {
		return isset(self::$_dictionary[$word]);
	}
	
	const CHAR_NOTSTART = 1;	
	const CHAR_MUSTSTART = 2;	// implies not stop
	const CHAR_NOTSTOP = 4;
	const CHAR_MUSTSTOP = 8;	// implies not start
	const CHAR_ZEROWIDTH = 16;
	
	const CHAR_ENDINGVOWEL = 9; //	self::CHAR_NOTSTART | self::CHAR_MUSTSTOP
	const CHAR_INNERVOWEL = 21;	//	self::CHAR_NOTSTART | self::CHAR_NOTSTOP | self::CHAR_ZEROWIDTH;
	
	const MAX_WORD_LENGTH = 15;
	
}