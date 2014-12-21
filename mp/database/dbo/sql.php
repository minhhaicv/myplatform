<?php
/**
 * Dbo Source
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
 * @package       Cake.Model.Datasource
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


/**
 * DboSource
 *
 * Creates DBO-descendant objects from a given db connection configuration
 *
 * @package       Cake.Model.Datasource
 */
class Sql{
    /**
     * Flag to support nested transactions. If it is set to false, you will be able to use
     * the transaction methods (begin/commit/rollback), but just the global transaction will
     * be executed.
     *
     * @var boolean
     */
    public $useNestedTransactions = false;

    /**
     * Print full query debug info?
     *
     * @var boolean
     */
    public $fullDebug = false;

    /**
     * String to hold how many rows were affected by the last SQL operation.
     *
     * @var string
     */
    public $affected = null;

    /**
     * Number of rows in current resultset
     *
     * @var integer
     */
    public $numRows = null;

    /**
     * Time the last query took
     *
     * @var integer
     */
    public $took = null;

    /**
     * Result
     *
     * @var array
     */
    protected $_result = null;

    /**
     * Queries count.
     *
     * @var integer
     */
    protected $_queriesCnt = 0;

    /**
     * Total duration of all queries.
     *
     * @var integer
     */
    protected $_queriesTime = null;

    /**
     * Log of queries executed by this DataSource
     *
     * @var array
     */
    protected $_queriesLog = array();

    public function __construct($config = array()) {
        global $config;

        $this->fullDebug = $config->vars['debug'] > 1;
    }

    public function renderStatement($type, $data) {
        //extract($data);
        $aliases = null;

        switch (strtolower($type)) {
            case 'select':
                return $this->select($data);
            case 'create':
                return $this->create($data);
            case 'update':
                return $this->update($data);
           case 'delete':
                if (!empty($alias)) {
                   $aliases = "{$this->alias}{$alias} {$joins} ";
                }
                return trim("DELETE {$alias} FROM {$table} {$aliases}{$conditions}");
        }
    }

    public function hasResult() {
        return $this->_result instanceof PDOStatement;
    }

    public function fetchAll($query, $params = array(), $options = array()) {
        $result = $this->execute($query, array(), $params);
        if ($result) {
            $out = array();

            if ($this->hasResult()) {
                $first = $this->fetchRow();
                if ($first) {
                    $out[] = $first;
                }
                while ($item = $this->fetchResult()) {
                    $out[] = $item;
                }
            }

            if (empty($out) && is_bool($this->_result)) {
                return $this->_result;
            }

            return $out;
        }

        return false;
    }


    public function query($query, $nonQuery = false) {
        if($nonQuery) {
            $return = $this->_execute($query);
            return !empty($return);
        }
        return $this->fetchAll($query);
    }


    public function execute($sql, $options = array(), $params = array()) {
        $options += array('log' => $this->fullDebug);

        $t = microtime(true);
        $this->_result = $this->_execute($sql, $params);

        if ($options['log']) {
            $this->took = round((microtime(true) - $t) * 1000, 0);
            $this->numRows = $this->affected = $this->lastAffected();
            $this->logQuery($sql, $params);
        }

        return $this->_result;
    }
    /**
     * Returns number of affected rows in previous database operation. If no previous operation exists,
     * this returns false.
     *
     * @param mixed $source
     * @return integer Number of affected rows
     */
    public function lastAffected($source = null) {
        if ($this->hasResult()) {
            return $this->_result->rowCount();
        }
        return 0;
    }



    /**
     * Log given SQL query.
     *
     * @param string $sql SQL statement
     * @param array $params Values binded to the query (prepared statements)
     * @return void
     */
    public function logQuery($sql, $params = array()) {
        $this->_queriesCnt++;
        $this->_queriesTime += $this->took;
        $this->_queriesLog[] = array(
                        'query' => $sql,
                        'params' => $params,
                        'affected' => $this->affected,
                        'numRows' => $this->numRows,
                        'took' => $this->took
        );
    }

    public function getLog($sorted = false, $clear = true) {
        $log = $this->_queriesLog;
        if ($clear) {
            $this->_queriesLog = array();
        }
        return array('log' => $log, 'count' => $this->_queriesCnt, 'time' => $this->_queriesTime);
    }

    protected function _execute($sql, $params = array(), $prepareOptions = array()) {
        $sql = trim($sql);

        try {
            $query = $this->_connection->prepare($sql, $prepareOptions);
            $query->setFetchMode(PDO::FETCH_LAZY);
            if (!$query->execute($params)) {
                $this->_results = $query;
                $query->closeCursor();
                return false;
            }
            if (!$query->columnCount()) {
                $query->closeCursor();
                if (!$query->rowCount()) {
                    return true;
                }
            }

            return $query;
        } catch (PDOException $e) {
            if (isset($query->queryString)) {
                $e->queryString = $query->queryString;
            } else {
                $e->queryString = $sql;
            }
            throw $e;
        }
    }



    /**
     * Returns a row from current resultset as an array
     *
     * @param string $sql Some SQL to be executed.
     * @return array The fetched row as an array
     */
    public function fetchRow($sql = null) {
        if (is_string($sql) && strlen($sql) > 5 && !$this->execute($sql)) {
            return null;
        }

        if ($this->hasResult()) {
            $this->resultSet($this->_result);
            $resultRow = $this->fetchResult();

            return $resultRow;
        }
        return null;
    }



    public function getColumn($table = '') {
        $sql = "DESCRIBE {$table}";
        $query = $this->_connection->prepare($sql);
        $query->execute();
        // $results->getColumnMeta($index);
        return $column = $query->fetchAll(PDO::FETCH_COLUMN);
    }
}
