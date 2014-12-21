<?php

class category extends Model {

    function __construct(){
        parent::__construct('category', 'category');
    }

    function __destruct(){
        unset($this);
    }

    public function getBySlug($slug='', $type = 'first') {
        $alias = $this->getAlias();

        $option = array (
                        'select' => "{$alias}.id",
                        'where' => "{$alias}.slug = '{$slug}' AND {$alias}.status > 0 AND {$alias}.deleted = 0",
        );

        return $this->find($option, $type);
    }
}
