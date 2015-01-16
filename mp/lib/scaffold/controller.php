<?php
class controller{

    function __construct($model = ''){
        global $app;

        if($model){
            $this->model = $app->load('model', $model);
        }
    }


    public function getCategory($alias, $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;', $level = 2, &$entity = null) {
        global $app;

        $entity = $app->load('entity', 'category');

        return $entity->branch($alias, $spacer, 'title', $level);
    }

    public function paginate($options = array(), $pager = true) {
        return Helper::get('paginator', 'helper')->paginate($options, $this->model, $pager);
    }

    public function redirect($url = '/', $status = null, $refresh = 0) {
        $_statusCodes = array(
                        200 => 'OK',
                        301 => 'Moved Permanently',
                        302 => 'Found',
                        304 => 'Not Modified',
                        400 => 'Bad Request',
                        401 => 'Unauthorized',
                        402 => 'Payment Required',
                        403 => 'Forbidden',
                        404 => 'Not Found',
        );

        if($refresh) {
            header ( "Refresh: " . $refresh . ";url=" . $url);
            $this->render('redirect', array(), 'scaffold/');
        } else {
            header ("HTTP/1.1 " . $status . " " . $_statusCodes[$status]);
            header ("Location: " . $url);
        }
    }


    public function render ($template = '', $option = array(), $prefix = '') {
        global $view, $request;

        if(empty($prefix)) {
            $prefix = $request->query['module'] . DS;
        }

        $option['alias'] = $this->model->getAlias();

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