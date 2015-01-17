<?php
class entity {
    function __construct($model = '', $table = null, $alias = null) {
        if ($model) {
            $this->model = Helper::getApp()->load($model, 'model', compact($table, $alias));
        }
    }
}