<?php

class userBackend extends backend {

    public function __construct($model = 'user', $table = 'user', $alias = 'user', $template = '') {
        parent::__construct($model, $table, $alias, $template);
    }

    public function navigator(){
        global $request;

        switch($request->query['action']) {
            default :
                    $this->login();
                break;
        }
    }

    public function login() {
        global $request;

        $option = array();
        $alias = $this->model->getAlias();

        if(!empty($request->data[$alias])) {
            extract($request->data[$alias]);
            $flag = Helper::load('auth', 'package')->login($account, $password);

            if($flag) {
                $this->redirect(Helper::get('url')->extend('/category/main'));
            } else {
                $option['error'] = array($alias => array('Account or password you entered is incorrect'));
            }
        }

        $this->layout = 'blank';

        $this->render('login', compact('option'));
    }
}