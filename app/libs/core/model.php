<?php
class Model{

    public function findById($id, $fields = array()) {
        $option = array (
                        'select'=> empty($fields) ? "*" : $fields,
                        'where' => "{$this->alias}.id = {$id} AND {$this->alias}.deleted = 0",
                        'order' => "{$this->alias}.id desc",
                    );
        
        return $this->_findFirst($option);
    }
	
	public function find($option = array(), $type = 'all', $key = 'id') {
		$func = '_find'.ucfirst($type);
		
		return $this->$func($option);
	}
	
	private function _findAll($option, $key= 'id') {
	    $tmp = $this->retrieve($option);
	    
	    $alias = $this->alias;
	    
	    $result = array();
	    
	    foreach( $tmp as $item ) {
	        if(empty($item[$alias][$key])) $result[] = $item;
	        else $result[$item[$alias][$key]] = $item;
	    }
	    
	    return $result;
	}
	
	private function _findFirst($option) {
	    $tmp = $this->retrieve($option);

	    $result = array();
	     
	    foreach( $tmp as $item ) {
	        $result = $item;
	    }
	     
	    return $result;
	}
	
	private function retrieve($option = array()){
		global $db;
		
		$option['from'] = array($this->table => $this->alias);
		$query = $db->buildQuery($option);
		
		$q = $db->renderStatement('select', $query);
		print "<pre>";
		print_r($q);
		print "</pre>";
		return $db->query($q);
	}
	
	public function query($query = '') {
        global $db;
  
        return $db->query($query);
	}

	public function create($data = array()) {
	    global $db;
	    
	    $option = array(
	    	      'from' => $this->table,
                  'fields' => $data
	    );
	    $query = $db->buildQuery($option, 'create');
	    
	    $q = $db->renderStatement('create', $query);
print "<pre>";
print_r($q);
print "</pre>";
	    return $db->query($q, true);
	}
	
	public function update($data) {
	    global $db;

	    $data['from'] = $this->table;
	    $query = $db->buildQuery($data, 'update');
	    
	    $q = $db->renderStatement('update', $query);
	    
	    print "<pre>";
	    print_r($q);
	    print "</pre>";
	    return $db->query($q, true);
	}
	
	public function lastInsertId() {
	    global $db;
	    
	    return $db->lastInsertId();
	}
	
	
	protected $table 			= "";
	protected $alias 			= "";
	protected $primaryKey		= "";
	
	
	
	
	function __construct($table='', $alias = '', $primaryKey='id'){
		$this->table = $table;
		$this->alias = $alias;
		$this->primaryKey = $primaryKey;
	}
	
	function __destruct() {
		unset($this);
	}

	public function getAlias() {
	    return $this->alias;
	}
}