<?php
class objectTrait{


	public function url($obj) {
	    global $config;
	    
	    if(empty($obj["SEO"])) {
	        return $config->base_url.'doing-more';
	    }
	    
	    return $config->base_url.$obj["SEO"]['alias'];
	}
	
	public function decode($html = '') {
	    return html_entity_decode($html);
	}
	
}