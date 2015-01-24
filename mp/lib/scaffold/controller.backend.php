<?php

class backend extends controller{

    public function __construct($model, $table = '', $alias = '', $template = ''){
        parent::__construct($model, $table, $alias, $template);
    }

    public function getSEOInstance($id = 0) {
        global $app;

        $entity = $app->load('seo', 'entity');

        return $entity->instance($id);
    }

    public function saveSEOInstance($data = array(), $target = array(), $type = 'detail', &$lastInsertId = 0) {
        global $app;

        $entity = $app->load('seo', 'entity');

        $data['url'] = Helper::get("url")->seo($target, $type);

        $data['foreign_key']      = $target['id'];
        $data['foreign_category'] = $target['category_id'];

        $lastInsertId = 0;
        if ($entity->save($data)) {
            if(empty($data['id']))
                $lastInsertId = $entity->model->lastInsertId();
            else $lastInsertId = $data['id'];

            return true;
        }

        return false;
    }
}