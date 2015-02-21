<?php

class errorEntity extends entity {
    public function __construct($model = 'error', $table = 'log_error', $alias = 'log_error') {
        parent::__construct($model, $table, $alias);
    }

    public function handler($error = null) {
        $code = $error->getCode();

        $list = array(
                        400 => 'badrequest',
                        401 => 'unauthorized',
                        403 => 'forbidden',
                        404 => 'notfound',
        );

        $controller = new controller();
        $url = empty($list[$code]) ? 'internal' : $list[$code];

        $controller->redirect(Helper::get('url')->extend('error/'. $url), $code);
    }
}