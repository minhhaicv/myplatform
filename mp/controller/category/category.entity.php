<?php

class category_entity extends entity{
    function __construct() {
        parent::__construct('category');

        Helper::get('tree', 'behavior')->load($this->model);
    }

    function __destruct(){
        unset($this);
    }

    public function root($branch = '') {
        $tmp = $this->model->getBySlug($branch, 'list');

        return current($tmp);
    }

    public function branch($branch = '', $spacer = '', $display = 'title') {
        return $this->model->tree->flat($this->root($branch), $spacer, $display);
    }

    public function extract($id = '', $childOnly = false, $option = array()) {
        return $this->model->tree->extract($id, $childOnly, $option);
    }
}