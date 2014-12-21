<?php

class post_backend extends backend {

    function __construct(){
        parent::__construct('post');

// 		$this->view->alias = 'post';
    }

    function navigator(){
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
                    //$this->category();
                break;
        }
    }


    function main() {
        $this->render('main.twg');
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

    function category($category = ''){
        global $config, $app, $request;

        $data = array();

        $query = explode('-', $category);
        $catId = abs(intval($query[count($query)-1]));

        $catId = 2;

        $entity = $app->load('entity', 'category');
        $lists = $entity->extract($catId);

        $data['category'] = $lists[$catId];


        $page = 1; $size = 10;

        $alias = $this->model->getAlias();

        $option = array (
                    'select' => "{$alias}.id,
                                 {$alias}.title,
                                 {$alias}.status,
                                 {$alias}.created
                                 ",
                    'where' => "{$alias}.deleted = 0",
                    'order' => "{$alias}.id desc",
                    'limit' => $size,
                    'page' => $page,
            );

        $data['list'] = $this->model->find($option);

        $this->output = 123456;
        //$this->view->category($data);
    }




}