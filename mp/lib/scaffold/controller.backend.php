<?php

class backend extends controller{

    public function __construct($model, $table = '', $alias = '', $template = '') {
        parent::__construct($model, $table, $alias, $template);
    }

    public function getSEOInstance($id = 0) {
        return Helper::load('seo', 'entity')->instance($id);
    }

    public function saveSEOInstance($data = array(), $target = array(), $type = 'detail', &$lastInsertId = 0) {
        $entity = Helper::load('seo', 'entity');

        $data['url'] = $entity->realUrl($type, $target);

        $data['foreign_key']      = $target['id'];
        $data['foreign_category'] = $target['category_id'];

        $lastInsertId = 0;
        if ($entity->save($data)) {
            if (empty($data['id'])) {
                $lastInsertId = $entity->model->lastInsertId();
            } else {
                $lastInsertId = $data['id'];
            }

            return true;
        }

        return false;
    }
}