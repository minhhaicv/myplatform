<?php
require_once "./config/constant.php";
require_once MP."global.php";

session_start();

$mem = memory_get_usage();
$time = microtime(true);

try {
    require_once CONFIG."config.php";
    $config = new config();

    require_once LIB.'helper.lib.php';

    $db = Helper::getDB(true);
    $db->connect();

    register_shutdown_function('deconstructor');

    Helper::getApp();
    Helper::lib('request');

    Helper::scaffold(array("controller", "model", "entity"));
    $request->conduct();

    Helper::lib('view');
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
