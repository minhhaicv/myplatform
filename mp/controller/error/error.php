<?php
class error extends Model {

    public function __construct($table = 'log_error', $alias = 'log_error'){
        parent::__construct($table, $alias);
    }

}