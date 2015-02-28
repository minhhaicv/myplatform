<?php
/**
 * MySQL layer for DBO
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Model.Datasource.Database
 * @since         CakePHP(tm) v 0.10.5.1790
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


/**
 * MySQL DBO driver object
 *
 * Provides connection and SQL generation for MySQL RDMS
 *
 * @package       Cake.Model.Datasource.Database
 */

Helper::attach(MP.'database'.DS.'dbo'.DS.'sql.php');

class Mysql extends Sql{

    public $config = array();
/**
 * Reference to the PDO object connection
 *
 * @var PDO $_connection
 */
    protected $_connection = null;


/**
 * use alias for update and delete. Set to true if version >= 4.1
 *
 * @var boolean
 */
    protected $_useAlias = true;

    public function __construct($config) {
        $this->config = $config;
        parent::__construct($config);
    }

/**
 * Connects to the database using options in the given configuration array.
 *
 * MySQL supports a few additional options that other drivers do not:
 *
 * - `unix_socket` Set to the path of the MySQL sock file. Can be used in place
 *   of host + port.
 * - `ssl_key` SSL key file for connecting via SSL. Must be combined with `ssl_cert`.
 * - `ssl_cert` The SSL certificate to use when connecting via SSL. Must be
 *   combined with `ssl_key`.
 * - `ssl_ca` The certificate authority for SSL connections.
 *
 * @return boolean True if the database could be connected, else false
 * @throws MissingConnectionException
 */
    public function connect() {
        $this->connected = false;

        $config = $this->config;
        $flags = array(
            PDO::ATTR_PERSISTENT => $config['persistent'],
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        if (!empty($config['encoding'])) {
            $flags[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $config['encoding'];
        }
        if (!empty($config['ssl_key']) && !empty($config['ssl_cert'])) {
            $flags[PDO::MYSQL_ATTR_SSL_KEY] = $config['ssl_key'];
            $flags[PDO::MYSQL_ATTR_SSL_CERT] = $config['ssl_cert'];
        }
        if (!empty($config['ssl_ca'])) {
            $flags[PDO::MYSQL_ATTR_SSL_CA] = $config['ssl_ca'];
        }
        if (empty($config['unix_socket'])) {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        } else {
            $dsn = "mysql:unix_socket={$config['unix_socket']};dbname={$config['database']}";
        }

        try {
            $this->_connection = new PDO(
                $dsn,
                $config['login'],
                $config['password'],
                $flags
            );
            $this->connected = true;
            if (!empty($config['settings'])) {
                foreach ($config['settings'] as $key => $value) {
                    $this->_execute("SET $key=$value");
                }
            }

        } catch (PDOException $e) {
            throw new MissingConnectionException(array(
                'class' => get_class($this),
                'message' => $e->getMessage()
            ));
        }

        $this->_useAlias = (bool)version_compare($this->getVersion(), "4.1", ">=");

        return $this->connected;
    }

/**
 * Check whether the MySQL extension is installed/loaded
 *
 * @return boolean
 */
    public function enabled() {
        return in_array('mysql', PDO::getAvailableDrivers());
    }

    public function getVersion() {
        return $this->_connection->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    public function disconnect() {
        unset($this->_connection);
    }

    public function resultSet($results) {
        $this->map = array();
        $numFields = $results->columnCount();
        $index = 0;

        while ($numFields-- > 0) {
            $column = $results->getColumnMeta($index);
            $k = empty($column['table']) ? 0 : $column['table'];
            $this->map[$index++] = array($k, $column['name']);
        }
    }

    public function fetchResult() {
        if ($row = $this->_result->fetch(PDO::FETCH_NUM)) {
            $resultRow = array();
            foreach ($this->map as $col => $meta) {
                list($table, $column) = $meta;
                $resultRow[$table][$column] = $row[$col];
            }
            return $resultRow;
        }

        $this->_result->closeCursor();
        return false;
    }

    public function buildQuery($query = array(), $type = "select") {
        if($type == 'select')
            return $this->__buildFind($query);

        if($type == 'create')
            return $this->__buildCreate($query);

        if($type == 'update')
            return $this->__buildUpdate($query);
    }

    private function __buildFind($query) {
        $prefix = $this->config['prefix'];

        if (empty($query['select'])) {
            $query['select'] = "*";
        }

        foreach ($query['from'] as $table => $alias) {
            $query['from'] = $prefix . $table . ' AS `' . $alias . '`';

            $query['select'] = strtr($query['select'], array("{$alias}." => "`{$alias}`."));

            break;
        }

        if(!empty($query['joins'])) {
            foreach ($query['joins'] as $list) {
                foreach( $list as $table => $join) {
                    $table = $prefix . $table;

                    $condition = 'ON (';
                    foreach( $join['condition'] as $fk => $pk) {
                        $condition .= $fk . ' = ' . $pk . ' ';
                    }

                    $condition = trim($condition) . ')';

                    $extend = ' ' . $join['type'] . ' JOIN ' . $table . ' AS `' . $join['alias'] . '` ' . $condition;

                    $query['from'] .= $extend;
                }
            }

            unset($query['joins']);
        }

        if (empty($query['page'])) {
            $query['page'] = 1;
        }

        if ($query['page'] > 1 && !empty($query['limit'])) {
            $query['offset'] = ($query['page'] - 1) * $query['limit'];
        }

        if (!empty($query['limit'])) {
            $page = (empty($query['page'])) ? 1 : $query['page'];
            $offset = ($page - 1) * $query['limit'];
            $query['limit'] = "{$offset}, {$query['limit']}";
        }

        return $query;
    }

    private function __buildCreate($query) {
        $dbFields = $this->getColumn($query['from']);

        $from = $this->config['prefix'].$query['from'];

        $index  = 0;
        $fields = '';
        $value  = $ignore = array();

        foreach ($query['fields'] as $record) {
            if ($index++ == 0) {
                $tmp = array_keys($record);
                foreach($tmp as $key) {
                    if (in_array($key, $dbFields)) {
                        $fields .= "`{$key}`,";
                    } else {
                        $ignore[] = $key;
                    }
                }

                $fields = trim($fields, ',');
            }

            foreach($record as $key => $field) {
                if (in_array($key, $ignore)) {
                    continue;
                }

                $record[$key] = $this->__format($field);
            }

            $value[] = implode(', ', $record);
        }

        return compact('fields', 'value', 'from');
    }

    private function __format($value = '') {
        $special = array('NOW()');

        if ( in_array($value, $special)) {
            return $value;
        }

        return "'{$value}'";
    }

    public function create($option) {
        extract($option);

        $main = "INSERT INTO {$from} ({$fields}) VALUES ";

        $run = array_chunk($value, 10, true);

        $query = '';
        foreach ($run as $value) {
            $query .= $main;
            foreach ($value as $record) {
                $query .= "(".$record."),";
            }

            $query = rtrim($query, ',') . ';';
        }

        return $query;
    }

    private function __buildUpdate($query, $option = array()) {
        $prefix = $this->config['prefix'];

        $query['from'] = $this->config['prefix'].$query['from'];

        $fields = '';
        foreach($query['fields'] as $key => $value) {
            $update = $this->__format($value);

            $fields .= "`{$key}` = {$update},";
        }

        $fields = trim($fields, ',');

        $query['fields'] = $fields;

        return $query;
    }

    public function select($query = array()) {
        $key = array(
                    'select' => "SELECT",
                    'from'   => "FROM",
                    'where'  => "WHERE",
                    'group'  => "GROUP BY",
                    'having' => "HAVING",
                    'order'  => "ORDER BY",
                    'limit'  => "LIMIT",
        );

        $q = '';
        foreach( $key as $k => $v) {
            if(empty($query[$k])) continue;

            $q .= "{$v} {$query[$k]} ";
        }

        return trim($q);
    }

    public function update($query = array()) {
        extract($query);

        return trim("UPDATE {$from} SET {$fields} WHERE {$where}");
    }

    /**
     * Returns the ID generated from the previous INSERT operation.
     *
     * @param mixed $source
     * @return mixed
     */
    public function lastInsertId() {
        return $this->_connection->lastInsertId();
    }


    public function getColumn($table = '') {
        $table = $this->config['prefix'] . $table;
        return parent::getColumn($table);
    }
}