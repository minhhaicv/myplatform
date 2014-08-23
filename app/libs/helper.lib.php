<?php
class Helper {

    public function soap() {
        $options = array(
                                'location' => 'http://localhost/server/api.php',
                                'uri'      => 'http://localhost/server/'
                );
        
        
                 $hdl = new SoapClient(null, $options);
                 return $hdl->query(999);
                 return;
    }
    
    
    static function getApp() {
        global $app;
        if (is_null($app)) {
            require_once(LIBS_PATH . "app.lib.php");
            $app = new App();
        }
        
        return $app;
    }
    
    static function getTemplate() {
        self::getApp()->requireFile(LIBS_PATH . "tpl-engine/pdf.php");
        return new Template(true);
    }
    
    static function getDB() {
        global $db;
    
        if(is_null($db)){
            require_once (CONFIG_PATH . 'database' . ".php");
            $dbconfig = new DATABASE_CONFIG();
            
            $config = $dbconfig->default;
    
            require_once (APP_PATH . 'database/instance/' . $config['driver'] . ".php");
            	
            $dbName = ucfirst($config['driver']);
            $db = new $dbName($config);
        }
    
        return $db;
    }
    
    static function getHelper($helpername = ''){
        $path = LIBS_PATH."helper/".$helpername.'.helper.php';
    
        $hname = $helpername.'Helper';
        require_once ($path);
        $name = new $hname();
        return $name;
    }
    
    static function getLib($name = ''){
        $path = LIBS_PATH.$name.'.lib.php';
    
        $name = 'PDF'.$name;
        require_once ($path);
        
        return new $name();
    }
    
    static function getTrait($name = ''){
        $path = LIBS_PATH."trait/".$name.'.trait.php';
    
        $name = $name.'Trait';
        require_once ($path);
        
        return new $name();
    }
    
    static function getBeharior($name = ''){
        $path = LIBS_PATH."core/behavior/".$name.'.behavior.php';
    
        $name = $name.'Behavior';
        
        require_once ($path);
        
        return new $name();
    }
    
    ///////////////////////////////////////////////
	static function getRequest(){
		global $request;
		if (is_null($request)) {
			require_once(LIBS_PATH . "request.lib.php");
			$request = new Request();
		}
		return $request;
	}
	
	static function getView() {
		global $view;
		if (is_null($view)) {
			self::getApp()->requireFile(LIBS_PATH . "view.lib.php");
			$view = new View();
		}
		return $view;
	}

	
	static function getSession() {
	    global $session;
	    if (is_null($session)) {
	        self::getApp()->requireFile(LIBS_PATH . "session.lib.php");
	        $session = new Session();
	    }
	    return $session;
	}
	
	
	
	
	
	
	static function getHtml() {
	    global $html;
	    if (is_null($html)) {
	        self::getApp()->requireFile(LIBS_PATH . "html.lib.php");
	        $html = new Html();
	    }
	    return $html;
	}
	
}