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

    static function login() {
        global $login;

        if (is_null($login)) {
            $login = Helper::get('login');
        }

        return $login;
    }

    static function template() {
        global $template;

        if (is_null($template)) {
            Helper::attach(LIB."parse-engine".DS."init.php", false);
            $template = initTemplate::twig();
        }

        return $template;
    }

    static function scaffold($name = array()) {
        $path = LIB . 'scaffold' . DS;

        foreach ($name as $value) {
            self::attach($path . $value . '.php', true  , false);
        }
    }

    static function get($name, $type = 'helper', $global = false) {
        if (empty($global)) {
            return self::load($name, $type);
        }

        global $$name;

        if (is_null($$name)) {
            $$name = self::load($name, $type);
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

    static function uses($instance = '', $type = 'helper', $filename = '') {
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

    static function load($instance = '', $type = 'helper', $arguments = array()) {
        $instance = self::uses($instance, $type);

        $reflection = new ReflectionClass($instance);
        return $reflection->newInstanceArgs($arguments);
    }

    static function lib($filename = '') {
        $path = path::load('lib');

        $filename = $path . $filename . '.php';

        self::attach($filename);
    }

    static function def(&$instance = '', $type = 'helper') {
        $path = path::load($type);

        $filename = $path . $instance . '.' . $type . '.php';

        self::attach($filename);

        $instance = $instance . ucfirst($type);
    }

    static function module(&$module = '', $type = 'model') {
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

    static function attach($filename = "", $relative = true, $exception = true) {
        if ($relative) {
            if (file_exists(ROOT . $filename)) {
                $filename = ROOT . $filename;
            } else {
                $filename = MP . $filename;
            }
        }

        if (file_exists(ROOT . $filename)) {
            require_once $filename;
        } elseif($exception) {
            throw new InternalErrorException('file not found: '.$filename);
        }
    }
}