<?php
class Request {

// 	protected $_detectors = array(
// 		'get' => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
// 		'post' => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
// 		'put' => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
// 		'delete' => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
// 		'head' => array('env' => 'REQUEST_METHOD', 'value' => 'HEAD'),
// 		'options' => array('env' => 'REQUEST_METHOD', 'value' => 'OPTIONS'),
// 		'ssl' => array('env' => 'HTTPS', 'value' => 1),
// 		'ajax' => array('env' => 'HTTP_X_REQUESTED_WITH', 'value' => 'XMLHttpRequest'),
// 		'flash' => array('env' => 'HTTP_USER_AGENT', 'pattern' => '/^(Shockwave|Adobe) Flash/'),
// 		'mobile' => array('env' => 'HTTP_USER_AGENT', 'options' => array(
// 			'Android', 'AvantGo', 'BlackBerry', 'DoCoMo', 'Fennec', 'iPod', 'iPhone', 'iPad',
// 			'J2ME', 'MIDP', 'NetFront', 'Nokia', 'Opera Mini', 'Opera Mobi', 'PalmOS', 'PalmSource',
// 			'portalmmm', 'Plucker', 'ReqwirelessWeb', 'SonyEricsson', 'Symbian', 'UP\\.Browser',
// 			'webOS', 'Windows CE', 'Windows Phone OS', 'Xiino'
// 		)),
// 	);


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

    public function getBaseUrl() {
        return Helper::config()->vars['board_url'];
    }

    public function conduct() {
        global $request;

        $input = array();

        $output = $_GET;

        if (empty($output)) {
           $this->__alternate($output);
        } else {
            $output['request'] = strtolower($output['request']);
        }

        $output = $this->__formatGet($output);
        if (!empty($_POST)) {
            $output['data'] = $_POST;
        }

        $output = Sanitize::clean($output);

        foreach ($output as $key => $info) {
            $request->$key = $info;
        }

        Helper::scaffold(array("controller.".$output['channel']));

        return true;
    }


    private function __formatGet($input) {
        $prefix = $channel = '';
        $query  = $name = $param = array();

        $request = $input['request'];

        $prefixList = Helper::config()->prefix;

        foreach ($input as $key => $value) {
            if ($key == 'request') {
                $value = trim($value, '/');

                if (strpos(trim($value, '/'), '/') !== false) {
                    $query = array_merge($query, explode('/', $value));

                    foreach ($query as $k => $v) {
                        if (in_array($v, $prefixList)) {
                            $prefix = $v;
                            unset($query[$k]);

                            continue;
                        }

                        if (strpos($v, ':') !== false) {
                            $tmp = explode(':', $v);

                            if (count($tmp) > 1) {
                                $name[$tmp[0]] = $tmp[1];
                            }
                        }
                    }

                    continue;
                }

                $query[] = $value;
            }

            if (strpos($key, ':') !== false) {
                $tmp = explode(':', $key);
                if (count($tmp) > 1) {
                    $param[$tmp[0]] = $tmp[1];
                    continue;
                }
            }

            $param[$key] = $value;
        }

        $query = array_values($query);

        $query['module'] = $query[0];
        $query['action'] = '';

        if (empty($query[1]) === false && strpos($query[1], ':') === false) {
            $query['action'] = $query[1];
        }

        $channel = empty($prefix) ? 'blank' : $prefix;

        return compact('request', 'query', 'name', 'param', 'prefix', 'channel');
    }

    private function __alternate(&$input = array()) {
        if(empty($url['request'])){
            $input['request'] = "home";
        }
    }
}