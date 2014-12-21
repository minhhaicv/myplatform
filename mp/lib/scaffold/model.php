<?php
class model{

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

        return $this->$func($option, $key);
    }

    private function _findAll($option, $key= 'id') {
        $tmp = $this->retrieve($option);

        if(empty($tmp)) return array();

        $result = array();

        $checker = current($tmp);
        if(empty($checker[$this->alias][$key])) {
            foreach( $tmp as $item ) {
                $result[] = $item;
            }
        } else {
            foreach( $tmp as $item ) {
                $result[$item[$this->alias][$key]] = $item;
            }
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

    private function _findCount($option) {
        extract($option);

        $select = 'count(' . $this->alias .'.id) as count';

        $tmp = $this->retrieve(compact('select', 'where'));

        return empty($tmp) ? 0 : $tmp[0][0]['count'];
    }

    private function _findList($option) {
        $tmp = $this->retrieve($option);

        $exp = explode(',', $option['select']);

        $result = array();

        if(count($exp) == 1) {
            $value = strtr($exp[0], array('category.' => ''));

            foreach( $tmp as $item ) {
                $result[] = $item[$this->alias][$value];
            }
        } else {
            $key   = strtr($exp[0], array('category.' => ''));
            $value = strtr($exp[1], array('category.' => ''));

            foreach( $tmp as $item ) {
                $result[$key] = $item[$this->alias][$value];
            }
        }

        return $result;
    }

    function blank() {
        $blank = array();
        $fields = $this->getColumn();
        foreach($fields as $f) {
            $blank[$f] = '';
        }

        return array($this->getAlias() => $blank);
    }

///////////////////////////////////////////////////////////////
    private function retrieve($option = array()){
        global $db;

        $option['from'] = array($this->table => $this->alias);
        $query = $db->buildQuery($option);

        $q = $db->renderStatement('select', $query);

        return $db->query($q);
    }


    public function getQueriesLog($full = false) {
        global $db;

        $log = $db->getLog();
        if($full) return $log;

        $tmp = array();
        foreach( $log['log'] as $item) {
            $tmp[] = $item['query'];
        }

        return $tmp;
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

        return $db->query($q, true);
    }

    public function update($data) {
        global $db;

        $data['from'] = $this->table;
        $query = $db->buildQuery($data, 'update');

        $q = $db->renderStatement('update', $query);

        return $db->query($q, true);
    }

    public function lastInsertId() {
        global $db;

        return $db->lastInsertId();
    }

    public function getColumn() {
        global $db;

        return $db->getColumn();
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

    public function getTable() {
        return $this->table;
    }
}