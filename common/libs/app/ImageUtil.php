<?php

namespace app;

/**
 * Utility class สำหรับทำ image manipulation
 * @author JJoi
 */
class ImageUtil {
	public $im;
	public $width;
	public $height;
	public $ratio;
	
	public function __construct($filePath) {
		$this->setGd(imagecreatefromstring(file_get_contents($filePath)));
	}
	
	/**
	 * crop ภาพตามข้อมูลี่ระบุ
	 * @param int $width
	 * @param int $height
	 * @param int $x = null ตำแหน่งเริ่มต้นแกน x
	 * @param string $y = null ตำแหน่งเริ่มต้นแกน y
	 * @param bool $scale = false กรณี autocrop ให้เลือก
	 * @return object
	 */
	public function crop($width, $height, $x = null, $y = null, $scale = false) {
		if ($scale) {
			
			$cratio = $width / $height;
			
			if ($cratio > $this->ratio) {
				$sw = $this->width;
				$sh = $sw * $height / $width;
			}
			else {
				$sh = $this->height;
				$sw = $sh * $width / $height;
			}
		}
		else {
			$sw = $width;
			$sh = $height;
		}
		
		if ($x == null || $y == null) {
			// crop at center
			$cx = $this->width / 2;
			$cy = $this->height / 2;
			
			$x = $cx - $sw / 2;
			$y = $cy - $sh / 2;
		}
		
		$im = imagecreatetruecolor($width, $height);
		
		imagecopyresampled($im, $this->im, 0, 0, $x, $y, $width, $height, $sw, $sh);
		imagedestroy($this->im);
		
		$this->setGd($im);
		
		return $this->im;
	}
	
	/**
	 * หาค่า scale size โดยการระบุ width หรือ height
	 * @param string $width
	 * @param string $height
	 * @return array
	 */
	public function getScale($width, $height = null) {
		if($width) {
			$retVal = array(
					$width,
					round($this->height * $width / $this->width),
			);
		}
		elseif($height) {
			$retVal = array(
					round($this->width * $height / $this->height),
					$height,
			);
		}
		else
			$retVal = array();
			
		return $retVal;
	}
	
	/**
	 * resize image ตามขนาดที่กำหนด
	 * @param int $width
	 * @param int $height
	 * @return object
	 */
	public function scale($width, $height = null) {
		$scales = $this->getScale($width, $height);
		$im = imagecreatetruecolor($scales[0], $scales[1]);
		imagealphablending( $im, false );
		imagesavealpha( $im, true );
		imagecopyresampled($im, $this->im, 0, 0, 0, 0, $scales[0], $scales[1], $this->width, $this->height);
		imagedestroy($this->im);
		
		$this->setGd($im);
		
		return $im;
	}
	
	/**
	 * set ค่า image instance ด้วย gd resource ที่ระบุ
	 * @param object $im
	 */
	private function setGd($im) {
		$this->im = $im;
		$this->width = imagesx($this->im);
		$this->height = imagesy($this->im);
		$this->ratio = $this->height?($this->width/$this->height):0;		
	}
	
	public function watermark($type) {
		switch($type) {
			case Media::FIRSTWATERMARK:
				$this->watermarkFirst();
				break;
			default:
				$this->watermarkSecond();
		}
	}
	
	public function watermarkFirst() {
		$wmPath = \Yii::$app->params['media.basePath'].'watermark/watermarkfirst.png';
		
		$wmIm = new self($wmPath);
		
		$wmIm->scale($this->width, null);
	
		$y = intval(($this->height - $wmIm->height)/2);
		
		imagealphablending($this->im, true);
		imagesavealpha($this->im, false);
		imagecopy($this->im, $wmIm->im, 0, $y, 0, 0, $wmIm->width, $wmIm->height);
		
		imagealphablending($this->im, false);
		imagesavealpha($this->im, true);
	}
	
	public function watermarkSecond() {
		$wmPath = \Yii::$app->params['media.basePath'].'watermark/watermarksecond.png';
		$stamp = imagecreatefrompng($wmPath);
	
		$watermarkSize = getimagesize($wmPath);
	
		$grapScale = array();
		$now = ceil($this->width/$watermarkSize[0]);
		$noh = ceil($this->height/$watermarkSize[1]);
		for($i=0; $i<$now; $i++){
			for($j=0; $j<$noh; $j++){
				$grapScale[$i][$j] = array('x' => $watermarkSize[0]*$i, 'y'=> $watermarkSize[1]*$j);
			}
		}
	
		// Copy the stamp image onto our photo using the margin offsets and the photo
		// width to calculate positioning of the stamp.
		imagealphablending($this->im, true);
		imagesavealpha($this->im, false);
		foreach ($grapScale as $row){
			foreach ($row as $columns => $fields){
				imagecopy($this->im, $stamp, $fields['x'], $fields['y'], 0, 0, imagesx($stamp), imagesy($stamp));
			}
		}
		imagealphablending($this->im, false);
		imagesavealpha($this->im, true);
	}
	
}