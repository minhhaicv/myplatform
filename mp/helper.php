<?php
class Helper {

    static function app() {
        global $app;

        if (is_null($app)) {
            $app = self::load('app', 'lib');
        }

        return $app;
    }

    static function db() {
        global $db;

        if (is_null($db)) {
            self::attach(CONFIG."database.php", false);
            $dbconfig = new DATABASE_CONFIG();

            $config = $dbconfig->default;

            self::attach(MP . 'database'.DS.'instance'.DS . $config['driver'] . ".php", false);

            $dbName = ucfirst($config['driver']);
            $db = new $dbName($config);
        }

        return $db;
    }

    static function template() {
        global $template, $request, $config;

        if (is_null($template)) {
            $folder = $config->view[$request->channel];

            self::attach(LIB."parse-engine".DS."Twig".DS."Autoloader.php", false);
            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem("./view/{$folder}".DS);

            $template = new Twig_Environment($loader, array(
                                'cache' => TMP . "cache/view/{$folder}",
                                'debug' => true,
                                'strict_variables' => true,
                                'autoescape' => false,
                                'auto_reload' => true
                        ));


            $function = new Twig_SimpleFunction("get", function ($name, $type) {
                return Helper::get($name, $type);
            });

            $template->addFunction($function);
            $template->addExtension(new Twig_Extension_Debug());
        }

        return $template;
    }

    static function scaffold($name = array()) {
        $path = LIB . 'scaffold' . DS;

        foreach ($name as $value) {
            self::attach($path . $value . '.php', false);
        }
    }

    static function get($name, $type = 'helper', $global = false) {
        if (empty($global)) {
            return self::load($name, $type);
        }

        global $$name;

        if (is_null($$name)) {
            $$name = self::load($name, 'lib');
        }

        return $$name;
    }


    static function config() {
        global $config;

        if (is_null($config)) {
            self::attach(CONFIG."config.php", false);
            $config = new config();
        }

        return $config;
    }

///fixed

    static function uses($instance = '', $type = '', $filename = '') {
        switch ($type) {
            case 'package':
                self::package($instance, $filename);
                break;
            case 'utility':
                self::utility($instance);
                break;
            case 'model':
            case 'entity':
            case 'controller':
                self::module($instance, $type);
                break;
            case 'lib':
                self::lib($instance);
                break;
            default:
                self::def($instance, $type);
        }

        return $instance;
    }

    static function load($instance = '', $type = '', $arguments = array()) {
        $instance = self::uses($instance, $type);

        $reflection = new ReflectionClass($instance);
        return $reflection->newInstanceArgs($arguments);
    }

    static function lib($filename = '') {
        $path = path::load('lib');

        $filename = $path . $filename . '.php';

        self::attach($filename);
    }

    static function def(&$instance = '', $type = '') {
        $path = path::load($type);

        $filename = $path . $instance . '.' . $type . '.php';

        self::attach($filename);

        $instance = $instance . ucfirst($type);
    }

    static function module(&$module = '', $type = '') {
        $path = path::load('module', $module);

        switch ($type) {
            case 'entity':
                $filename = $path . $module . '.entity.php';
                $module   = $module .'Entity';

                break;
            case 'controller':
                global $request;

                $channel = $request->channel;
                $filename = $path . $module . '.' . $channel . '.php';

                $module   = $module .ucfirst($channel);
                break;
            default:
                $filename = $path . $module . '.php';
                break;
        }

        self::attach($filename);
    }

    static function utility($filename = '') {
        $path = path::load('utility');

        $filename = $path . $filename . '.php';

        self::attach($filename);
    }

    static function package($package = '', $filename = '') {
        $path = path::load('package', $package);

        if (empty($filename)) {
            $filename = $package;
        }

        $filename = $path . $filename . '.php';

        self::attach($filename);
    }

    static function attach($filename = "", $relative = true) {
        try {
            if (empty($relative)) {
                return require_once($filename);
            }

            if (file_exists(ROOT . $filename)) {
                $filename = ROOT . $filename;
            } else {
                $filename = MP . $filename;
            }

            return require_once($filename);
        } catch (Exception $e) {
            throw new Exception(sprintf('Cannot import [%s] by require_once!', $path));
        }
    }
}