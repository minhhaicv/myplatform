<?php

class category_entity extends entity{
    function __construct($model = 'category', $table = 'category', $alias = 'category') {
        parent::__construct($model, $table, $alias);
    }

    function __destruct(){
        unset($this);
    }

    public function superRoot() {
        return $this->model->getSuperRoot();
    }

    public function root($branch = '') {
        $tmp = $this->model->getBySlug($branch, 'list');

        if (empty($tmp)) return 0;
        return current($tmp);
    }

    public function branch($branch = '', $spacer = '', $display = 'title', $level = 2) {
        return $this->model->tree->flat($this->root($branch), $spacer, $display, $level);
    }

    public function extract($id = '', $childOnly = false, $option = array()) {
        return $this->model->tree->extract($id, $childOnly, $option);
    }
}