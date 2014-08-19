<?php

class category_entity extends Entity{
    function __construct() {
        parent::__construct('category');
        $this->model = Helper::getTrait('tree')->load($this->model);
    }
    
    function __destruct(){
        unset($this);
    }
	
    
    public function getChildren($id = '', $childOnly = false, $custom = array()) {
        return $this->model->getChildren($id, $childOnly, $custom);
    }
    
    public function getBySlug($slug = '', $childOnly = false, $custom = array()) {
        return $this->model->getBySlug($slug, $childOnly, $custom);
    }
}