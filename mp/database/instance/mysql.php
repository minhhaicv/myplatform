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

require (MP . 'database/dbo/sql.php');


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


// /**
//  * Generates and executes an SQL UPDATE statement for given model, fields, and values.
//  *
//  * @param Model $model
//  * @param array $fields
//  * @param array $values
//  * @param mixed $conditions
//  * @return array
//  */
// 	public function update(Model $model, $fields = array(), $values = null, $conditions = null) {
// 		if (!$this->_useAlias) {
// 			return parent::update($model, $fields, $values, $conditions);
// 		}

// 		if (!$values) {
// 			$combined = $fields;
// 		} else {
// 			$combined = array_combine($fields, $values);
// 		}

// 		$alias = $joins = false;
// 		$fields = $this->_prepareUpdateFields($model, $combined, empty($conditions), !empty($conditions));
// 		$fields = implode(', ', $fields);
// 		$table = $this->fullTableName($model);

// 		if (!empty($conditions)) {
// 			$alias = $this->name($model->alias);
// 			if ($model->name === $model->alias) {
// 				$joins = implode(' ', $this->_getJoins($model));
// 			}
// 		}
// 		$conditions = $this->conditions($this->defaultConditions($model, $conditions, $alias), true, true, $model);

// 		if ($conditions === false) {
// 			return false;
// 		}

// 		if (!$this->execute($this->renderStatement('update', compact('table', 'alias', 'joins', 'fields', 'conditions')))) {
// 			$model->onError();
// 			return false;
// 		}
// 		return true;
// 	}

// /**
//  * Generates and executes an SQL DELETE statement for given id/conditions on given model.
//  *
//  * @param Model $model
//  * @param mixed $conditions
//  * @return boolean Success
//  */
// 	public function delete(Model $model, $conditions = null) {
// 		if (!$this->_useAlias) {
// 			return parent::delete($model, $conditions);
// 		}
// 		$alias = $this->name($model->alias);
// 		$table = $this->fullTableName($model);
// 		$joins = implode(' ', $this->_getJoins($model));

// 		if (empty($conditions)) {
// 			$alias = $joins = false;
// 		}
// 		$complexConditions = false;
// 		foreach ((array)$conditions as $key => $value) {
// 			if (strpos($key, $model->alias) === false) {
// 				$complexConditions = true;
// 				break;
// 			}
// 		}
// 		if (!$complexConditions) {
// 			$joins = false;
// 		}

// 		$conditions = $this->conditions($this->defaultConditions($model, $conditions, $alias), true, true, $model);
// 		if ($conditions === false) {
// 			return false;
// 		}
// 		if ($this->execute($this->renderStatement('delete', compact('alias', 'table', 'joins', 'conditions'))) === false) {
// 			$model->onError();
// 			return false;
// 		}
// 		return true;
// 	}

// /**
//  * Check if the server support nested transactions
//  *
//  * @return boolean
//  */
// 	public function nestedTransactionSupported() {
// 		return $this->useNestedTransactions && version_compare($this->getVersion(), '4.1', '>=');
// 	}

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

///// Pandog
    protected $modified = 'modified';
    protected $default = array('created', 'modified', 'deleted');

    public function buildQuery($query = array(), $type = "select") {
        if($type == 'select')
            return $this->_buildFind($query);

        if($type == 'create')
            return $this->_buildCreate($query);

        if($type == 'update')
            return $this->_buildUpdate($query);
    }

    private function _buildFind($query) {
        $prefix = $this->config['prefix'];

         if (empty($query['select'])) {
            $query['select'] = "*";
        }

        foreach ($query['from'] as $table => $alias) {
            $query['from'] = $prefix . $table . ' AS ' . $alias;
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

                    $extend = ' ' . $join['type'] . ' JOIN ' . $table . ' AS ' . $join['alias'] . ' ' . $condition;

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

    private function _buildCreate($query) {
        $data = $query['fields'];

        $from = $this->config['prefix'].$query['from'];

        $tmp = array_merge(array_keys($data), $this->default);

        $fields = '';
        foreach($tmp as $key) {
            $fields .= "`{$key}`,";
        }

        $fields = trim($fields, ',');

        $list = array($data);

        $value = array();
        foreach($list as $record) {
            foreach($record as $key => $field) {
                if(is_int($field)) continue;

                $record[$key] = "'{$field}'";
            }

            $record = array_merge($record, array("NOW()", "NOW()", '0'));
            $value[] = implode(', ', $record);
        }

        return compact('fields', 'value', 'from');
    }

    public function create($option) {
        $index = 1; $max = 10;

        extract($option);

        $count = count($value);

        $query = $main = "INSERT INTO {$from} ({$fields}) VALUES ";

        foreach( $value as $record ){
            if($index == $max) {
                $index = 0;

                $query .= $main."(".$record.");";
                continue;
            }

            if($index == $count) {
                $query .= "(".$record.");";
                continue;
            }

            $index++;
            $query .= "(".$record."),";
        }

        return $query;
    }

    private function _buildUpdate($query, $option = array()) {
        $prefix = $this->config['prefix'];

        $query['from'] = $this->config['prefix'].$query['from'];
        $query['fields'] = array_merge($query['fields'], array($this->modified => 'NOW()'));

        $fields = '';
        foreach($query['fields'] as $key => $value) {
            $update = $value;
            if(strpos($value, '`') === false)
               $update = "'{$value}'";

            if($key == $this->modified) {
                $update = $value;
            }

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
                        'limit' => "LIMIT",
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
