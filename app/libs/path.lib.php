<?php
class PDFPath {
	
	public function __construct() {
		$this->_parseURL();
	}


	private function _parseURL() {
	    global $request;
	    
		$return = array();
		
		if(!empty($_GET)) {
		    $return['request'] = $_GET['request'];

		    $return['params'] = array();
			while(list($k, $v) = each($_GET)) {
			    if($k == 'request') continue;

			    $tmp = explode(':', $k);
			    if(count($tmp) == 2) {
			        $return['params'][$tmp[0]] = $tmp[1];
			        
			        continue;
			    }
			    
				if(is_array($_GET[$k])) {
					while(list($k2, $v2) = each($_GET[$k])) {
					    $return['params'][ $this->cleanKey($k) ][ $this->cleanKey($k2) ] = $this->cleanValue($v2);
					}
				} else $return['params'][$this->cleanKey($k)] = $this->cleanValue($v);
			}
		}
		
		if(!empty($_POST)) {
		    $return['data'] = array();
		   
			while(list($k, $v) = each($_POST)) {
				if ( is_array($_POST[$k])) {
					$k = $this->cleanKey($k);
					while( list($k2, $v2) = each($_POST[$k]) ){
						if(is_array($v2)){
							$k2 = $this->cleanKey($k2);
							foreach($v2 as $k3=>$v3){
								$return['data'][$k][$k2][$this->cleanKey($k3)] = $this->cleanValue($v3);
							}
						}
						else $return['data'][$k][ $this->cleanKey($k2) ] = $this->cleanValue($v2);
					}
				}
				else $return['data'][ $this->cleanKey($k) ] = $this->cleanValue($v);
			}
		}

		foreach($this->_cleanUrl($return) as $key => $info) {
		    $request->$key = $info;
		}
		
		return true;
	}
	
	private function _cleanUrl($url) {
	    global $meta, $app;
	    
		if( APPLICATION_TYPE=='frontend' ) {
			$url['request'] = str_replace($app->language()."/", '', $url['request']);
		}
		
		$url = $this->_setDefault($url);
	
		$url['params'] = $url['request'];
		if(APPLICATION_TYPE == "frontend") {
			if(!empty($url['request'])) {
				$match = array();
// 				preg_match('/trang-([0-9]+)\/?$/', $url['request'], $match);
				
// 				$path = trim(str_replace($match[0], '', $url['request']), '/');
					
					
				require_once (CORE_PATH."seo/seo.entity.php");
		        $seo = new seo_entity();
				
				$tmp = $seo->getByUrl($url['request']);
				
				if(!empty($tmp)) {
    				$url['params'] = $tmp["SEO"]['url'];
				}
			}
		}
		
		$tmp = explode("/", trim($url['params'], '/'));
		$query = array();
		
		$index = 0;
		
		ksort($tmp);
		$map = array('module', 'action');
		$orders = array("first", 'second', "third");
		
		foreach( $tmp as $key => $value) {
		    if(empty($value)) continue;
		    
		    if(empty($map[$key])) {
		        $t = explode(':', $value);
		        if(count($t) == 2) {
		            $third[$t[0]] = $t[1];
		        } else {
		            $second[$index++] = $value;
		        }
		    } else {
		        $first[$map[$key]] = $value;
		    }
		}

		$query = array();
		foreach ($orders as $order) {
		    if(empty($$order)) continue;
		    foreach( $$order as $key => $value ) {
		        $query[$key] = $value;
		    }
		}
        		
		if(!isset($query['action'])) {
            $query['action'] = "";
		}
		
		$url['query'] = $query;

		return $url;
	}
	
	private function _setDefault($url){
		if(empty($url['request'])){
			$url['request'] = "home";
			if(APPLICATION_TYPE == "backend") {
				$url['request'] = "post";
			}
		}
		
		return $url;
	}
	
	
	private function cleanKey($key) {
		if ($key == "") return "";
	
		$key = preg_replace( "/\.\./"           , ""  , $key );
		$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
		$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
			
		return $key;
	}
	
	private function cleanValue($val) {
		if ($val == "") return "";
		
		$val = str_replace( "&#032;", " ", $val );
		
		$val = str_replace( "&"            , "&amp;"         , $val );
		$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
		$val = str_replace( "-->"          , "--&#62;"       , $val );
		$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
		$val = str_replace( ">"            , "&gt;"          , $val );
		$val = str_replace( "<"            , "&lt;"          , $val );
		$val = str_replace( "\""           , "&quot;"        , $val );
		$val = preg_replace( "/\n/"        , "<br />"        , $val ); // Convert literal newlines
		$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
		$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
		$val = str_replace( "!"            , "&#33;"         , $val );
		$val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
			
		// Ensure unicode chars are OK
		$val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val );
		if($val)
		  $val = stripslashes($val);
		// Swop user inputted backslashes
			
		$val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val );
		return $val;
	}
}