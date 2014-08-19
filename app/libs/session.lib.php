<?php
class Session {
	
	public function check($key=''){
		if(!$key) return false;
		$temp = explode('.', $key);
		
		$eval ="\$return = \$_SESSION";
		foreach($temp as $field) {
			$eval = $eval."['".$field."']";
		}
		
		eval($eval.';');
		
		return isset($return); 
	}
	
	public function write($key='', $val=''){
		if(!$key) return false;
		$temp = explode('.', $key);
		
		$eval ="\$_SESSION";
		foreach($temp as $field) {
			$eval = $eval."['".$field."']";
		}
		
		$eval .= "=\$val;";
		eval($eval);
	}
	
	public function read($key='') {
		if(!$key) return false;
		$temp = explode('.', $key);

		if($temp) {
    		$eval = "\$_SESSION";
    		foreach($temp as $field) {
    			$eval = $eval."['".$field."']";
    		}

    		eval("\$return = empty(".$eval.") ? '' : ".$eval.';');
		}
	
		return $return;
	}

	public function delete($key=''){
		if(!$key) return false;
		
		$eval ="unset(\$_SESSION";
		foreach(explode(".", trim($key, '.')) as $field) {
			$eval = $eval."['".$field."']";
		}
		eval($eval.');');
	}
}