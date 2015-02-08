<?php
class user extends Model {

    function __construct($table = 'user', $alias = 'user'){
        parent::__construct($table, $alias);
    }

    function __destruct(){
        unset($this);
    }
}