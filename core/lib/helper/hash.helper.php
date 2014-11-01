<?php
class hashHelper{

	public function extract($list = array(), $alias = '', $target = 'id') {
	    $return = array();
	    foreach($list as $key => $item) {
	        $return[$key] = $item[$alias][$target];
	    }
	    
	    return $return;
	}
	
	public function password($password = '') {
	    return md5($password);
	}
}