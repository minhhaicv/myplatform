<?php
require_once "./config/constant.php";
require_once MP . "global.php";
require_once LIB . 'utility/path.php';
require_once MP . 'helper.php';

$mem = memory_get_usage();
$time = microtime(true);

try {
    Helper::app();

    Helper::uses('security', 'utility');
    Helper::uses('sanitize', 'utility');

    Helper::config();

    Helper::db(true)->connect();

    register_shutdown_function('deconstructor');

    Helper::get('session', 'lib', true);

    Helper::get('request', 'lib', true);

    Helper::scaffold(array("controller", "model", "entity"));
    $request->conduct();

    Helper::get('view', 'lib', true);
    Helper::template();

    $app->execute();
}

catch (Exception $e) {
    echo 'exception';

    print "<pre>";
    print_r($e);
    print "</pre>";
}

print_r(array(
'memory' => (memory_get_usage() - $mem) / (1024 * 1024),
'seconds' => microtime(TRUE) - $time
));