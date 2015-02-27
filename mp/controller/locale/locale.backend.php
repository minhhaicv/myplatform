<?php

class localeBackend extends backend {

    public function __construct($table = 'locale', $alias = 'locale', $template = 'locale') {
        $filename = MP . 'controller' . DS . 'locale' . DS .'mlocale.php';
        Helper::attach($filename);
        $this->model = new mlocale($table, $alias);

        parent::__construct(null, $table, $alias, $template);
    }

    public function navigator() {
        global $request;

        switch($request->query['action']) {
            case 'add':
                $this->add();
                break;
            case 'edit':
                $this->edit($request->query[2]);
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

        $page = empty($request->name['page']) ? 1 : $request->name['page'];

        $alias = $this->model->getAlias();

        $available = Helper::config()->get('locale.available');

        $option = array (
                        'select' => "{$alias}.id, {$alias}.code, {$alias}.modified, {$alias}.locale_" . implode(", {$alias}.locale_", array_keys($available)),
                        'where'  => "{$alias}.deleted = 0",
                        'order'  => "{$alias}.code asc",
                        'page'   => $page,
        );

        $data = $this->paginate($option, true);

        $this->render('index', compact('data', 'available'));
    }

    public function add() {
        global $request;

        $option = array();
        $alias = $this->model->getAlias();

        $target = $this->model->init();
        if (!empty($request->data[$alias])) {
            $flag = $this->model->save($request->data[$this->model->getAlias()]);

            if ($flag) {
                return $this->redirect(Helper::get('url')->generate());
            }

            $target = $request->data;
        }

        $available = Helper::config()->get('locale.available');
        return $this->render('add_edit_form', compact('target', 'available'));
    }

    public function edit($id = 0) {
        global $request;

        $id = intval($id);

        $alias = $this->model->getAlias();
        if (!empty($request->data[$alias])) {
            $this->model->save($request->data[$alias]);
        }

        $available = Helper::config()->get('locale.available');

        $fields = "{$alias}.id, {$alias}.code, {$alias}.modified, {$alias}.locale_" . implode(", {$alias}.locale_", array_keys($available));

        $target = $this->model->findById($id, $fields);
        if (empty($target)) {
            throw new NotFoundException();
        }

        return $this->render('add_edit_form', compact('target', 'available'));
    }

    public function delete() {
        global $request;

        if (!empty($request->data[$this->model->getAlias()])) {
            $target = implode(',', $request->data[$this->model->getAlias()]);

            $condition = 'id IN (' . $target . ')';

            $this->model->delete($condition);
        }

        return $this->redirect(Helper::get('url')->generate());
    }
}