<?php
class seo extends Model {

	function __construct(){
		parent::__construct('seo', 'seo');            
	}
	
	function __destruct(){
		unset($this);
	}
}