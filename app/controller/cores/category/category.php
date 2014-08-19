<?php

class category extends Model {

	function __construct(){
		parent::__construct('category', 'Category');            
	}
	
	function __destruct(){
		unset($this);
	}
}
