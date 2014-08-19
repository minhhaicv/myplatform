<?php
class treeTrait {
    private $lft    = 'lft';
    private $rght   = 'rght';
	private $parent = 'parent_id';
	private $target  = null;
	
	public function __construct() {
	    
	}
	
	public function load($model=null) {
	    $this->target = $model;
	    
	    return $this;
	}
	
	public function getBySlug($slug='', $childOnly = false, $custom = array()) {
	    $alias = $this->target->getAlias();
	    
	    $option = array (
                    'select' => "{$alias}.id, {$alias}.lft, {$alias}.rght",
                    'where' => "{$alias}.slug = '{$slug}' AND {$alias}.status > 0 AND {$alias}.deleted = 0",
	    );
	    
	    $parent = $this->target->find($option, 'first');
	    
	    $lft = $parent[$alias]['lft'];
	    $rght = $parent[$alias]['rght'];
	    
	    $where = "{$lft} <= {$alias}.lft AND {$alias}.rght <= {$rght}  AND {$alias}.status > 0 AND {$alias}.deleted = 0";
	    if($childOnly) {
	        $where = "{$lft} < {$alias}.lft AND {$alias}.rght < {$rght}  AND {$alias}.status > 0 AND {$alias}.deleted = 0";
	    }
	    
	    $option  = array(
	                    "select" => "{$alias}.id, {$alias}.title, {$alias}.slug",
	                    "where"  => $where,
	                    "order"  => "{$alias}.index, {$alias}.lft"
	    );
	    
	    $option = $this->_merge($option, $custom);

	    return $this->target->find($option);
	}

	public function getChildren($id = '', $childOnly = false, $custom = array()) {
	    $alias = $this->target->getAlias();
	    
	    $option = array (
	                    'select' => "{$alias}.id, {$alias}.lft, {$alias}.rght",
	                    'where' => "{$alias}.id = {$id} AND {$alias}.status > 0 AND {$alias}.deleted = 0",
	    );
	    
	    $parent = $this->target->find($option, 'first');

	    $lft = $parent[$alias]['lft'];
	    $rght = $parent[$alias]['rght'];
	    
	    $where = "{$lft} <= {$alias}.lft AND {$alias}.rght <= {$rght}  AND {$alias}.status > 0 AND {$alias}.deleted = 0";
	    if($childOnly) {
	        $where = "{$lft} < {$alias}.lft AND {$alias}.rght < {$rght}  AND {$alias}.status > 0 AND {$alias}.deleted = 0";
	    }
	    
	    $option = array (
                            'select' => "{$alias}.id, {$alias}.title, {$alias}.slug",
                            'where'  => $where,
                            'order'  => "{$alias}.index",
            );
	
	    return $this->target->find($option);
	}

    private function _merge($option = array(), $custom = array()) {
        if($custom) {
            foreach($option as $key => $value) {
                if(empty($custom[$key])) continue;
                 
                $option[$key] = $custom[$key];
            }
        }
        
        return $option;
    }
}