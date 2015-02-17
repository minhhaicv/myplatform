<?php
class model{

    public function findById($id, $fields = array()) {
        $option = array (
                        'select'=> empty($fields) ? "*" : $fields,
                        'where' => "{$this->alias}.id = {$id} AND {$this->alias}.deleted = 0",
                        'order' => "{$this->alias}.id desc",
                        'limit'  => 1,
        );

        return $this->__findFirst($option);
    }

    public function find($option = array(), $type = 'all', $key = 'id') {
        $func = '__find'.ucfirst($type);

        return $this->$func($option, $key);
    }

    private function __findAll($option, $key= 'id') {
        $tmp = $this->__retrieve($option);

        if (empty($tmp)) {
            return array();
        }

        $result = array();

        $checker = current($tmp);
        if (empty($checker[$this->alias][$key])) {
            foreach ($tmp as $item) {
                $result[] = $item;
            }
        } else {
            foreach ($tmp as $item) {
                $result[$item[$this->alias][$key]] = $item;
            }
        }

        return $result;
    }

    private function __findFirst($option) {
        $result = array();

        $tmp = $this->__retrieve($option);
        foreach ($tmp as $item) {
            $result = $item;
        }

        return $result;
    }

    private function __findCount($option) {
        extract($option);

        $select = 'count(' . $this->alias .'.id) as count';

        $tmp = $this->__retrieve(compact('select', 'where'));

        return empty($tmp) ? 0 : $tmp[0][0]['count'];
    }

    private function __findList($option) {
        $tmp = $this->__retrieve($option);

        $exp = explode(',', $option['select']);

        $result = array();

        if (count($exp) == 1) {
            $value = strtr($exp[0], array($this->alias.'.' => ''));

            foreach ($tmp as $item) {
                $result[] = $item[$this->alias][$value];
            }
        } else {
            $key   = strtr($exp[0], array($this->alias.'.' => ''));
            $value = strtr($exp[1], array($this->alias.'.' => ''));

            foreach ($tmp as $item) {
                $result[$key] = $item[$this->alias][$value];
            }
        }

        return $result;
    }

    public function delete($condition = '') {
        $option = array(
                        'fields' => array('deleted' => 1),
                        'where' => $condition
        );

        return $this->update($option);
    }

    public function save($data) {
        if (empty($data[$this->primaryKey])) {
            return $this->create($data);
        }

        $option = array(
                        'fields' => $data,
                        'where' => $this->primaryKey . " = ".$data[$this->primaryKey]
        );

        return $this->update($option);
    }

    public function init($fields = array()) {
        $value = array('status' => 1);
        $default = array();

        if (empty($fields)) {
            $fields = $this->getColumn();
        }

        foreach ($fields as $f) {
            $default[$f] = empty($value[$f]) ? '' : $value[$f];
        }

        return array($this->getAlias() => $default);
    }

///////////////////////////////////////////////////////////////
    private function __retrieve($option = array()){
        $option['from'] = array($this->table => $this->alias);
        $query = Helper::db()->buildQuery($option);

        $q = Helper::db()->renderStatement('select', $query);

        return Helper::db()->query($q);
    }

    public function getQueries($full = false) {
        $log = Helper::db()->getLog();
        if ($full) {
            return $log;
        }

        $tmp = array();
        foreach ($log['log'] as $item) {
            $tmp[] = $item['query'];
        }

        return $tmp;
    }

    public function query($query = '') {
        return Helper::db()->query($query);
    }

    public function create($data = array()) {
        $option = array(
                      'from' => $this->table,
                      'fields' => $data
        );

        $query = Helper::db()->buildQuery($option, 'create');

        $q = Helper::db()->renderStatement('create', $query);

        return Helper::db()->query($q, true);
    }

    public function update($data) {
        $data['from'] = $this->table;
        $query = Helper::db()->buildQuery($data, 'update');

        $q = Helper::db()->renderStatement('update', $query);

        return Helper::db()->query($q, true);
    }

    public function lastInsertId() {
        return Helper::db()->lastInsertId();
    }

    public function getColumn() {
        return Helper::db()->getColumn($this->table);
    }

    protected $table             = "";
    protected $alias             = "";
    protected $primaryKey        = "";


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

    public function getPrimaryKey() {
        return $this->primaryKey;
    }
}