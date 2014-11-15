<?php
class Request {

	protected $_detectors = array(
		'get' => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
		'post' => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
		'put' => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
		'delete' => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
		'head' => array('env' => 'REQUEST_METHOD', 'value' => 'HEAD'),
		'options' => array('env' => 'REQUEST_METHOD', 'value' => 'OPTIONS'),
		'ssl' => array('env' => 'HTTPS', 'value' => 1),
		'ajax' => array('env' => 'HTTP_X_REQUESTED_WITH', 'value' => 'XMLHttpRequest'),
		'flash' => array('env' => 'HTTP_USER_AGENT', 'pattern' => '/^(Shockwave|Adobe) Flash/'),
		'mobile' => array('env' => 'HTTP_USER_AGENT', 'options' => array(
			'Android', 'AvantGo', 'BlackBerry', 'DoCoMo', 'Fennec', 'iPod', 'iPhone', 'iPad',
			'J2ME', 'MIDP', 'NetFront', 'Nokia', 'Opera Mini', 'Opera Mobi', 'PalmOS', 'PalmSource',
			'portalmmm', 'Plucker', 'ReqwirelessWeb', 'SonyEricsson', 'Symbian', 'UP\\.Browser',
			'webOS', 'Windows CE', 'Windows Phone OS', 'Xiino'
		)),
	);


/**
 * Get the IP the client is using, or says they are using.
 *
 * @param boolean $safe Use safe = false when you think the user might manipulate their HTTP_CLIENT_IP
 *   header. Setting $safe = false will will also look at HTTP_X_FORWARDED_FOR
 * @return string The client IP.
 */
	public function clientIp($safe = true) {
		if (!$safe && env('HTTP_X_FORWARDED_FOR')) {
			$ipaddr = preg_replace('/(?:,.*)/', '', env('HTTP_X_FORWARDED_FOR'));
		} else {
			if (env('HTTP_CLIENT_IP')) {
				$ipaddr = env('HTTP_CLIENT_IP');
			} else {
				$ipaddr = env('REMOTE_ADDR');
			}
		}

		if (env('HTTP_CLIENTADDRESS')) {
			$tmpipaddr = env('HTTP_CLIENTADDRESS');

			if (!empty($tmpipaddr)) {
				$ipaddr = preg_replace('/(?:,.*)/', '', $tmpipaddr);
			}
		}
		return trim($ipaddr);
	}

/**
 * Returns the referer that referred this request.
 *
 * @param boolean $local Attempt to return a local address. Local addresses do not contain hostnames.
 * @return string The referring address for this request.
 */
	public function referer($local = false) {
		$ref = env('HTTP_REFERER');
		$forwarded = env('HTTP_X_FORWARDED_HOST');
		if ($forwarded) {
			$ref = $forwarded;
		}

		$base = '';
		if (defined('FULL_BASE_URL')) {
			$base = FULL_BASE_URL . $this->webroot;
		}
		if (!empty($ref) && !empty($base)) {
			if ($local && strpos($ref, $base) === 0) {
				$ref = substr($ref, strlen($base));
				if ($ref[0] != '/') {
					$ref = '/' . $ref;
				}
				return $ref;
			} elseif (!$local) {
				return $ref;
			}
		}
		return '/';
	}


/**
 * Check whether or not a Request is a certain type. Uses the built in detection rules
 * as well as additional rules defined with CakeRequest::addDetector(). Any detector can be called
 * as `is($type)` or `is$Type()`.
 *
 * @param string $type The type of request you want to check.
 * @return boolean Whether or not the request is the type you are checking.
 */
	public function is($type) {
		$type = strtolower($type);
		if (!isset($this->_detectors[$type])) {
			return false;
		}
		$detect = $this->_detectors[$type];
		if (isset($detect['env'])) {
			if (isset($detect['value'])) {
				return env($detect['env']) == $detect['value'];
			}
			if (isset($detect['pattern'])) {
				return (bool)preg_match($detect['pattern'], env($detect['env']));
			}
			if (isset($detect['options'])) {
				$pattern = '/' . implode('|', $detect['options']) . '/i';
				return (bool)preg_match($pattern, env($detect['env']));
			}
		}
		
		return false;
	}



/**
 * Read an HTTP header from the Request information.
 *
 * @param string $name Name of the header you want.
 * @return mixed Either false on no header being set or the value of the header.
 */
	public static function header($name) {
		$name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
		if (!empty($_SERVER[$name])) {
			return $_SERVER[$name];
		}
		return false;
	}

/**
 * Get the HTTP method used for this request.
 * There are a few ways to specify a method.
 *
 * - If your client supports it you can use native HTTP methods.
 * - You can set the HTTP-X-Method-Override header.
 * - You can submit an input with the name `_method`
 *
 * Any of these 3 approaches can be used to set the HTTP method used
 * by CakePHP internally, and will effect the result of this method.
 *
 * @return string The name of the HTTP method used.
 */
	public function method() {
		return env('REQUEST_METHOD');
	}

/**
 * Get the host that the request was handled on.
 *
 * @return string
 */
	public function host() {
		return env('HTTP_HOST');
	}

/**
 * Get the domain name and include $tldLength segments of the tld.
 *
 * @param integer $tldLength Number of segments your tld contains. For example: `example.com` contains 1 tld.
 *   While `example.co.uk` contains 2.
 * @return string Domain name without subdomains.
 */
	public function domain($tldLength = 1) {
		$segments = explode('.', $this->host());
		$domain = array_slice($segments, -1 * ($tldLength + 1));
		return implode('.', $domain);
	}

/**
 * Get the subdomains for a host.
 *
 * @param integer $tldLength Number of segments your tld contains. For example: `example.com` contains 1 tld.
 *   While `example.co.uk` contains 2.
 * @return array of subdomains.
 */
	public function subdomains($tldLength = 1) {
		$segments = explode('.', $this->host());
		return array_slice($segments, 0, -1 * ($tldLength + 1));
	}
	
	
	public function analyse() {
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
	    
	    foreach($this->cleanUrl($return) as $key => $info) {
	        $request->$key = $info;
	    }
	    
	    return true;
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

	private function cleanUrl($url) {
	    global $meta, $app, $config;;
	    
	    $url = $this->setDefault($url);
	    $url['request'] = str_replace($app->language()."/", '', $url['request']);

// 	    if(!empty($url['request'])) {
//             $match = array();
            	
//             require_once (CONTROLLER_PATH."seo/seo.entity.php");
//             $seo = new seo_entity();

//             $tmp = $seo->getByUrl($url['request']);

//             if(!empty($tmp)) {
//                 $url['params'] = $tmp["SEO"]['url'];
//             }
//         }
	    
	    $tmp = explode("/", trim($url['request'], '/'));
	    $query = array();
	
	    $index = 0;
	
	    ksort($tmp);
	    $prefix = '';
	    foreach( $tmp as $key => $value) {
	        if(empty($value)) continue;
	        
	        if(in_array($value, $config->prefix)) {
	            $prefix = $value;
	            unset($tmp[$key]);
	            break;
	        }
	    }
	    
	    $url['params'] = trim(str_replace($prefix, '', $url['request']), '/');
	    $tmp = array_values($tmp);

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
	    
	    $url['prefix']= $prefix;
	    
	    $url['branch']= empty($prefix) ? 'blank' : $prefix;
	    
	    return $url;
	}

	private function setDefault($url){
	    if(empty($url['request'])){
	        $url['request'] = "home";
	    }
	
	    return $url;
	}
	
	private function detectPrefix() {
	    
	}
}