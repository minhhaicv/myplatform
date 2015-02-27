<?php

class localeEntity extends entity {
    public function __construct($table = 'locale', $alias = 'locale') {
        $filename = MP . 'controller' . DS . 'locale' . DS .'mlocale.php';
        Helper::attach($filename);
        $this->model = new mlocale($table, $alias);

        $this->candidates = $this->retrieve();
    }

    public function retrieve() {
        global $request;

        $active = $request->getLocale();
        $alias = $this->model->getAlias();

        $module = $request->query['module'];

        $scope = Helper::config()->get('locale.scope.'.$module);
        if (empty($scope)) {
            $scope = Helper::config()->get('locale.scope.others');
        }

        $active = array_search($active, Helper::config()->get('locale.available'));
        if (is_null($active) || $active === false) {
            return array();
        }

        $option = array(
                        'select' => "{$alias}.code, {$alias}.locale_{$active}",
                        'where'  => "{$alias}.deleted = 0 AND {$alias}.scope IN (1,".$scope.")",
                        'order'  => "{$alias}.scope, {$alias}.code"
        );

        return $this->model->find($option, 'list');
    }

    public function get($code = '') {
        return empty($this->candidates[$code]) ? $code : $this->candidates[$code];
    }
}