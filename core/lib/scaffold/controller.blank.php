<?php
class blank extends Controller{

	function __construct($model, $view = 'frontendSkin', $option=array()){
		if(!$view) $view = 'frontendSkin';
	
		parent::__construct($model, $view, $option);
	}
        
	function navigator(){
		global $request;
		
		switch($request->query['action']) {
			case 'detail':
					$this->loadDetail($request->query[0]);
				break;
			case 'category':
					$this->loadCategory($request->query[0]);
				break;
			default :
					$this->loadDefault();
				break;
		}
	}
	
	function loadDefault() {
		$this->output = $this->view->loadDefault();
	}
}