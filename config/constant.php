<?php
//-----------------------------------------------
// USER CONFIGURABLE ELEMENTS
//-----------------------------------------------
// Root path

define( 'DS', DIRECTORY_SEPARATOR);

define( 'ROOT'      , "." . DS );
define( 'CONFIG'    , ROOT . "config" . DS );
define( 'MP'        , ROOT . "mp" . DS );
define( 'MEDIA'     , ROOT . 'media' . DS );
define( 'TMP'       , ROOT . 'tmp' . DS );

define( 'LIB'       , MP . 'lib' . DS );

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
