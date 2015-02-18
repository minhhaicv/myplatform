<?php
class controller{

    function __construct($model = '', $table = '', $alias = '', $template = '') {
        global $request;

        if ($model) {
            $this->model = Helper::load($model, 'model', compact('table', 'alias'));
        }

        if (empty($template)) {
            $template = $request->query['module'];
        }

        $this->templateFolder = $template;
    }


    public function getCategory($alias, $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;', $level = 2, &$entity = null) {
        return Helper::load('category', 'entity')->branch($alias, $spacer, 'title', $level);
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

        if ($refresh) {
            header ( "Refresh: " . $refresh . ";url=" . $url);
            $this->render('redirect', array(), 'scaffold/');
        } else {
            header ("HTTP/1.1 " . $status . " " . $_statusCodes[$status]);
            header ("Location: " . $url);
        }
    }


    public function render($template = '', $option = array(), $prefix = '') {
        global $view, $request;

        if (empty($option['alias'])) {
            $option['alias'] = $this->model->getAlias();
        }

        if (empty($prefix)) {
            $prefix = $this->templateFolder;
        }

        $template = $prefix . DS . $template;

        return $this->output = $view->render($template, $option);
    }

    public function getOutput() {
        return $this->output;
    }

    public function getLayout(){
        return $this->layout;
    }

    protected $output  = '';
    protected $model   = '';
    protected $layout  = 'default';

    protected $templateFolder = '';
}