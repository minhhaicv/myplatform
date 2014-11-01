<?php
class Addon{
	public  $html;
	function __construct() {
		global	$template;
		$this->view = $template->load('addonSkin');
		
		$f = APPLICATION_TYPE;
		$this->$f();
	}

	function frontend() {
		global $template, $app;
		
		$app->import('entity', array('post'));
		
		$post = new post_entity();
		$random = $post->getRandom(3);
		
		$template->global_template->portlet_random = $this->view->displayRandom($random);
		
		$template->global_template->portlet_ads = $this->view->displayAds();
		$template->global_template->portlet_facebook = $this->view->displayFacebook();
	}

	function backend() {
		global $template;
		
		$template->global_template->sidebar = $this->view->sidebar();
	    $template->global_template->header = $this->view->header();
	}
}