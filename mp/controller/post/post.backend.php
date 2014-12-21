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
                     $this->edit($request->query[0]);
                break;
            default :
                    $this->main();
                break;
        }
    }


    public function main($category = '') {
            global $app, $request;

            $entity = $app->load('entity', 'category');

            $data = array();

            $query = explode('-', $category);
            $catId = abs(intval($query[count($query)-1]));

            if(empty($catId)) $catId = $entity->root('post');

            $lists = $entity->extract($catId);

            $page = empty($request->query['page']) ? 1 : $request->query['page'];

            $alias = $this->model->getAlias();

            $option = array (
                            'select' => "{$alias}.id, {$alias}.title, {$alias}.status, {$alias}.index, {$alias}.modified, {$alias}.category_id",
                            'where'  => "{$alias}.deleted = 0 AND category_id IN (" . implode(',', array_keys($lists)) . ')',
                            'order'  => "{$alias}.id desc",
                            'page'   => $page,
            );

            $pager = array();

            $data['category'] = $lists;
            $data['list']     = $this->paginate($option, $pager);
            $data['pager']    = $pager;
print "<pre>";
print_r($this->model->getQueriesLog());
print "</pre>";
            $this->render('main.twg', compact('data', 'alias'));
    }














    function edit($id = 0) {
        global $request;

        $id = intval($id);

        if(empty($request->data['post'])) {
            $alias = $this->model->getAlias();
            $fields =  "{$alias}.id, {$alias}.title, {$alias}.content, {$alias}.status";

            $data = $this->model->findById($id);
            if(empty($target)) {
                $this->output = 'redirect to error page';
            }

            return $this->output = $this->view->form($data);
        }

        $this->_edit();
    }

    function _edit() {
        global $app, $request;

        $app->import('entity', array('seo'));
        $seo = new seo_entity();

        $request->data['seo']['id'] = 425;
        if(empty($request->data['seo']['id'])) {
           $seoId = $seo->create($request->data['seo']);
           $request->data['post']['seo_id'] = $seoId;
        }

        $option = array(
                      'fields' => $request->data['post'],
                      'where' => "id = ".$request->data['post']['id']
        );
        $flag = $this->model->update($option);

        if(!$flag) {
            echo 1;exit;
            //redirect to edit page.
            return $this->edit();
        }

        $seo->update($request->data['post'], $request->data['seo']);
        return $this->output = 'redirect to index';
    }


    function add() {
        global $app, $request;

        if(empty($request->data['post'])) {
            $app->import('entity', array('category'));
            $entity = new category_entity();
            $lists = $entity->getBranch('post');

            $data = $this->model->blank();

            return $this->output = $this->view->form($data);
        }

        $this->_add();
    }

    function _add() {
        global $app, $request;

        $app->import('entity', array('seo'));
        $seo = new seo_entity();

        $seoId = $seo->create($request->data['seo']);

        $request->data['seo']['id'] = $seoId;
        $request->data['post']['seo_id'] = $seoId;

        $flag = $this->model->create($request->data['post']);

        if(!$flag) {
            return $this->add($request->data['post']);
        }

        $id = $this->model->lastInsertId();
        $request->data['post']['id'] = $id;

        $seo->update($request->data['post'], $request->data['seo'], array('fields' => array('url'), 'prefix' => "post/detail/"));
        return $this->output = 'redirect to index';
    }

}