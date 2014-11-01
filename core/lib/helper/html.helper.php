<?php
class htmlHelper{
    
    public function __construct() {
        global $config, $app;
        
        $this->path = $config->vars['cdn']."/".$app->skin.'/';
        
        $this->js = array(array(), array());
        $this->css = array(array(), array());
    }
    
    public function css($css = array(), $position=0, $type = 'internal'){
        foreach($css as $content) {
            $this->css[$position][] = array('type' => $type, 'content' => $content);
        }
    }
    
    public function loadCSS() {
        return $this->_loadCSS();
    }
    
    public function js($script = array(), $position = 1, $type = 'internal') {
        foreach($script as $content) {
            $this->js[$position][] = array('type' => $type, 'content' => $content);
        }
    }
    
    public function loadJS() {
        return $this->_loadJS();
    }
    
    private function _loadJS() {
        $html = array();
        
        $map = array(
                    'external' => '',
                    'internal' => $this->path.'js/',
                    'package'  => $this->path.'package/'
                );
        
        foreach( $this->js as $position => $list) {
            if(empty($list)) continue;
            
            $html[$position] = '';
            foreach($list as $item) {
                if($item['type'] == 'inline') {
                    $html[$position] .= '<script type="text/javascript">'.$item['content']."</script>";
                } else {
                    $src = $map[$item['type']].$item['content'];
                    $html[$position] .= '<script type="text/javascript" src="'.$src.'.js"></script>';
                }
            }
        }
        
        return $html;
    }
    
    public function _loadCSS() {
        $html = array();
        
        $map = array(
                        'internal' => $this->path.'css/',
                        'package'  => $this->path.'package/'
        );
        
        foreach( $this->css as $position => $list) {
            if(empty($list)) continue;
        
            $html[$position] = '';
            foreach($list as $item) {
                $src = $map[$item['type']].$item['content'];
                
                $html[$position] .= '<link type="text/css" rel="stylesheet" href="'.$src.'.css" />';
            }
        }
        
        return $html;
    }
}