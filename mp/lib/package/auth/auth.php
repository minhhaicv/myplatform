<?php
Helper::attach(LIB . 'package' . DS . 'auth' . DS . 'security.auth.php');

class auth{

    public function test(){
        echo 54321;
    }

    public function login($account = '', $password = '') {
        return $this->authenticate($account, $password);
    }

    protected function authenticate($account = '', $password = '') {
        return $this->internalAuthenticate($account, $password);
    }

    protected function internalAuthenticate($account = '', $password = '') {
        $model = Helper::get('user', 'model');

        $security = new securityAuth();
        $password = $security->hash($password);

        $alias = $model->getAlias();
        $option = array(
                        'select' => "{$alias}.id, {$alias}.fullname, {$alias}.group_id",
                        'where'  => "{$alias}.account = '" . $account . "' AND {$alias}.password = '" . $password ."' AND {$alias}.deleted = 0",
                        'limit'  => 1,
        );

        $tmp = $model->find($option, 'first');

        if(empty($tmp)) return false;

        session::write('auth', $tmp);

        return true;
    }
}