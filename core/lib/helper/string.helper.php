<?php
class stringHelper {
	static function removeAccent($strAccent = "", $sepchar = "", $preg = "", $remove_special = true) {
		$strfrom 	= " Á À Ả Ã Ạ Â Ấ Ầ Ẩ Ẫ Ậ Ă Ắ Ằ Ẳ Ẵ Ặ Đ É È Ẻ Ẽ Ẹ Ê Ế Ề Ể Ễ Ệ Í Ì Ỉ Ĩ Ị Ó Ò Ỏ Õ Ọ Ơ Ớ Ờ Ở Ỡ Ợ Ô Ố Ồ Ổ Õ Ộ Ú Ù Ủ Ũ Ụ Ư Ứ Ừ Ử Ữ Ự Ý Ỳ Ỷ Ỹ Ỵ";
		$strto		= " A A A A A A A A A A A A A A A A A D E E E E E E E E E E E I I I I I O O O O O O O O O O O O O O O O O U U U U U U U U U U U Y Y Y Y Y";
		
		$strfrom 	.= " á à ả ã ạ â ấ ầ ẩ ẫ ậ ă ắ ằ ẳ ẵ ặ đ é è ẻ ẽ ẹ ê ế ề ể ễ ệ í ì ỉ ĩ ị ó ò ỏ õ ọ ơ ớ ờ ở ỡ ợ ô ố ồ ổ ỗ ộ ú ù ủ ũ ụ ư ứ ừ ử ữ ự ý ỳ ỷ ỹ ỵ";
		$strto		.= " a a a a a a a a a a a a a a a a a d e e e e e e e e e e e i i i i i o o o o o o o o o o o o o o o o o u u u u u u u u u u u y y y y y";

		$fromarr = explode(" ", $strfrom);
		$toarr = explode(" ", $strto);
		
		$dicarr = array();
		for($i=1; $i<count($fromarr); $i++)
			$dicarr[$fromarr[$i]] = $toarr[$i];
		
		if(!$preg) $preg = "/[^a-z0-9-\s]+/i";
		
		$strNoAccent = strtr($strAccent, $dicarr);
		$strNoAccent = preg_replace($preg, "", $strNoAccent);
	
		if($remove_special){
			$specialchar = "acute grave circ tilde cedil ring uml amp quot verbar ";
			$specialchar .= ", . ? : ! < > & * ^ % $ # @ ; ' ( ) { } [ ] + ~ = / \" ";
			
			$specialcharArr = explode(" ", $specialchar);
			$strNoAccent = str_replace($specialcharArr,"",$strNoAccent);
		}
		
		if($sepchar) {
			$strNoAccent = str_replace(" ", $sepchar, $strNoAccent);
			$strNoAccent = str_replace($sepchar.$sepchar,$sepchar,$strNoAccent);
		}
			
		return $strNoAccent;
	}
	
	function cut($string = "", $num = 20, $subfix = ' ...'){
		$result = $string;
		if(mb_strlen($string, "UTF-8") > $num){
	    	$result = mb_substr($string,0,$num+1,"UTF-8"); //cut string with limited number
	    	$position = mb_strrpos($result," ",null,"UTF-8"); //find position of last space
	    	if($position)
	    		$result = mb_substr($result,0,$position,"UTF-8"); //cut string again at last space if there are space in the result above    	
	    	$result .= $subfix;
	    }    
	    return $result;
	}
}