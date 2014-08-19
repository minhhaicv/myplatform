<?php
class Controller{
		
	function __construct($model = '', $view = ''){
		global $template, $app;

		if($view) 
		    $this->view = $template->load($view);
		
		if($model){
			$path = CORE_PATH.$model."/".$model.".php";

			$app->requireFile($path);

			$this->model = new $model();	
		}
	}
	
	function getOutput() {
		return $this->output;
	}
		
	function setModel($model){
		$this->model = $model;
	}
	
	function getLayout(){
		return $this->layout;
	}
	
	function getView(){
		return $this->view;
	}
	
	protected $view		= NULL;
	protected $output	= NULL;
	protected $model	= NULL;
	protected $layout 	= true;
}