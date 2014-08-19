<?php
class hashHelper{

	public function extract($list = array(), $alias = '') {
	    $return = array();
	    foreach($list as $key => $item) {
	        $return[$key] = $item[$alias]['id'];
	    }
	    
	    return $return;
	}
	
	public function password($password = '') {
	    return md5($password);
	}
}