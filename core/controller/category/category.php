<?php

class category extends Model {

	function __construct(){
		parent::__construct('category', 'Category');            
	}
	
	function __destruct(){
		unset($this);
	}
	
	public function getBySlug($slug='', $option = array()) {
	    $alias = $this->getAlias();
	     
	    $option = array (
	                    'select' => "{$alias}.id",
	                    'where' => "{$alias}.slug = '{$slug}' AND {$alias}.status > 0 AND {$alias}.deleted = 0",
	    );
	     
	    return $this->find($option, 'first');
	}
}
