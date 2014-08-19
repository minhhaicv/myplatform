<?php
class imageHelper{
	
	
	function watermarkImage($source, $destination){
		$stamp = imagecreatefrompng(LIBS_PATH.'include/watermark/watermark.png');
	
		$ww = imagesx($stamp);
		$wh = imagesy($stamp);
		
		
		$image = imagecreatefromjpeg($source);
		$w = imagesx($image);
		$h = imagesy($image);
		
		imagealphablending($stamp, true);
		imagealphablending($image, true);
		
		
		imagecopy($image, $stamp, $w-$ww, $h-$wh, 0, 0, $ww, $wh);
		
		imagejpeg($image, $destination);
		imagedestroy($image);
	}
		
	function watermarkText($source, $destination, $text) {
	   list($width, $height) = getimagesize($source);
	  
	   $image_p = imagecreatetruecolor($width, $height);
	   $color = imagecolorallocate($image_p, 254, 48, 40);
	   $font = LIBS_PATH.'include/watermark/arial.ttf';
	   $font_size = 30;
	   $filename = explode(".", $this->parsed_file_name);
	   $ext = strtolower($filename[count($filename)-1]);
	
	   if($ext == "jpg" || $ext == "jpeg"){
			$image = imagecreatefromjpeg($source);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
			imagettftext($image_p, $font_size, 0, $width-400, $height-50, $color, $font, $text);
			if ($destination) imagejpeg ($image_p, $destination, 100);
			else {
			  header('Content-Type: image/jpeg');
			  imagejpeg($image_p, null, 100);
			}
		}
		if($ext == "png"){
			$image = imagecreatefrompng($source);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
			imagettftext($image_p, $font_size,45, 100, $height-100, $color, $font, $text);
			if ($destination) imagepng($image_p, $destination);
			else {
			  header('Content-Type: image/jpeg');
			  imagepng($image_p, null);
			}
		}
		if($ext == "gif"){
		   $image = imagecreatefromgif($source);
		   imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
		   imagettftext($image_p, $font_size,45, 50, $height-50, $color, $font, $text);
		   if($destination) imagegif($image_p, $destination);
		   else {
			  header('Content-Type: image/jpeg');
			  imagegif($image_p, null);
		   }
		}
		imagedestroy($image);
		imagedestroy($image_p);
	}

	function displayImage($file, $width=150, $height=100, $zc=0, $furl = 0, $noimage=0, $option = array()){
		global $config;
		
		$path = $file['path'].'/'.$file['phyname'];
		
        $ext = $file['ext'];
        $alt = $file['name'];
        
		if($ext == "gif"){
			$phypath = $config->vars['upload'].'/'.$path;
			if($height)
				return "<img alt='{$alt}' src='{$phypath}' width='{$width}' height='{$height}' />";
			return "<img alt='{$alt}' src='{$phypath}' width='{$width}' />";
		}

        if(isset($option['scale'])){
			$size = $this->__scaleImage($path, $width, $height);
			if($option['padding'])
				return "<img alt='{$file->getTitle()}' src='{$this->__timthumbImagePath($path,$size['width'], $size['width'], 0, $furl)}' style='padding-top:{$size['padding-top']}px;'/>";
			return "<img alt='{$file->getTitle()}' src='{$this->__timthumbImagePath($path, $size['width'], $size['width'], 0, $furl)}' />";
		}
		
		return "<img alt='{$alt}' src='{$this->__timthumbImagePath($path, $width, $height, $zc, $furl)}' />";
	}
		
	function __timthumbImagePath($path, $width = 150, $height = 100, $zc=0, $furl = 0){
		global $config;
		
		if($furl) 
			return $config->vars['upload']."/image/{$width}-{$height}-{$zc}/{$path}";

		$path = $config->vars['upload'].'/'.$path;
		
		$timthumb = $config->vars['board_url']."/timthumb.php";
		return $timthumb."?src={$path}&w={$width}&h={$height}&zc={$zc}";
	}
	
	function __scaleImage($url, $width, $height) {
		global $config, $app;
		if(!file_exists($url)) return array();
		
		$image = @getimagesize($url);
		$height = $height?$height:$image['1'];
		$width = $width?$width:$image['0'];
		
		if($image['0']>$image['1']&&$image['1']>0){
			if(($image['0']/$image['1'])>($width/$height)&&$image['0']>$width){
				$tmp = $image['0']/$width;
				$sheight = $image['1']/$tmp;
				$swidth=$width;
			}elseif($image['1']>$height){
				$tmp = $image['1']/$height;
				$swidth = $image['0']/$tmp;
				$sheight=$height;
			}
		}elseif($image['0']>0) {
			if(($image['1']/$image['0'])>($height/$width)&&$image['1']>$height){
				$tmp = $image['1']/$height;
				$swidth = $image['0']/$tmp;
				$sheight=$height;
			}elseif($image['0']>$width){
				$tmp = $image['0']/$width;
				$sheight = $image['1']/$tmp;
				$swidth=$width;
			}
		}
		
		$size['width'] = round($swidth?$swidth:$image['0']);
		$size['height'] = round($sheight?$sheight:$image['1']);
		$size['padding-top'] = round($size['height']?(($height-$size['height'])/2):0);
		
		return $size;
	}
}