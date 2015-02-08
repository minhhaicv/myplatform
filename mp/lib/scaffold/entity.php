<?php
class entity {
    function __construct($model = '', $table = '', $alias = '') {
        if ($model) {
            $this->model = Helper::load($model, 'model', compact('table', 'alias'));
        }
    }
}