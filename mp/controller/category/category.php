<?php

class category extends Model {

    function __construct($table = 'category', $alias = 'category') {
        parent::__construct($table, $alias);

        Helper::get('tree', 'behavior')->load($this);
    }

    public function __destruct(){
        unset($this);
    }

    public function getBySlug($slug='', $type = 'first', $option = array()) {
        $alias = $this->getAlias();

        $default = array(
                        'select' => "{$alias}.id",
                        'where' => "{$alias}.slug = '{$slug}' AND {$alias}.status > 0 AND {$alias}.deleted = 0",
        );

        if (!empty($option)) {
            $default = array_merge($default, $option);
        }

        return $this->find($default, $type);
    }

    public function getSuperRoot() {
        $alias = $this->getAlias();

        $option = array(
                        'select' => "{$alias}.id",
                        'where' => "{$alias}.parent_id = 0 AND {$alias}.status > 0 AND {$alias}.deleted = 0",
        );

        $tmp = $this->find($option, 'first');

        return empty($tmp[$alias]) ? array('id' => 0) : $tmp[$alias];
    }
}