<?php

class fileBackend extends backend {

    public function __construct($model = 'file', $table = 'file', $alias = 'file', $template = '') {
        parent::__construct($model, $table, $alias, $template);
    }

    public function navigator() {
        global $request;

        switch($request->query['action']) {
            case 'upload':
                    $this->upload();
                break;
        }
    }

    public function upload() {
        global $request;

        $result = Helper::get('file', 'entity')->upload();

        if ($request->is('ajax')) {
            $this->layout = false;

            if (isset($request->name['v']) && $request->name['v'] == 'jquery-upload') {
                reset($result);
                $result = array('files' => current($result));
            }

            echo json_encode($result);
        }
    }
}