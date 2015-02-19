<?php
class categoryBackend extends backend {

    public function __construct($model = 'category', $table = 'category', $alias = 'category', $template = '') {
        parent::__construct($model, $table, $alias, $template);

        $this->entity = Helper::load($model, 'entity', compact('model', 'table', 'alias'));
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
                break;
            default:
                    $this->index();
                break;
        }
    }

    public function index() {
        global $request;

        $data   = array('list' => array());
        $alias  = $this->model->getAlias();
        $super  = $this->entity->superRoot();

        if (empty($request->name['edit']) == false) {
            $target = $this->__editBranch();
        } elseif (empty($request->query[2]) == false) {
            $this->__deleteBranch();
        } else {
            $target = $this->__addBranch($super);
        }

        $option = array(
                        'select' => "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.status, {$alias}.modified",
                        'where'  => "{$alias}.parent_id = {$super['id']} AND {$alias}.deleted = 0"
                    );

        $data['list'] = $this->model->find($option);

        $this->render('index', compact('target', 'data'));
    }

    private function __deleteBranch() {
        global $request;

        $alias = $this->model->getAlias();
        if (!empty($request->data[$alias])) {
            $target = implode(',', $request->data[$alias]);

            $condition = 'id IN (' . $target . ')';
            $this->model->delete($condition);
        }

        return $this->redirect(Helper::get('url')->generate('index'));
    }

    private function __editBranch($super = array()) {
        global $request;

        $id = intval($request->name['edit']);

        $alias = $this->model->getAlias();
        if (!empty($request->data[$alias])) {
            $this->_save($request->data);
        }

        $fields =  "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.status";

        $target = $this->model->findById($id, $fields);
        if (empty($target)) {
            return $this->redirect(Helper::get('url')->notfound());
        }

        return $target;
    }

    private function __addBranch($super = array()) {
        global $request;

        $alias = $this->model->getAlias();
        if (!empty($request->data[$alias])) {
            $request->data[$alias]['parent_id'] = $super['id'];
            $flag = $this->_save($request->data);

            if ($flag) {
                return $this->redirect(Helper::get('url')->generate('index'));
            }

            return $request->data;
        }

        return $this->model->init();
    }

    public function branch($branch = '') {
        $alias = $this->model->getAlias();

        $root = $this->entity->root($branch);

        $data = array('list' => array());
        $option = array(
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
        if (!empty($request->data[$alias])) {
            $flag = $this->_save($request->data);

            if ($flag) {
                return $this->redirect(Helper::get('url')->generate('branch/'.$branch));
            }

            $target = $request->data;
        }

        $option['category'] = $this->_getParent($branch);

        $target = array_merge($target, $this->getSEOInstance());

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    public function edit($branch = '', $id = 0) {
        global $request, $app;

        $id = intval($id);
        $option = array();

        $alias = $this->model->getAlias();

        if(!empty($request->data[$alias])) {
            $this->_save($request->data);
        }

        $fields =  "{$alias}.id, {$alias}.title, {$alias}.slug, {$alias}.parent_id, {$alias}.index, {$alias}.status, {$alias}.seo_id";

        $target = $this->model->findById($id, $fields);
        if (empty($target)) {
            return $this->redirect(Helper::get('url')->notfound());
        }

        $option['category'] = $this->_getParent($branch);
        $target = array_merge($target, $this->getSEOInstance($target[$alias]['seo_id']));

        return $this->render('add_edit_form', compact('target', 'option'));
    }

    protected function _save($data = array(), &$affact = array()) {
        $lastInsertId = 0;

        $flag = $this->model->save($data[$this->model->getAlias()]);
        if ($flag == false) {
            return false;
        }

        $lastInsertId = empty($data[$this->model->getAlias()]['id']) ? $this->model->lastInsertId() : $data[$this->model->getAlias()]['id'];

        $flag = $this->model->tree->rebuild();
        if ($flag == false) {
            return false;
        }

        $affact[$this->model->getAlias()] = $lastInsertId;

        if(empty($data['seo']) == false) {
            return $this->_saveSEO($data, $affact);
        }

        return $flag;
    }

    protected function _saveSEO($data, &$affact = array()) {
        $master = $affact[$this->model->getAlias()];
        $data[$this->model->getAlias()]['id'] = $data[$this->model->getAlias()]['category_id'] = $master;

        $lastInsertId = 0;
        $flag = $this->saveSEOInstance($data['seo'], $data[$this->model->getAlias()], 'detail', $lastInsertId);
        if ($flag == false) {
            return false;
        }

        $affact['seo'] = $lastInsertId;

        $option = array(
                        'fields' => array('seo_id' => $lastInsertId),
                        'where' => "id = " . $master
        );

        return $this->model->update($option);
    }

    protected function _getParent($alias, $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;') {
        $return = $this->entity->branch($alias, $spacer, 'title', 1);

        foreach ($return as $key => $value) {
            $return[$key] = $value ." (locale)";
            break;
        }

        return $return;
    }

    public function delete() {
        global $request;

        if(!empty($request->data[$this->model->getAlias()])) {
            $target = implode(',', $request->data[$this->model->getAlias()]);

            $condition = 'id IN (' . $target . ')';

            $this->model->delete($condition);
        }

        return $this->redirect(Helper::get('url')->generate('branch/'.$request->query[2]));
    }
}