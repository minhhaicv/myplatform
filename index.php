<?php

require_once "./app/config/constant.php";
require_once "./app/global.php";


$uri = $_SERVER['REQUEST_URI'];
if(strpos($uri, '/page/') !== false){
	$url = $_SERVER['HTTP_HOST'].str_replace('/page/', '/trang-', $uri);
	$url = str_replace('.html', '', $url);
	@header("HTTP/1.1 301 Moved Permanently");
	@header("location: http://".$url);
	exit();
}

if(CACHED) {
	$uri = str_replace('/', '#', $_SERVER['REQUEST_URI']);
	$htmlfile = CACHE_PATH."html/".$uri.".html";
	
	if(file_exists($htmlfile)){
		$time = substr(file_get_contents($htmlfile), 0, 10);
		if($time >= time() - 3600){
			if(file_exists($htmlfile)){
				ob_start('ob_gzhandler');
				echo substr(file_get_contents($htmlfile), 10); 
				exit;
			}
		}
		@unlink($htmlfile);
	}
}

define('APPLICATION_TYPE', 'frontend');
require_once ROOT_PATH."main.php";