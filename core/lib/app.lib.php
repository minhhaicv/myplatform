<?php
class App {
    
    public function __construct() {
        $this->skin = '';
        $this->language = 'vi';
        
        $this->basic();
    }
    
    public function skin() {
        return $this->skin;
    }
    
    public function language() {
        return $this->language;
    }


    public function basic() {
        global $config;
    
        $config->base_url = $config->vars['board_url'].'/';
    
//         $config->vars['img'] = $config->vars['cdn']."/".$this->skin()."/img";
    }
    
	public function import($type = '', $name = array()) {
	    global $config;
	    
	    $map = array(
	    	      'scaffold' => LIB_PATH . 'scaffold/'
	    );
	    
		foreach($name as $value) {
		    $path = $map[$type].$value.'.php';
            
			$this->requireFile($path);
		}
	}
	
	public function requireFile($filePath="", $once=true) {
		if(!file_exists($filePath)) {
			throw new Exception(sprintf('The file [%s] does not exist!', $filePath));
		}
		
		if($once){
			try{
				return require_once($filePath);
			}catch(Exception $e){
				throw new Exception(sprintf('Cannot import [%s] by require_once!', $filePath));
			}
		} 
		
		try{
		    return require($filePath);
		}catch(Exception $e){
		    throw new Exception(sprintf('Cannot import [%s] by require!', $filePath));
		}
	}

    public function initExecutor() {
        global $request;
     
        $branch = $request->branch;
        $module = $request->query['module'];
        $path = $module . "/" . $module . ".".$branch.".php";
        
        $path = CORE_PATH . "controller/" . $path;
        
        $this->requireFile($path);
        $class = $module."_".$branch;
        if(!class_exists($class))
            throw new Exception(sprintf('The class <b>%s</b> does not exist in the file [%s]!', $runme_class, $runme_path));
       
        return new $class();
    }

    public function finish() {
        Helper::getDB()->disconnect();
        exit();
    }
}