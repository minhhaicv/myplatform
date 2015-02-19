<?php
class model{

    public function findById($id, $fields = '') {
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
        $result = $this->__retrieve($option);

        if (empty($result)) {
            return array();
        }

        if (Hash::check($result, "{n}.{$this->alias}.{$key}")) {
            return Hash::combine($result, "{n}.{$this->alias}.{$key}", "{n}");
        }

        return $result;
    }

    private function __findFirst($option) {
        $option['limit'] = 1;
        $result = $this->__retrieve($option);

        if (empty($result)) {
            return array();
        }

        return current($result);
    }

    private function __findCount($option) {
        extract($option);

        $select = 'count(' . $this->alias .'.id) as count';

        $tmp = $this->__retrieve(compact('select', 'where'));

        return empty($tmp) ? 0 : $tmp[0][0]['count'];
    }

    private function __findList($option) {
        $result = $this->__retrieve($option);

        if (empty($result)) {
            return array();
        }

        $exp = explode(',', strtr($option['select'], array(' ' => '')));

        $key = strtr($exp[0], array($this->alias.'.' => ''));
        if (empty($exp[1])) {
           $value = $key;
        } else {
            $value = strtr($exp[1], array($this->alias.'.' => ''));
        }

        return Hash::combine($result, "{n}.{$this->alias}.{$key}", "{n}.{$this->alias}.{$value}");
    }

    public function delete($condition = '') {
        $userId = Session::read('auth.user.id');

        $option = array(
                        'fields' => array('modified' => 'NOW()', 'editor' => $userId, 'deleted' => 1),
                        'where' => $condition
        );

        return $this->update($option);
    }

    public function save($data) {
        $userId = Session::read('auth.user.id');

        if (empty($data[$this->primaryKey])) {
            $default = array(
                            'modified' => 'NOW()',
                            'editor'   => $userId,
                            'created'  => 'NOW()',
                            'creator'  => $userId,
                            'deleted'  => 0
            );
            $data = array_merge($default, $data);

            return $this->create($data);
        }

        $default = array(
                        'modified' => 'NOW()',
                        'editor'   => $userId,
                        'deleted'  => 0
        );
        $data = array_merge($default, $data);
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
                      'from'   => $this->table,
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