<?php

/*
 * --- TimThumb CONFIGURATION ---
 * To edit the configs it is best to create a file called timthumb-config.php
 * and define variables you want to customize in there. It will automatically be
 * loaded by timthumb. This will save you having to re-edit these variables
 * everytime you download a new version
*/
define ('DEBUG_ON', false);								// Enable debug logging to web server error log (STDERR)
define ('DEBUG_LEVEL', 1);								// Debug level 1 is less noisy and 3 is the most noisy
define ('MEMORY_LIMIT', '30M');							// Set PHP memory limit
define ('BLOCK_EXTERNAL_LEECHERS', false);				// If the image or webshot is being loaded on an external site, display a red "No Hotlinking" gif.

//Image fetching and caching
define ('ALLOW_EXTERNAL', true);						// Allow image fetching from external websites. Will check against ALLOWED_SITES if ALLOW_ALL_EXTERNAL_SITES is false
define ('ALLOW_ALL_EXTERNAL_SITES', false);				// Less secure. 
define ('FILE_CACHE_ENABLED', true);					// Should we store resized/modified images on disk to speed things up?
define ('FILE_CACHE_TIME_BETWEEN_CLEANS', 86400);	// How often the cache is cleaned 

define ('FILE_CACHE_MAX_FILE_AGE', 86400);				// How old does a file have to be to be deleted from the cache
define ('FILE_CACHE_SUFFIX', '.timthumb.txt');			// What to put at the end of all files in the cache directory so we can identify them
define ('FILE_CACHE_PREFIX', 'timthumb');				// What to put at the beg of all files in the cache directory so we can identify them
define ('FILE_CACHE_DIRECTORY', '');				// Directory where images are cached. Left blank it will use the system temporary directory (which is better for security)
define ('MAX_FILE_SIZE', 10485760);						// 10 Megs is 10485760. This is the max internal or external file size that we'll process.  
define ('CURL_TIMEOUT', 20);							// Timeout duration for Curl. This only applies if you have Curl installed and aren't using PHP's default URL fetching mechanism.
define ('WAIT_BETWEEN_FETCH_ERRORS', 3600);				//Time to wait between errors fetching remote file

//Browser caching
define ('BROWSER_CACHE_MAX_AGE', 864000);				// Time to cache in the browser
define ('BROWSER_CACHE_DISABLE', false);				// Use for testing if you want to disable all browser caching

//Image size and defaults
define ('MAX_WIDTH', 1500);									// Maximum image width
define ('MAX_HEIGHT', 1500);								// Maximum image height
define ('NOT_FOUND_IMAGE', '');								// Image to serve if any 404 occurs 
define ('ERROR_IMAGE', '');									// Image to serve if an error occurs instead of showing error message 
define ('PNG_IS_TRANSPARENT', FALSE);  //42 Define if a png image should have a transparent background color. Use False value if you want to display a custom coloured canvas_colour 
define ('DEFAULT_Q', 90);									// Default image quality. Allows overrid in timthumb-config.php
define ('DEFAULT_ZC', 1);									// Default zoom/crop setting. Allows overrid in timthumb-config.php
define ('DEFAULT_F', '');									// Default image filters. Allows overrid in timthumb-config.php
define ('DEFAULT_S', 0);									// Default sharpen value. Allows overrid in timthumb-config.php
define ('DEFAULT_CC', 'ffffff');							// Default canvas colour. Allows overrid in timthumb-config.php

$ALLOWED_SITES = array (
		'pandogfw.ipd',
	);
