<?php
//-----------------------------------------------
// USER CONFIGURABLE ELEMENTS
//-----------------------------------------------
// Root path
define ( 'ROOT_PATH'		, "./" );

define ( 'APP_PATH'		    , ROOT_PATH . "app/" );

define ( 'CONFIG_PATH'		, APP_PATH . "config/" );

define ( 'LIBS_PATH'		, APP_PATH . 'libs/' );
define ( 'CONTROLLER_PATH'	, APP_PATH . 'controller/' ); // controller

define ( 'CORE_PATH'		, CONTROLLER_PATH . 'cores/' );

//////

define ( "CACHE_PATH"		, ROOT_PATH . 'cache/' );

define ( "CACHED"			, false );

// error_reporting ( E_ERROR | E_WARNING | E_PARSE );
// error_reporting(1);