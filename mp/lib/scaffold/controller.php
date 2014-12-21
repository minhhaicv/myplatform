<?php
class controller{

    function __construct($model = ''){
        global $app;

        if($model){
            $this->model = $app->load('model', $model);
        }
    }

    public function paginate($options = array(), &$pager = array()) {
        return Helper::get('paginator', 'helper')->paginate($options, $this->model, $pager);
    }

    function render ($template = '', $option = array(), $prefix = '') {
        global $view, $request;

        if(empty($prefix)) {
            $prefix = $request->query['module'] . DS;
        }

        $template = $prefix . $template;

        return $this->output = $view->render($template, $option);
    }

    function getOutput() {
        return $this->output;
    }

    function setModel($model){
        $this->model = $model;
    }

    function getLayout(){
        return $this->layout;
    }

    protected $output	= NULL;
    protected $model	= NULL;
    protected $layout 	= 'default';
}