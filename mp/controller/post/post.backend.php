<?php

class post_backend extends backend {

    public function __construct(){
        parent::__construct('post');
    }

    public function navigator(){
        global $request;

        switch($request->query['action']){
            case 'add':
                    $this->add();
                break;
            case 'edit':
                     $this->edit($request->query[2]);
                break;
            case 'delete':
                    $this->delete();
                break;
            default :
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

        return $this->redirect(Helper::get('url')->generate());
    }


    public function main($category = '') {
        global $app, $request;

        $entity = $app->load('entity', 'category');

        $data = array();

        $query = explode('-', $category);
        $catId = abs(intval($query[count($query)-1]));

        if(empty($catId)) $catId = $entity->root($this->model->getAlias());

        $lists = $entity->extract($catId);

        $page = empty($request->query['page']) ? 1 : $request->query['page'];

        $alias = $this->model->getAlias();

        $option = array (
                        'select' => "{$alias}.id, {$alias}.title, {$alias}.status, {$alias}.index, {$alias}.modified, {$alias}.category_id",
                        'where'  => "{$alias}.deleted = 0 AND category_id IN (" . implode(',', array_keys($lists)) . ')',
                        'order'  => "{$alias}.id desc",
                        'page'   => $page,
        );

        $data = $this->paginate($option, true);
        $data['category'] = $lists;

        $this->render('main', compact('data'));
    }

    public function edit($id = 0) {
        global $request, $app;

        $id = intval($id);
        $option = array();

        if(!empty($request->data[$this->model->getAlias()])) {
            $this->_edit();
        }

        $alias = $this->model->getAlias();
        $fields =  "{$alias}.id, {$alias}.title, {$alias}.category_id, {$alias}.index, {$alias}.intro, {$alias}.content, {$alias}.status, {$alias}.seo_id";

        $target = $this->model->findById($id, $fields);
        if(empty($target)) {
            return $this->redirect(Helper::get('url')->notfound());
        }

        $target = array_merge($target, $this->getSEOInstance($target[$alias]['seo_id']));

        $option['category'] = $this->getCategory($alias, '&nbsp;&nbsp;&nbsp;&nbsp;');

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function _edit() {
        global $app, $request;

        $option = array(
                        'fields' => $request->data[$this->model->getAlias()],
                        'where' => "id = " . $request->data[$this->model->getAlias()]['id']
        );

        $flag = $this->model->update($option);
        if($flag == false) return false;

        $lastInsertId = 0;
        $flag = $this->saveSEOInstance($request->data['seo'], $request->data[$this->model->getAlias()], 'detail', $lastInsertId);
        if($flag == false) return false;

        if(empty($request->data['seo']['id'])) {
            $option = array (
                        'fields' => array('seo_id' => $lastInsertId),
                        'where' => "id = " . $request->data[$this->model->getAlias()]['id']
                    );

            return $this->model->update($option);
        }

        return true;
    }

    public function add() {
        global $app, $request;

        $option = array();
        $alias = $this->model->getAlias();

        $target = $this->model->init();
        if(!empty($request->data[$alias])) {
            $flag = $this->_add();

            if($flag) return $this->redirect(Helper::get('url')->generate());

            $target = $request->data;
        }

        $option['category'] = $this->getCategory($alias, '&nbsp;&nbsp;&nbsp;&nbsp;');

        $target = array_merge($target, $this->getSEOInstance());

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function _add($affact = array()) {
        global $request;

        $lastInsertId = 0;

        $flag = $this->model->create($request->data[$this->model->getAlias()]);
        if($flag == false) return false;

        $lastInsertId = $this->model->lastInsertId();

        $request->data[$this->model->getAlias()]['id'] = $lastInsertId;
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
}