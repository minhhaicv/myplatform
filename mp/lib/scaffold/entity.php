<?php
class entity {
    function __construct($model = '', $table = '', $alias = '') {
        if ($model) {
            $this->model = Helper::getApp()->load($model, 'model', compact('table', 'alias'));
        }
    }
}