<?php
class App {

    public function __construct() {
        $this->basic();
    }

    public function basic() {
        Helper::attach(LIB . 'package' . DS . 'error' . DS . 'exception.php', false);
    }


    public function execute() {
        global $request, $view;

        $channel = $request->channel;
        $module = $request->query['module'];
        $path = $module . DS . $module . "." . $channel . ".php";

        $path = "controller" . DS . $path;

        Helper::attach($path);

        $class = $module.ucfirst($channel);

        if (class_exists($class)) {
            $view->finalize(new $class());
        }

        throw new Exception(sprintf('The class <b>%s</b> does not exist in the file [%s]!', $class, $path));
    }

    public function finish() {
        Helper::db()->disconnect();
        exit();
    }
}