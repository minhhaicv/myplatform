<?php

class category_entity extends Entity{
    function __construct() {
        parent::__construct('category');
        
        Helper::getBeharior('tree')->load($this->model);
    }
    
    function __destruct(){
        unset($this);
    }
    
    public function getBranch($name = '', $spacer = '', $display = 'title') {
        $root = $this->model->getBySlug($name);
        
        $alias = $this->model->getAlias();
        return $this->model->tree->flat($root[$alias]['id'], $spacer, $display);
    }
    
    public function extract($id = '', $childOnly = false, $alter = array(), $custom = array()) {
        return $this->model->tree->extract($id, $childOnly, $alter, $custom);
    }
}