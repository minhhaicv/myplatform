<?php
require_once './config/constant.php';

require_once MP  . 'global.php';
require_once LIB . 'utility' . DS . 'path.php';
require_once MP  . 'helper.php';
require_once LIB . 'utility' . DS . 'exception.php';

$mem = memory_get_usage();
$time = microtime(true);

try {
    Helper::app();

    Helper::uses('hash', 'utility');
    Helper::uses('security', 'utility');
    Helper::uses('sanitize', 'utility');

    Helper::db(true)->connect();
    Helper::config();

    register_shutdown_function('deconstructor');

    Helper::get('session', 'lib', true);
    Helper::get('request', 'lib', true);

    Helper::scaffold(array("controller", "model", "entity"));
    $request->conduct();

    if (Helper::get('security')->authenticate() === false) {
        throw new UnauthorizedException();
    }

    Helper::get('view', 'lib', true);
    Helper::template();

    $app->execute();
}
catch (Exception $e) {
    Helper::get('error', 'entity')->handler($e);
}

print_r(array(
'memory' => (memory_get_usage() - $mem) / (1024 * 1024),
'seconds' => microtime(TRUE) - $time
));