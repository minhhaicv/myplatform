<?php
class formatHelper{

	public function cutString($content, $length = 500, $subfix=' ...', $tags = null){
        $content = strip_tags($content, $tags);
        
		$result = $content;
		if(mb_strlen($content, "UTF-8") > $length){
	    	$result = mb_substr($content, 0, $length+1, "UTF-8"); //cut string with limited number
	    	$position = mb_strrpos($result, " ", null, "UTF-8"); //find position of last space
	    	if($position)
	    		$result = mb_substr($result, 0, $position, "UTF-8"); //cut string again at last space if there are space in the result above    	
	    	$result .= $subfix;
	    }
	    
	    return $result;
	}
	
}