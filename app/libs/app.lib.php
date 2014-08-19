<?php
class App {
    
    public function __construct() {
        $this->skin = APPLICATION_TYPE == 'backend' ? 'backend' : 'chuyentrang';
        $this->language = 'vi';
        
        $this->basic();
    }
    
    public function site(){
        return CONTROLLER_PATH.'sites/'.$this->skin.'/';
    }
    
    public function skin() {
        return $this->skin;
    }
    
    public function language() {
        return $this->language;
    }


    public function basic() {
        global $config;
    
        $flag = array('backend'=>'admin', 'frontend'=>'index');
        if(1) {
            $config->base_url = $config->vars['board_url'].'/';
            if(APPLICATION_TYPE=='backend'){
                $flag['backend'] = 'backend';
                $config->base_url = $config->vars['board_url'].'/'.$flag[APPLICATION_TYPE].'/';
            }
        } else $config->base_url = $config->vars['board_url'].'/'.$flag[APPLICATION_TYPE].'.php?request=';
    
        $config->vars['img'] = $config->vars['cdn']."/".$this->skin()."/img";
    }
    
	public function import($type = '', $name = array()) {
	    global $config;
	    
	    $site = $this->site();
	    
		foreach($name as $value) {
		    $path = $value.'/'.$value.'.'.$type.'.php';
		    
			if(file_exists($site.$path)) $path = $site.$path;
            elseif(file_exists(CONTROLLER_PATH.'cores/'. $path)) $path = CONTROLLER_PATH.'cores/'. $path;
           
			$this->requireFile($path);
		}
	}
	
	public function requireFile($filePath="", $requireOnce=true) {
		if(!file_exists($filePath)) {
			throw new Exception(sprintf('The file <b>%s</b> does not exist!', $filePath));
		}
		
		if($requireOnce){
			try{
				return require_once($filePath);
			}catch(Exception $e){
				throw new Exception(sprintf('Cannot import <b>%s</b> by require_once!', $filePath));
			}
		} else {
			try{
				return require($filePath);
			}catch(Exception $e){
				throw new Exception(sprintf('Cannot import <b>%s</b> by require!', $filePath));
			}
		}
	}

    public function initExecutor() {
        global $request;
        $module = $request->query['module'];
        
        $path = $module . "/" . $module . ".".APPLICATION_TYPE.".php";
        
        if(file_exists($this->site().$path)) $path = $this->site().$path;
        elseif(file_exists(CONTROLLER_PATH.'cores/'. $path)) $path = CONTROLLER_PATH.'cores/'. $path;
        
        $this->requireFile($path);
        $class = $module."_".APPLICATION_TYPE;
        if(!class_exists($class))
            throw new Exception(sprintf('The class <b>%s</b> does not exist in the file <b>%s</b>!', $runme_class, $runme_path));
       
        return new $class();
    }

    public function finish() {
        Helper::getDB()->disconnect();
        exit();
    }

    function redirect($url, $type = '301'){
        global $config;
        $url = str_replace("&amp;", "&", $url);
    
        switch($type){
        	case 'html':
        	    @flush();
        	    echo ("<html><head><meta http-equiv='refresh' content='2; url=".$url."'></head><body></body></html>");
        	    break;
        	case 'refresh':
        	    header("refresh: 2; url=".$url);
        	    break;
        	case '302':
        	    @header("HTTP/1.1 302 Moved Permanently");
        	    @header("location: ".$url);
        	    break;
        	case '404':
        	    @header('HTTP/1.1 404 Not Found');
        	    @header("location: ".$url);
        	    break;
        	default:
        	    @header("HTTP/1.1 301 Moved Permanently");
        	    @header("location: ".$url);
        	    break;
        }
    
        $this->finish();
    }
}