<?php
class app {

    public $base = '';
    public function __construct() {
        $this->basic();
    }


    public function basic() {
        $this->requireFile(LIB . 'package' . DS . 'error' . DS . 'exception.php');
    }

    private function _path($name = '', $folder = '') {
        $path = $folder . DS . $name . '.php';

        if(file_exists(ROOT . 'lib' . DS . $path)) {
            $path = ROOT . 'lib' . DS . $path;
        } else {
            $path = MP . $path;
        }

        return $path;
    }

    public function import($name = array(), $type = 'model') {
        global $request;

        $subfix = ($type == 'entity') ? $type : $request->channel;

        foreach($name as $value) {
            $path = 'controller' . DS  . $value;

            if(in_array($type, array('entity', 'controller'))) {
                $value = $value . '.' . $subfix;
            }

            $path = $this->_path($value, $path);
            $this->requireFile($path);
        }
    }

    public function uses($name = '', $type = 'model') {
        $this->import(array($name), $type);
    }

    public function load($name = '', $type = 'model', $option = array()) {
        $this->import(array($name), $type);

        if($type == 'model') {
            extract($option);

            if( !(empty($table) && empty($alias)))
                return new $name ($table, $alias);

            return new $name ();
        }

        if($type == 'entity') {
            $name = $name . '_' . $type;
        }

        return new $name();
    }

    public function requireFile($path = "") {
        try {
            return require_once($path);
        } catch(Exception $e){
            throw new Exception(sprintf('Cannot import [%s] by require_once!', $path));
        }
    }

    public function execute() {
        global $request, $view;

        $channel = $request->channel;
        $module = $request->query['module'];
        $path = $module . "/" . $module . ".".$channel.".php";

        $path = MP . "controller/" . $path;

        $this->requireFile($path);
        $class = $module."_".$channel;

        if(!class_exists($class))
            throw new Exception(sprintf('The class <b>%s</b> does not exist in the file [%s]!', $class, $path));


        $view->finalize(new $class());
    }

    public function finish() {
        Helper::getDB()->disconnect();
        exit();
    }
}