<?php
class Helper {

    static function getApp() {
        global $app;

        if (is_null($app)) {
            require_once(LIB . "app.lib.php");
            $app = new App();
        }

        return $app;
    }

    static function getDB() {
        global $db;

        if(is_null($db)){
            require_once (CONFIG . 'database' . ".php");
            $dbconfig = new DATABASE_CONFIG();

            $config = $dbconfig->default;

            require_once (MP . 'database/instance/' . $config['driver'] . ".php");

            $dbName = ucfirst($config['driver']);
            $db = new $dbName($config);
        }

        return $db;
    }

    static function template() {
        global $template, $request, $config;

        if(is_null($template)) {
            $folder = $config->view[$request->channel];

            self::getApp()->requireFile(LIB . "tpl-engine/twig/Autoloader.php");
            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem("./view/{$folder}/");

            $template = new Twig_Environment($loader, array(
                                'cache' => "tmp/cache/view/{$folder}",
                                'debug' => true,
                                'strict_variables' => true,
                                'autoescape' => false,
                                'auto_reload' => true
                        ));


            $function = new Twig_SimpleFunction("get", function ($name, $type) {
                return Helper::get($name, $type);
            });

            $template->addFunction($function);
        }

        return $template;
    }

    static function scaffold($name = array()) {
        $path = LIB . 'scaffold' . DS;

        foreach($name as $value) {
            self::getApp()->requireFile($path . $value . '.php');
        }
    }

    static function get($name, $type = 'helper') {
        $path = $type . DS . $name . '.' . $type . '.php';

        if(file_exists(ROOT . 'lib' . DS . $path)) {
            $path = ROOT . 'lib' . DS . $path;
        } else {
            $path = LIB . $path;
        }

        self::getApp()->requireFile($path);
        $name .= ucfirst($type);

        return new $name();
    }

    static function lib($name = '', $global = true) {
        if($global) {
            global $$name;
        }

        if(is_null($$name)) {
            $path = $name.'.lib.php';

            if(file_exists(ROOT . 'lib' . DS . $path)) {
                $path = ROOT . 'lib' . DS . $path;
            } else {
                $path = LIB . $path;
            }

            self::getApp()->requireFile($path);
            $$name = new $name();
        }

        return $$name;
    }
}