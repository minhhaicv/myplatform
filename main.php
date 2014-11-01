<?php 

session_start();

$mem = memory_get_usage();
$time = microtime(true);

try {
    global $meta;
    
    require_once CONFIG_PATH."config.php";
    $config = new config();
    
    require_once LIBS_PATH.'helper.lib.php';
    
	$db = Helper::getDB(true);
	$db->connect();
	
	register_shutdown_function('deconstructor');
	
	Helper::getApp();
	Helper::getRequest();
	
	$app->requireFile(LIBS_PATH . "core/controller.php");
	$app->requireFile(LIBS_PATH . "core/model.php");
	$app->requireFile(LIBS_PATH . "core/entity.php");
	
	$app->requireFile(LIBS_PATH . "core/controller.".APPLICATION_TYPE.".php");
	
	Helper::getView();
	
	Helper::getLib('path');

	$template = Helper::getTemplate();
	
	$app->requireFile(LIBS_PATH."components/authorize.com.php");
	$authorize = new authorizeComponent();
		
	if(APPLICATION_TYPE == "backend") {
		$authorize->authorizeBackend();
	}

	$html = Helper::getHelper('html');

	$app->requireFile(LIBS_PATH . 'tmp.lib.php');
	$tmp = new Tmp();
	
	$runme = $app->initExecutor();

	$runme->navigator();
}

catch (Exception $e) {
    $message= "<div >
    Error: {$e->getMessage()}<br />
    Line: {$e->getLine()}<br />
    File: {$e->getFile()}<br />
    Trace: <pre>{$e->getTraceAsString()}</pre><br />
    </div>";
    print "<pre>";
    print_r($message);
    print "</pre>";
// 	$app->finish();
exit;
}

if(Helper::getRequest()->is('ajax')) {	
	print $runme->getOutput();
	$app->finish();
}

$output = $runme->getOutput();
$layout = $runme->getLayout();

$view->render($output, $layout);

print_r(array(
'memory' => (memory_get_usage() - $mem) / (1024 * 1024),
'seconds' => microtime(TRUE) - $time
));