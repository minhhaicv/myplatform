<?php
class initTemplate {

    static public function twig() {
        global $request;

        $folder = Helper::config()->view[$request->channel];

        Helper::attach(LIB."parse-engine".DS."Twig".DS."Autoloader.php", false);
        Twig_Autoloader::register();

        $loader = new Twig_Loader_Filesystem("./view/{$folder}".DS);

        $template = new Twig_Environment($loader, array(
                            'cache' => TMP . "cache/view/{$folder}",
                            'debug' => true,
                            'strict_variables' => true,
                            'autoescape' => false,
                            'auto_reload' => true
                    ));

        self::__twigFunctionLocale($template);
        self::__twigFunctionHelper($template);
        self::__twigFunctionGet($template);
        self::__twigFilterLink($template);

        $template->addExtension(new Twig_Extension_Debug());

        return $template;
    }

    static private function __twigFunctionLocale($template) {
        $function = new Twig_SimpleFunction("_", function ($code) {
            global $locale;

            return $locale->get($code);
        });

        $template->addFunction($function);
    }

    static private function __twigFunctionHelper($template) {
        $function = new Twig_SimpleFunction("helper", function ($func, $arguments = array()) {
            return (forward_static_call_array(array('Helper', $func), $arguments));
        });

        $template->addFunction($function);
    }

    static private function __twigFunctionGet($template) {
        $function = new Twig_SimpleFunction("get", function ($name, $type, $global = false) {
            return Helper::get($name, $type, $global);
        });

        $template->addFunction($function);
    }

    static private function __twigFilterLink($template) {
        $filter = new Twig_SimpleFilter('link', function ($string) {
            if (strpos($string, 'http://') === 0 ||
                strpos($string, 'https://') === 0 ||
                strpos($string, 'www') === 0 ) {
                return $string;
            }

            $string = Helper::get('request', 'lib', true)->baseUrl() . '/' . ltrim($string, '/');
            return $string;
        });

        $template->addFilter($filter);
    }
}