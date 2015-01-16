<?php
class entity {
    function __construct($model = '') {
        if ($model) {
            global $app;

            $this->model = $app->load('model', $model);
        }
    }

    public function getLastest($limit = 10) {
        global $app;

        $app->import('entity', array('category'));
        $entity = new category_entity();
        $category = $entity->getBySlug('post');

        if ($category) {
            $alias = $this->model->getAlias();

            $catIds = implode(',', Helper::getHelper('hash')->extract($category, 'Category'));

            $option = array (
                        'select'=>"{$alias}.id, {$alias}.title, {$alias}.content, File.*, SEO.*",
                        'where' => "{$alias}.status > 0 AND {$alias}.category_id IN (" . $catIds . ") AND {$alias}.deleted = 0",
                        'order' => "{$alias}.id desc",
                        'limit' => $limit,

                        'joins' => array (
                                        array (
                                            'file' => array (
                                                            'alias' => 'File',
                                                            'type' => 'LEFT',
                                                            'condition' => array (
                                                                                "{$alias}.file" => "File.id"
                                                                            )
                                                     ),
                                            'seo' => array (
                                                            'alias' => 'SEO',
                                                            'type' => 'LEFT',
                                                            'condition' => array (
                                                                                "{$alias}.seo" => "SEO.id"
                                                                            )
                                                     )
                                        )
                                    )
            );

            return $this->model->find($option);
        }

        return array();
    }

}