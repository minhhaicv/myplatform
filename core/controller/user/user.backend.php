<?php
	class user_backend extends backend{
		
	    public function navigator(){
			global $request;
		
			switch($request->query['action']) {
			    case 'login':
                        $this->login();
			        break;
				case 'logout':
						$this->logout();
					break;
				default:
					break;
			}
		}
		
		
		
		public function login() {
			global $request;
				
			$this->layout = false;
			
			if(empty($request->data['user'])) {
			    return $this->output = $this->view->loginForm();
			}
			
			return $this->_login();
		}
	
		private function _login(){
			global $app, $config, $request;

			$app->import('entity', array('user'));
			$entity = new user_entity();
			
			$flag = $entity->login($request->data);
			if($flag) {
			    return $app->redirect($config->base_url);
			}
			
			return $app->redirect($config->base_url.'user/login');
		}
		
		
/*end pandog*/	
	
		public function __construct(){
			parent::__construct('user', 'userSkin');
		}
	}