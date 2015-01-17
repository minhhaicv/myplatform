<?php

class category_backend extends backend {

    public function __construct($model = 'category', $table = 'category', $alias = 'category', $template = '') {
        parent::__construct($model, $table, $alias, $template);

        $this->entity = Helper::getApp()->load('category', 'entity');
    }

    public function navigator(){
        global $request;

        switch($request->query['action']){
            case 'add':
                    $this->add($request->query[2]);
                break;
            case 'edit':
                     $this->edit($request->query[2], $request->query[3]);
                break;
            case 'branch':
                    $this->branch($request->query[2]);
                break;
            case 'delete':
                    $this->delete();
            default:
                    $this->main();
                break;
        }
    }

    public function delete() {
        global $request;

        if(!empty($request->data[$this->model->getAlias()])) {
            $target = implode(',', $request->data[$this->model->getAlias()]);

            $condition = 'id IN (' . $target . ')';

            $this->model->delete($condition);
        }

        if(empty($request->query[2]))
            return $this->redirect(Helper::get('url')->category('main'));

        return $this->redirect(Helper::get('url')->category('branch', array("branch" => $request->query[2])));
    }

    public function main() {
        global $request;

        $data = array('list' => array());

        $alias  = $this->model->getAlias();

        $super  = $this->entity->superRoot();

        $target = $this->model->init();

        if(!empty($request->data[$alias])) {
            $flag = $this->saveBranchRoot($super);

            if($flag) return $this->redirect(Helper::get('url')->category('main'));
        } elseif(!empty($request->name['edit'])) {
            $fields =  "{$alias}.id, {$alias}.title, {$alias}.slug";

            $target = $this->model->getBySlug($request->name['edit'], 'first', array("select" => $fields));

            if(empty($target)) {
                return $this->redirect(Helper::get('url')->notfound());
            }
        }

        $option = array (
                            'select' => "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.modified",
                            'where'  => "{$alias}.parent_id = {$super['id']}"
                    );

        $data['list'] = $this->model->find($option);

        $option = array();
        $this->render('main', compact('target', 'data', 'option'));
    }

    private function saveBranchRoot($super, $lastInsertId = 0) {
        global $request;

        $alias  = $this->model->getAlias();
        $request->data[$alias]['parent_id'] = $super['id'];

        return $this->_save($request->data, $lastInsertId);
    }

    public function branch($branch = '') {
        $alias = $this->model->getAlias();

        $root = $this->entity->root($branch);

        $data = array('list' => array());
        $option = array (
                        'select' => "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.modified",
        );

        $data['list'] = $this->entity->extract($root, true, $option);
        $this->entity->model->tree->indent($data['list'], '&nbsp;&nbsp;&nbsp;&nbsp;', 'title');

        $option = compact('branch');

        $this->render('branch', compact('data', 'option'));
    }

    public function add($branch = '') {
        global $app, $request;

        $option = array();
        $alias = $this->model->getAlias();

        $target = $this->model->init();
        if(!empty($request->data[$alias])) {
            $flag = $this->_add();

            if($flag) {
                return $this->redirect(Helper::get('url')->category('branch', compact('branch')));
            }

            $target = $request->data;
        }

        $option['category'] = $this->getParent($branch);

        $target = array_merge($target, $this->getSEOInstance());

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function _add(&$affact = array()) {
        global $request;

        $lastInsertId = 0;

        $this->_save($request->data, $lastInsertId);

        $request->data[$this->model->getAlias()]['id'] = $request->data[$this->model->getAlias()]['category_id'] = $lastInsertId;
        $affact[$this->model->getAlias()] = $request->data[$this->model->getAlias()]['seo_id'] = $lastInsertId;

        $flag = $this->saveSEOInstance($request->data['seo'], $request->data[$this->model->getAlias()], 'detail', $lastInsertId);
        if($flag == false) return false;

        $affact['seo'] = $lastInsertId;

        $option = array(
                        'fields' => array('seo_id' => $lastInsertId),
                        'where' => "id = ".$affact[$this->model->getAlias()]
        );

        return $this->model->update($option);
    }

    public function edit($branch = '', $id = 0) {
        global $request, $app;

        $id = intval($id);
        $option = array();

        $alias = $this->model->getAlias();

        if(!empty($request->data[$alias])) {
            $this->_edit();
        }

        $fields =  "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.parent_id, {$alias}.index, {$alias}.status, {$alias}.seo_id";

        $target = $this->model->findById($id, $fields);
        if(empty($target)) {
            return $this->redirect(Helper::get('url')->notfound());
        }

        $option['category'] = $this->getParent('post');
        $target = array_merge($target, $this->getSEOInstance($target[$alias]['seo_id']));

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function _edit(&$affact = array()) {
        global $request;

        $lastInsertId = 0;

        $this->_save($request->data, $lastInsertId);

        $request->data[$this->model->getAlias()]['category_id'] = $lastInsertId;
        $affact[$this->model->getAlias()] = $request->data[$this->model->getAlias()]['seo_id'] = $lastInsertId;

        $flag = $this->saveSEOInstance($request->data['seo'], $request->data[$this->model->getAlias()], 'detail', $lastInsertId);
        if($flag == false) return false;

        $affact['seo'] = $lastInsertId;

        $option = array(
                        'fields' => array('seo_id' => $lastInsertId),
                        'where' => "id = ".$affact[$this->model->getAlias()]
        );

        return $this->model->update($option);
    }

    private function _save($data = array(), &$lastInsertId = 0) {
        $flag = $this->model->save($data[$this->model->getAlias()]);
        if($flag == false) return false;

        $lastInsertId = empty($data[$this->model->getAlias()]['id']) ? $this->model->lastInsertId() : $data[$this->model->getAlias()]['id'];

        return $this->model->tree->rebuild();
    }

    private function getParent($alias, $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;') {
        $return = $this->entity->branch($alias, $spacer, 'title', 1);

        foreach ($return as $key => $value) {
            $return[$key] = $value ." (locale)";
            break;
        }

        return $return;
    }

}