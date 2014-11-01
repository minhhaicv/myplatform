<?php

class user_entity extends Entity{
	
	function __construct(){
		parent::__construct('user');
	}
	
	function login($data = array()){
	    $alias = $this->model->getAlias();
	    
	    $option = array (
	                    'select'=>"`{$alias}`.`id`, `{$alias}`.`account`, `{$alias}`.`password`, `{$alias}`.`group_id`",
	                    'where' => "`{$alias}`.`account` = '" . $data['user']['account'] . "' AND `{$alias}`.`deleted` = 0",
	                    'limit' => 1,
	    	        );
	    $current = $this->model->find($option, 'first');
	    
	    if(!empty($current) && $current[$alias]['password'] == Helper::getHelper('hash')->password($data['user']['password'])){
	        unset($current['User']['password']);
	        
	        Helper::getSession()->write(APPLICATION_TYPE.".user", $current[$alias]);
	        return true;
	    }
	    
	    return false;
	}
}