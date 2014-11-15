<?php
class config {
    public $base_url = "";
    
    public $vars = array(
                'board_url'     => 'http://mp.me',
                'debug'         => 2,
        );
    
    public $view = array(
                       'frontend' => 'frontend', 
    	               'backend'   => 'backend',
    );
    
    public $prefix = array('admin');
}