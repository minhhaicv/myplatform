<?php

class seoEntity extends entity{
    function __construct() {
        parent::__construct('seo');
    }

    function __destruct(){
        unset($this);
    }

    public function instance($id = 0) {
        if($id) {
            $alias = $this->model->getAlias();
            $fields =  "{$alias}.id, {$alias}.alias, {$alias}.canonical, {$alias}.title, {$alias}.keyword, {$alias}.desc";

            $target = $this->model->findById($id, $fields);
        } else {
            $target = $this->model->init();
        }

        return $target;
    }

    public function save($data = array()) {
        return $this->model->save($data);
    }
}