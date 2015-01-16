<?php
class app {

    public $base = '';
    public function __construct() {
        $this->basic();
    }


    public function basic() {
        $this->requireFile(LIB . 'package' . DS . 'error' . DS . 'exception.php');
    }

    public function import($type = '', $name = array()) {
        global $config;

        foreach($name as $value) {
            if($type == 'model') {
                $path = MP . 'controller' . DS  . $value . DS . $value . '.php';

                $this->requireFile($path);
                return;
            }

            $path = MP . 'controller' . DS  . $value . DS . $value . '.' . $type . '.php';

            $this->requireFile($path);
        }
    }

    public function load($type = '', $name = '') {
        $this->import($type, array($name));

        if($type == 'model') return new $name();

        $name = $name . '_' . $type;
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