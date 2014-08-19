<?php
class authorizeComponent{

	
	function authorizeBackend() {
		global $request, $app, $config;

		if(($request->query['action'] == 'login')) return true;

		if(Helper::getSession()->check(APPLICATION_TYPE.'.user.id')) {
		    return true;
		}
		
		$app->redirect($config->base_url.'user/login');
	}
}