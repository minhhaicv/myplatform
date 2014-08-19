<?php
class Tmp{
	function __construct() {
		$this->backend();
	}

	function frontend() {
	}

	function backend() {
		global $html;
	
	    $html->css(
	            array(
                    'pace/pace-theme-flash',
                    'jquery-slider/css/jquery.sidr.light',
                    'boostrapv3/css/bootstrap.min',
                    'boostrapv3/css/bootstrap-theme.min',
                    'font-awesome/css/font-awesome',
	            ),
	            0,
	            'package'
	    );
	
	    $html->css(array('animate.min','style', 'responsive', 'custom-icon-set'));
	
	    $html->js(
	            array('jquery-1.8.3.min', 'bootstrap/js/bootstrap.min'),
	            0,
	            'package'
	    );
	    $html->js(
	            array(
                    'jquery-ui/jquery-ui-1.10.1.custom.min',
                    'breakpoints',
                    'jquery-unveil/jquery.unveil.min',
                    'jquery-block-ui/jqueryblockui',
                    'jquery-slider/jquery.sidr.min',
                    'jquery-slimscroll/jquery.slimscroll.min',
                    'pace/pace.min',
                    'jquery-numberAnimate/jquery.animateNumbers',
	            ),
	            1,
	            'package'
	    );
	
	    $html->js(array('core'));
	}
}