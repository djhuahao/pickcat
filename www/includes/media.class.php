<?php
class image{
	private $image;
	private $thumbnail;
	private $watermark;
	private $format;
	
	function __construct($files){
		$this->image = new Imagick($files);
	}
	
	function __destruct(){
		if($this->image) $this->image->destory();
		if($this->thumbnail) $this->thumbnail->destroy();
		if($this->watermatk) $this->watermark->destroy();
	}
	
	function init($src=null){
		$this->format = $this->image->getImageFormat();
		if($this->format == 'JPG' || $this->format == 'JPEG'){
			$this->image->setInterlaceScheme(imagick::INTERLACE_PLANE);
		}
	}
	
	function compress_jpg($quality=80){
		if($this->format == 'JPG' || $this->format == 'JPEG'){
			if(empty($quality)){
				$quality = $this->image->getImageCompressionQuality() * 0.8;
			}
			$this->image->setImageCompressionQuality($quality);
		}
	}
	
	function create_thumbnail($output,$weight,$height,$bestfit=false){
		$this->thumbnail = $this->image->clone();
		$this->thumbnail->thumbnailImage($weight,$height,$bestfit);
		$this->thumbnail->writeImage($output);
	}
	
	/* $position
	 * 7 0 1
	 * 6   2
	 * 5 4 3
	 */
	function add_word_watermark($output,$words,$font,$position=3){
	}
	
	function add_image_watermark($output,$src,$position=3){
	}
}
?>