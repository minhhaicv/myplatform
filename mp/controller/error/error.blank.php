<?php

class errorBlank extends controller {

    public function __construct($model = 'error', $table = 'log_error', $alias = 'log_error', $template = '') {
        parent::__construct($model, $table, $alias, $template);

        $this->layout = 'blank';
    }

    public function navigator(){
        global $request;

        switch ($request->query['action']) {
            case 'notfound':
                    $this->notfound();
                break;
            case 'badrequest':
                $this->badrequest();
                break;
            case 'unauthorized':
                $this->unauthorized();
                break;
            case 'forbidden':
                $this->forbidden();
                break;
            default:
                    $this->internal();
                break;
        }
    }

    public function notfound() {
        return $this->render('default', array('message' => 'page not found'));
    }

    public function badrequest() {
        return $this->render('default', array('message' => 'bad request'));
    }

    public function unauthorized() {
        return $this->render('default', array('message' => 'unauthorized'));
    }

    public function forbidden() {
        return $this->render('default', array('message' => 'forbidden'));
    }

    public function internal() {
        return $this->render('default', array('message' => 'internal error'));
    }
}