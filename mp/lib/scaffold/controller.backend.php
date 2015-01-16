<?php

class backend extends controller{

    public function __construct($model){
        parent::__construct($model);
    }


    public function getSEOInstance($id = 0) {
        global $app;

        $entity = $app->load('entity', 'seo');

        return $entity->instance($id);
    }

    public function saveSEOInstance($data = array(), $target = array(), $type = 'detail', &$lastInsertId = 0) {
        global $app;

        $entity = $app->load('entity', 'seo');

        $data['url'] = Helper::get("url")->seo($target, $type);

        $data['foreign_key'] = $target['id'];
        $data['foreign_category'] = $target['category_id'];

        $lastInsertId = 0;
        if ($entity->save($data)) {
            $primaryKey = $entity->model->getPrimaryKey();
            $lastInsertId = empty($target[$primaryKey]) ? $entity->model->lastInsertId() : $target[$primaryKey];

            return true;
        }

        return false;
    }
}