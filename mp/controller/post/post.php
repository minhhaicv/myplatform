<?php
class post extends Model {

    function __construct($table = 'post', $alias = 'post'){
        parent::__construct($table, $alias);
    }

    function __destruct(){
        unset($this);
    }
}