<?php

class auth{

    public function login($account = '', $password = '') {
        return $this->_authenticate($account, $password);
    }

    protected function _authenticate($account = '', $password = '') {
        return $this->_internalAuthenticate($account, $password);
    }

    protected function _internalAuthenticate($account = '', $password = '') {
        $password = Helper::get('security')->hash($password);

        $model = Helper::get('user', 'model');
        $alias = $model->getAlias();
        $option = array(
                        'select' => "{$alias}.id, {$alias}.fullname, {$alias}.group_id",
                        'where'  => "{$alias}.account = '" . $account . "' AND {$alias}.password = '" . $password ."' AND {$alias}.deleted = 0",
                        'limit'  => 1,
        );

        $tmp = $model->find($option, 'first');

        if (empty($tmp)) {
            return false;
        }

        Session::write('auth', $tmp);

        return true;
    }
}