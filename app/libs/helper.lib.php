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
    
    
    public function getApp() {
        global $app;
        if (is_null($app)) {
            require_once(LIBS_PATH . "app.lib.php");
            $app = new App();
        }
        
        return $app;
    }
    
    public function getTemplate() {
        self::getApp()->requireFile(LIBS_PATH . "tpl-engine/pdf.php");
        return new Template(true);
    }
    
    public function getDB() {
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
    
    public function getHelper($helpername = ''){
        $path = LIBS_PATH."helper/".$helpername.'.helper.php';
    
        $hname = $helpername.'Helper';
        require_once ($path);
        $name = new $hname();
        return $name;
    }
    
    public function getLib($libname = ''){
        $path = LIBS_PATH.$libname.'.lib.php';
    
        $hname = 'PDF'.$libname;
        require_once ($path);
        return new $hname();
    }
    
    public function getTrait($traitname = ''){
        $path = LIBS_PATH."trait/".$traitname.'.trait.php';
    
        $hname = $traitname.'Trait';
        require_once ($path);
        return new $hname();
    }
    
    ///////////////////////////////////////////////
	public function getRequest(){
		global $request;
		if (is_null($request)) {
			require_once(LIBS_PATH . "request.lib.php");
			$request = new Request();
		}
		return $request;
	}
	
	public function getView() {
		global $view;
		if (is_null($view)) {
			self::getApp()->requireFile(LIBS_PATH . "view.lib.php");
			$view = new View();
		}
		return $view;
	}

	
	public function getSession() {
	    global $session;
	    if (is_null($session)) {
	        self::getApp()->requireFile(LIBS_PATH . "session.lib.php");
	        $session = new Session();
	    }
	    return $session;
	}
	
	
	
	
	
	
	public function getHtml() {
	    global $html;
	    if (is_null($html)) {
	        self::getApp()->requireFile(LIBS_PATH . "html.lib.php");
	        $html = new Html();
	    }
	    return $html;
	}
	
}