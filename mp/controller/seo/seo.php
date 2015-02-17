<?php

class seo extends Model {

    public function __construct($table = 'seo', $alias = 'seo'){
        parent::__construct($table, $alias);
    }

    public function __destruct(){
        unset($this);
    }
}
