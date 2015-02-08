<?php

class postBackend extends backend {

    public function __construct($model = 'post', $table = 'post', $alias = 'post', $template = '') {
        parent::__construct($model, $table, $alias, $template);
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
        global $request;

        $entity = Helper::load('category', 'entity');
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
        global $request;

        $id = intval($id);
        $option = array();

        if(!empty($request->data[$this->model->getAlias()])) {
            $this->_save($request->data);
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

    public function add() {
        global $request;

        $option = array();
        $alias = $this->model->getAlias();

        $target = $this->model->init();
        if(!empty($request->data[$alias])) {
            $flag = $this->_save($request->data);

            if($flag) return $this->redirect(Helper::get('url')->generate());

            $target = $request->data;
        }

        $option['category'] = $this->getCategory($alias, '&nbsp;&nbsp;&nbsp;&nbsp;');

        $target = array_merge($target, $this->getSEOInstance());

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    protected function _save($data = array(), &$affact = array()) {
        $lastInsertId = 0;

        $flag = $this->model->save($data[$this->model->getAlias()]);
        if($flag == false) return false;

        $lastInsertId = empty($data[$this->model->getAlias()]['id']) ? $this->model->lastInsertId() : $data[$this->model->getAlias()]['id'];

        $affact[$this->model->getAlias()] = $lastInsertId;

        if(empty($data['seo']) == false)
            return $this->_saveSEO($data, $affact);

        return $flag;
    }

    protected function _saveSEO($data, &$affact = array()) {
        $master = $affact[$this->model->getAlias()];
        $data[$this->model->getAlias()]['id'] = $master;

        $lastInsertId = 0;
        $flag = $this->saveSEOInstance($data['seo'], $data[$this->model->getAlias()], 'detail', $lastInsertId);
        if($flag == false) return false;

        $affact['seo'] = $lastInsertId;

        $option = array(
                        'fields' => array('seo_id' => $lastInsertId),
                        'where' => "id = " . $master
        );

        return $this->model->update($option);
    }

}