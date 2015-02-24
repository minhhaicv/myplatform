<?php
Helper::uses('category', 'entity');
class menuEntity extends categoryEntity{
    public function __construct($model = 'category', $table = 'menu', $alias = 'menu') {
        parent::__construct($model, $table, $alias);
    }


    public function retrieve($branch = '') {
        $alias = $this->model->getAlias();

        $root = $this->root($branch);

        $option = array(
                        'select' => "{$alias}.id, {$alias}.title, {$alias}.url, {$alias}.caption",
        );

        $data = $this->extract($root, true, $option);

        return Hash::combine($data, '{n}.menu.id', '{n}.menu');
    }
}