<?php
class app {

    public $base = '';
    public function __construct() {
        $this->basic();
    }


    public function basic() {
        $this->attach(LIB . 'package' . DS . 'error' . DS . 'exception.php');
    }

    public function import($name = array(), $type = 'model') {
        global $request;

        $subfix = ($type == 'entity') ? $type : $request->channel;

        $t = array('model', 'package');
        $s = array('entity', 'controller');

        $helper = Helper::forceGet('path');

        foreach($name as $module) {
            $filename = $module;
            if(in_array($type, $s)) {
                $filename .= '.' . $subfix;
            } elseif (in_array($type, $t) === false) {
                $filename .= '.' . $type;
            }

            $path = $helper->import($module, $filename, $type);

            $this->attach($path);
        }
    }

    public function uses($name = '', $type = 'model') {
        $this->import(array($name), $type);
    }

    public function load($instance = '', $type = 'model', $arguments = array()) {
        $this->import(array($instance), $type);

        if(in_array($type, array('model', 'package')) === false) {
            $instance = $instance . ucfirst($type);
        }

        $reflection = new ReflectionClass($instance);
        return $reflection->newInstanceArgs($arguments);
    }

    public function attach($path = "") {
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

        $this->attach($path);
        $class = $module.ucfirst($channel);

        if(!class_exists($class))
            throw new Exception(sprintf('The class <b>%s</b> does not exist in the file [%s]!', $class, $path));

        $view->finalize(new $class());
    }

    public function finish() {
        Helper::getDB()->disconnect();
        exit();
    }
}