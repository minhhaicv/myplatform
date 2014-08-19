<?php

class post_entity extends Entity{
	
	function __construct(){
		parent::__construct('post');
	}
	
	function getRandom($limit = 5) {
	    global $app;
	    
	    $app->import('entity', array('category'));
	    $entity = new category_entity();
	    $category = $entity->getBySlug('post');
	            
        if ($category) {
            $alias = $this->model->getAlias();
            
            $catIds = implode(',', Helper::getHelper('hash')->extract($category, 'Category'));
            
            $option = array (
	                        'select'=>"{$alias}.id, {$alias}.title, {$alias}.content, {$alias}.file, {$alias}.seo",
	                        'where' => "{$alias}.status > 0 AND {$alias}.category_id IN (" . $catIds . ") AND {$alias}.deleted = 0 AND DATE_SUB(NOW(), INTERVAL 1 YEAR)",
	                        'order' => "RAND()",
	                        'limit' => $limit,
	    
	                        'joins' => array (
	                                        array (
                                                'file' => array (
                                                                'alias' => 'File',
                                                                'type' => 'LEFT',
                                                                'condition' => array (
                                                                                   "{$alias}.file" => "File.id"
                                                                                )
                                                           ),
                                                'seo' => array (
                                                                'alias' => 'SEO',
                                                                'type' => 'LEFT',
                                                                'condition' => array (
                                                                                   "{$alias}.seo" => "SEO.id"
                                                                               )
                                                            )
	                                        )
	                        )
	        );
	    
	        return $random = $this->model->find($option);
        }
        
        return array();
	}
}