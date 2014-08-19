<?php
class urlHelper{

	public function url($target, $prefix) {
	    $title = strtolower(Helper::getHelper('string')->removeAccent($target['title'], '-'));
	   
	    return $prefix.$title.'-'.$target['id'];
	}
	
	
	
}