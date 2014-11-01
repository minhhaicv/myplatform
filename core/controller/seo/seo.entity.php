<?php

class seo_entity extends Entity{
	
    public function getByUrl($url = '', $findBy = 'first') {
        $option = array (
                        'select'=>"seo.id, seo.url, seo.canonical, seo.title, seo.keyword, seo.desc, seo.alias",
                        'where' => "seo.status > 0 AND seo.alias = '" . $url . "' AND seo.deleted = 0",
                        'limit' => 1,
                         
        );
        return $this->model->find($option, $findBy);
    }
    
    public function create($data) {
        $flag = $this->model->create($data);
        if($flag) {
            return $this->model->lastInsertId();
        }
        return (int)$flag;
    }

    public function update($target = array(), $update = array(), $option = array()) {
        if(!empty($option['fields'])) {
            foreach($option['fields'] as $field) {
                if($field == 'url') {
                    $value = Helper::getHelper('url')->url($target, $option['prefix']);
                }
                
                $update[$field] = $value;
            }
        }
        
        $id = $update['id'];
        $option = array(
        	       'fields' => $update,
                   'where' => "id = ".$id
        );
        
        $this->model->update($option);
    }
    
    function __construct(){
        parent::__construct('seo');
    }
    
    function __destruct(){
        unset($this);
    }
}