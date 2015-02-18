<?php
class group extends Model {

    function __construct($table = 'group', $alias = 'group'){
        parent::__construct($table, $alias);
    }

    function __destruct(){
        unset($this);
    }
}