<?php
class Helper {

    static function getApp() {
        global $app;
        if (is_null($app)) {
            require_once(LIB_PATH . "app.lib.php");
            $app = new App();
        }
        
        return $app;
    }
    
    static function getTemplate() {
//         self::getApp()->requireFile(LIB_PATH . "tpl-engine/pdf.php");
//         return new Template(true);
        self::getApp()->requireFile(LIB_PATH . "tpl-engine/twig/Autoloader.php");
        Twig_Autoloader::register();
//         $twig = new Twig_Environment($loader, array('debug' => true));
        //$loader = new Twig_Loader_Filesystem('webroot/blank/view');
        
        $loader = new Twig_Loader_String();
        
        return $twig = new Twig_Environment($loader, array(
                        'cache' => 'tmp/cache/view/blank',
                        'debug' => true,
                        'strict_variables' => true
        ));
    }
    
    static function getDB() {
        global $db;
    
        if(is_null($db)){
            require_once (CONFIG_PATH . 'database' . ".php");
            $dbconfig = new DATABASE_CONFIG();
            
            $config = $dbconfig->default;
    
            require_once (CORE_PATH . 'database/instance/' . $config['driver'] . ".php");
            	
            $dbName = ucfirst($config['driver']);
            $db = new $dbName($config);
        }
    
        return $db;
    }
    
    static function getHelper($helpername = ''){
        $path = LIB_PATH."helper/".$helpername.'.helper.php';
    
        $hname = $helpername.'Helper';
        require_once ($path);
        $name = new $hname();
        return $name;
    }
    
    static function getLib($name = ''){
        $path = LIB_PATH.$name.'.lib.php';
    
        require_once ($path);
        
        return new $name();
    }
    
    static function getTrait($name = ''){
        $path = LIB_PATH."trait/".$name.'.trait.php';
    
        $name = $name.'Trait';
        require_once ($path);
        
        return new $name();
    }
    
    static function getBeharior($name = ''){
        $path = LIB_PATH."core/behavior/".$name.'.behavior.php';
    
        $name = $name.'Behavior';
        
        require_once ($path);
        
        return new $name();
    }
    
    ///////////////////////////////////////////////
	static function getRequest(){
		global $request;
		if (is_null($request)) {
			require_once(LIB_PATH . "request.lib.php");
			$request = new Request();
		}
		return $request;
	}
	
	static function getView() {
		global $view;
		if (is_null($view)) {
			self::getApp()->requireFile(LIB_PATH . "view.lib.php");
			$view = new View();
		}
		return $view;
	}

	
	static function getSession() {
	    global $session;
	    if (is_null($session)) {
	        self::getApp()->requireFile(LIB_PATH . "session.lib.php");
	        $session = new Session();
	    }
	    return $session;
	}
	
	
	static function getHtml() {
	    global $html;
	    if (is_null($html)) {
	        self::getApp()->requireFile(LIB_PATH . "html.lib.php");
	        $html = new Html();
	    }
	    return $html;
	}
	
}