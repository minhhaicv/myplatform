<?php
Helper::uses('category', 'controller');

class menuBackend extends categoryBackend {

    public function __construct() {
        parent::__construct('category', 'menu', 'menu', 'menu');
    }

    public function add($branch = '') {
        global $request;

        $option = array();
        $alias = $this->model->getAlias();

        $target = $this->model->init();
        if (!empty($request->data[$alias])) {
            $flag = $this->_save($request->data);

            if ($flag) {
                return $this->redirect(Helper::get('url')->category('branch', compact('branch')));
            }

            $target = $request->data;
        }

        $option['category'] = $this->getParent($branch);

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function edit($branch = '', $id = 0) {
        global $request;

        $id = intval($id);
        $option = array();

        $alias = $this->model->getAlias();

        if (!empty($request->data[$alias])) {
            $this->_save($request->data);
        }

        $fields =  "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.parent_id, {$alias}.index, {$alias}.status, {$alias}.url";

        $target = $this->model->findById($id, $fields);
        if (empty($target)) {
            return $this->redirect(Helper::get('url')->notfound());
        }

        $option['category'] = $this->getParent($branch);

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function branch($branch = '') {
        $alias = $this->model->getAlias();

        $root = $this->entity->root($branch);

        $data = array('list' => array());
        $option = array(
                    'select' => "{$alias}.id, {$alias}.title, {$alias}.url, {$alias}.modified",
        );

        $data['list'] = $this->entity->extract($root, true, $option);
        $this->entity->model->tree->indent($data['list'], '&nbsp;&nbsp;&nbsp;&nbsp;', 'title');

        $option = compact('branch');

        $this->render('branch', compact('data', 'option'));
    }
}