<?php

class userBackend extends backend {

    public function __construct($model = 'user', $table = 'user', $alias = 'user', $template = '') {
        parent::__construct($model, $table, $alias, $template);
    }

    public function navigator(){
        global $request;

        switch ($request->query['action']) {
            case 'group':
                    $this->group();
                break;
            case 'index':
                    $this->index();
                break;
            case 'add':
                    $this->add();
                break;
            case 'edit':
                    $this->edit($request->query[2]);
                break;
            case 'delete':
                    $this->delete();
                break;
            case 'logout':
                    $this->logout();
                break;
            default:
                    $this->login();
                break;
        }
    }

    public function logout() {
        Session::destroy();
        $this->redirect(Helper::get('url')->generate('login'));
    }

    public function delete() {
        global $request;

        if (!empty($request->data[$this->model->getAlias()])) {
            $target = implode(',', $request->data['user']);

            $condition = 'id IN (' . $target . ')';

            $this->model->delete($condition);
        }

        return $this->redirect(Helper::get('url')->generate('index/group:'.$request->name['group']));
    }

    private function __availableGroup(){
        $model = Helper::load('group', 'model');
        $alias  = $model->getAlias();
        $option = array(
                        'select' => "{$alias}.id, {$alias}.title",
                        'where'  => "{$alias}.deleted = 0 AND {$alias}.status > 0"
        );

        return $model->find($option, 'list');
    }

    public function add() {
        global $request;

        $option = array();
        $alias = $this->model->getAlias();

        $target = $this->model->init();
        if (!empty($request->data[$alias])) {
            $flag = $this->_save($request->data);

            if ($flag) {
                return $this->redirect(Helper::get('url')->generate('index/group:'.$request->data['user']['group_id']));
            }

            $target = $request->data;
        }

        $option = array('group' => $this->__availableGroup());

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function edit($id = 0) {
        global $request;

        $id = intval($id);

        if (!empty($request->data[$this->model->getAlias()])) {
            $this->_save($request->data);
        }

        $alias = $this->model->getAlias();
        $fields =  "{$alias}.id, {$alias}.account, {$alias}.email, {$alias}.fullname, {$alias}.group_id, {$alias}.status";

        $target = $this->model->findById($id, $fields);
        if (empty($target)) {
            throw new NotFoundException();
        }

        $option = array('group' => $this->__availableGroup());

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    protected function _save($data = array()) {
        if (empty($data['user']['password']) == false) {
            $data['user']['password'] = Helper::get('security')->hash($data['user']['password']);
        }

        return $this->model->save($data[$this->model->getAlias()]);
    }

    public function index() {
        global $request;

        $groupId = $request->name['group'];

        $page = empty($request->name['page']) ? 1 : $request->name['page'];

        $alias  = $this->model->getAlias();
        $option = array(
                        'select' => "{$alias}.id, {$alias}.account, {$alias}.email, {$alias}.fullname, {$alias}.last_login, {$alias}.status, {$alias}.modified",
                        'where'  => "{$alias}.group_id = {$groupId} AND {$alias}.deleted = 0",
                        'order'  => "{$alias}.fullname asc, {$alias}.id desc",
                        'page'   => $page,
        );

        $data = $this->paginate($option, true);

        $tmp = Helper::load('group', 'model')->findById($groupId, 'group.title');

        $groupName = $tmp['group']['title'];
        $option = array('group_id' => $groupId, 'group_name' => $groupName);
        $this->render('index', compact('data', 'option'));
    }

    public function group() {
        global $request;

        $model  = Helper::load('group', 'model');
        $alias  = $model->getAlias();

        if (empty($request->name['edit']) == false) {
            $target = $this->__editGroup($model, $alias);
        } elseif (empty($request->query[2]) == false) {
            $this->__deleteGroup($model, $alias);
        } else {
            $target = $this->__addGroup($model, $alias);
        }

        $option = array(
                        'select' => "{$alias}.id, {$alias}.title, {$alias}.status, {$alias}.modified",
                        'where'  => "{$alias}.deleted = 0"
        );

        $data['list'] = $model->find($option);

        $this->render('index', compact('target', 'data', 'alias'), 'user/group');
    }

    private function __deleteGroup($model, $alias) {
        global $request;

        if (!empty($request->data[$alias])) {
            $target = implode(',', $request->data[$alias]);

            $condition = 'id IN (' . $target . ')';
            $model->delete($condition);
        }

        return $this->redirect(Helper::get('url')->generate('group'));
    }

    private function __addGroup($model, $alias) {
        global $request;

        if (!empty($request->data[$alias])) {
            $flag = $this->__saveGroup($request->data[$alias], $model);

            if ($flag) {
                return $this->redirect(Helper::get('url')->generate('group'));
            }

            return $request->data;
        }

        return $model->init();
    }

    private function __editGroup($model, $alias) {
        global $request;

        $id = intval($request->name['edit']);

        if (!empty($request->data[$alias])) {
            $this->__saveGroup($request->data[$alias], $model);
        }

        $fields =  "{$alias}.id, {$alias}.title, {$alias}.status";

        $target = $model->findById($id, $fields);
        if (empty($target)) {
            throw new NotFoundException();
        }

        return $target;
    }

    protected function __saveGroup($data = array(), $model) {
        return $model->save($data);
    }

    public function login() {
        global $request;

        $option = array();
        $alias = $this->model->getAlias();

        if (!empty($request->data[$alias])) {
            extract($request->data[$alias]);
            $flag = Helper::load('auth', 'package')->login($account, $password);

            if($flag) {
                $this->redirect(Helper::get('url')->extend('category/index'));
            } else {
                $option['error'] = array($alias => array('Account or password you entered is incorrect'));
            }
        }

        $this->layout = 'blank';

        $this->render('login', compact('option'));
    }
}