<?php

/**
 * Database
 * 
 * A class for performing DML
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => August 2021
 * @link        alabiansolutions.com
 */

class DatabaseExpection extends Exception
{
}

class Database
{
    /** @var  string indication of mysql variant of sql to be written */
    public const MYSQL = "mysql";

    /** @var  string indication of sqlserver variant of sql to be written */
    public const MSSQL = "mssql";

    /** @var string table in db on which DML is to performed on */
    private $_table;

    /** @var string file where the class is called from */
    private $_file;

    /** @var PDO an instance of PDO */
    private $_PDO;

    /** @var  string copy of query to be run */
    private $_theQuery;

    /** @var  array copy of data to be supplied to query to be run */
    private $_theQueryData;

    /** @var  array a check if the query to be run should be displayed */
    private $_showQuery;

    /** @var  string the db engine been used */
    private $_dbEngine;

    /**
     * instantiation of Database
     *
     * @param string $file the file where the method is called
     * @param string $table table in db on which DML is to performed on
     * @param string $dbEngine the db engine been used
     */
    public function __construct(string $file, PDO $PDO, string $table, string $dbEngine = Database::MYSQL)
    {
        $this->_file = $file;
        $this->_PDO = $PDO;
        $this->_table = $table;
        $this->_dbEngine = $dbEngine;
    }

    /**
     * get the present table been used for DML
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->_table;
    }

    /**
     * change the present table been used for DML
     * @param string $table table to used for DML
     * @return string
     */
    public function setTable(string $table)
    {
        $this->_table = $table;
    }

    /**
     * peform any query
     *
     * @param int $line the line where the method is called
     * @param string $query sql query to be run
     * @param array $bindValue an array of the bind value
     * @return array ['data' => $data, 'newId' => $newId, 'rowCount' => $rowCount]
     */
    public function queryStatment(int $line, string $query, array $bindValue = []): array
    {
        if ($this->_showQuery) {
            $this->_theQuery = $query;
            $this->_theQueryData = $bindValue;
            $this->_showQuery = false;
            return [];
        }
        $data = [];
        $newId = 0;
        $rowCount = 0;

        try {
            $PDOStatment = $this->_PDO->prepare($query);
            $execute = ($bindValue) ? $PDOStatment->execute($bindValue) : $PDOStatment->execute();
            if ($execute) {
                if (strtolower(substr(trim($query), 0, 6)) == 'select') {
                    while ($result = $PDOStatment->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $result;
                    }
                } else {
                    if (strtolower(substr(trim($query), 0, 6)) == 'insert')
                        if (@$this->_PDO->lastInsertId()) $newId = @$this->_PDO->lastInsertId();
                    $rowCount = $PDOStatment->rowCount();
                }
            } else {
                $this->sendE("Query Error: " . $PDOStatment->errorInfo()[2], $this->_file, $line, "Exception");
            }
            $PDOStatment->closeCursor();
        } catch (PDOException $e) {
            $this->sendE("Db Error: " . $e->getMessage(), $this->_file, $line, "Exception");
        }
        return ['data' => $data, 'newId' => $newId, 'rowCount' => $rowCount];
    }

    /**
     * perform SQL SELECT query
     * 
     * @param int $line the line where the method is called
     * @param array $retrieveCols column name to be gotten from the db
     * @param array $whereCols where clause [[column=>val, comparsion=>val, value/bindAbleValue=>val, logic=>val], ...]
     * @param array $orders order by clause [colName1=>ASC, colName2=>DESC, ...]
     * @param array $limit limit by(mysql) or top clause(mssql) [length/top, start]
     * @return array
     */
    public function select(int $line, array $retrieveCols = [], array $whereCols = [], array $orders = [], array $limit = []): array
    {
        $dbExpectionMsg = "illegal parameter";
        if ($whereCols) {
            $this->whereParameter($line, $whereCols);
        }

        $dbExpectionMsg = "illegal parameter";
        if ($orders) {
            if (!(array_keys($orders) !== range(0, count($orders) - 1))) {
                $errorMsg = $dbExpectionMsg . ", 3rd parameter must an associative array";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            foreach ($orders as $aOrderCol => $anOrderVal) {
                if (!in_array(strtoupper($anOrderVal), ['ASC', 'DESC'])) {
                    $errorMsg = $dbExpectionMsg . ", values in 3rd parameter are either ASC or DESC";
                    $this->sendE($errorMsg, $this->_file, $line);
                }
            }
        }

        $dbExpectionMsg = "illegal parameter";
        if ($limit) {
            if (!(is_numeric($limit[0]))) {
                $errorMsg = $dbExpectionMsg . ", 4th parameter length/top must be a number";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            if (isset($limit[1]) && !(is_numeric($limit[1]))) {
                $errorMsg = $dbExpectionMsg . ", 4th parameter start must be a number";
                $this->sendE($errorMsg, $this->_file, $line);
            }
        }

        $bindValue = $data = [];
        $fields = $where = $order = $limitBy = $top = "";

        if ($retrieveCols) {
            foreach ($retrieveCols as $aCol) $fields .= " $aCol, ";
            $fields = rtrim($fields, ", ");
        } else {
            $fields = "*";
        }

        if ($whereCols) {
            $whereClause = $this->generateWhereClause($whereCols);
            $where = $whereClause['where'];
            $bindValue = $whereClause['bindValue'];
        }

        if ($orders) {
            $order = " ORDER BY ";
            foreach ($orders as $column => $ordering) {
                $order .= " $column $ordering ";
            }
        }

        if ($limit) {
            if ($this->_dbEngine == Database::MSSQL) {
                $top = " TOP ({$limit[0]}) ";
            }
            if ($this->_dbEngine == Database::MYSQL) {
                $limitBy = " LIMIT {$limit[0]} ";
                if (isset($limit[1])) $limitBy .= " OFFSET {$limit[1]}";
            }
        }

        $query = "SELECT $top $fields  FROM {$this->_table} $where $order $limitBy";

        if ($this->_showQuery) {
            $this->_theQuery = $query;
            $this->_theQueryData = $bindValue;
            $this->_showQuery = false;
            return [];
        }

        try {
            $PDOStatment = $this->_PDO->prepare($query);
            $execute = ($bindValue) ? $PDOStatment->execute($bindValue) : $PDOStatment->execute();
            if ($execute) {
                while ($result = $PDOStatment->fetch(PDO::FETCH_ASSOC)) {
                    $data[] = $result;
                }
            } else {
                $errorMsg = "Query Error: " . $PDOStatment->errorInfo()[2];
                $this->sendE($errorMsg, $this->_file, $line, "Exception");
            }
            $PDOStatment->closeCursor();
        } catch (PDOException $e) {
            $this->sendE($e->getMessage(), $this->_file, $line, "Exception");
        }
        return $data;
    }

    /**
     * perform SQL INSERT query
     *
     * @param int $line the line where the method is called
     * @param array $cols [colName=>[colValue=>value, isFunction=>false, isBindAble=>false], ...] a collection of field name and their values
     * @return array [lastInsertId, rowCount];
     */
    public function insert(int $line, array $cols): array
    {
        $dbExpectionMsg = "illegal parameter, non empty 2X2 associative array expected";
        if (!(array_keys($cols) !== range(0, count($cols) - 1)))
            $this->sendE($dbExpectionMsg, $this->_file, $line);
        foreach ($cols as $aCol) {
            if (!is_array($aCol) || (is_array($aCol) && !count($aCol)))
                $this->sendE($dbExpectionMsg, $this->_file, $line);
            if (!(array_keys($aCol) !== range(0, count($aCol) - 1)))
                $this->sendE($dbExpectionMsg, $this->_file, $line);
            if (!isset($aCol['colValue'])) {
                $errMsg = "illegal parameter, an element is missing colValue key";
                $this->sendE($errMsg, $this->_file, $line);
            }
        }

        $rowCount = 0;
        $newId = Functions::NO_INT_VALUE;
        $colNames = "";
        $colValues = "";
        $bindValue = [];
        foreach ($cols as $aColName => $aColValue) {
            $colNames .= " $aColName, ";

            if (isset($aColValue['isFunction']) && $aColValue['isFunction']) {
                $colValues .= " {$aColValue['colValue']}, ";
            }
            if (isset($aColValue['isBindAble']) && $aColValue['isBindAble']) {
                $colValues .= " :$aColName, ";
                $bindValue[$aColName] = $aColValue['colValue'];
            }
            if (isset($aColValue['isBindAble']) && !$aColValue['isBindAble']) {
                if (!isset($aColValue['isFunction'])) {
                    $colValues .= is_numeric($aColValue['colValue']) ?
                        " {$aColValue['colValue']}, " : " {$this->_PDO->quote($aColValue['colValue'])}, ";
                }
            }
            if ((!isset($aColValue['isFunction']) && !isset($aColValue['isBindAble']))) {
                $colValues .= is_numeric($aColValue['colValue']) ?
                    " {$aColValue['colValue']}, " : " {$this->_PDO->quote($aColValue['colValue'])}, ";
            }
        }
        $colNames = rtrim($colNames, ", ");
        $colValues = rtrim($colValues, ", ");

        $query = "INSERT INTO {$this->_table} ($colNames) VALUES ($colValues)";
        if ($this->_showQuery) {
            $this->_theQuery = $query;
            $this->_theQueryData = $bindValue;
            $this->_showQuery = false;
            return [];
        }

        try {
            $PDOStatment = $this->_PDO->prepare($query);
            $execute =  ($bindValue) ? $PDOStatment->execute($bindValue) : $PDOStatment->execute();
            if ($execute) {
                if (@$this->_PDO->lastInsertId()) $newId = @$this->_PDO->lastInsertId();
                $rowCount = $PDOStatment->rowCount();
            } else {
                $this->sendE("Query Error: " . $PDOStatment->errorInfo()[2], $this->_file, $line, "Exception");
            }
            $PDOStatment->closeCursor();
        } catch (PDOException $e) {
            $this->sendE("Db Error: " . $e->getMessage(), $this->_file, $line, "Exception");
        }
        return ['lastInsertId' => $newId, 'rowCount' => $rowCount];
    }

    /**
     * perform SQL UPDATE query
     *
     * @param int $line the line where the method is called
     * @param array $cols [colName=>[colValue=>value, isFunction=>false, isBindAble=>false], ...] fields and values(value type)
     * @param array $whereCols where clause [[column, comparsion, value/bindAbleValue, logic], ...]
     * @return int
     */
    public function update(int $line, array $cols, array $whereCols): int
    {
        $dbExpectionMsg = "illegal parameter";
        if (!(array_keys($cols) !== range(0, count($cols) - 1))) {
            $dbExpectionMsg .= ", parameter one must be non empty 2X2 associative array";
            $this->sendE($dbExpectionMsg, $this->_file, $line);
        }
        foreach ($cols as $aCol) {
            if (!is_array($aCol) || (is_array($aCol) && !count($aCol))) {
                $dbExpectionMsg .= ", 2nd parameter must be non empty 2X2 associative array";
                $this->sendE($dbExpectionMsg, $this->_file, $line);
            }
            if (!(array_keys($aCol) !== range(0, count($aCol) - 1))) {
                $dbExpectionMsg .= ", 2nd parameter must be non empty 2X2 associative array";
                $this->sendE($dbExpectionMsg, $this->_file, $line);
            }
            if (!isset($aCol['colValue'])) {
                $dbExpectionMsg .= ", an element in 2nd parameter is missing colValue key";
                $this->sendE($dbExpectionMsg, $this->_file, $line);
            }
        }

        $this->whereParameter($line, $whereCols);

        $rowCount = 0;
        $setColumn = "";
        $bindValue = [];

        foreach ($cols as $aColName => $aColValue) {
            if (isset($aColValue['isFunction']) && $aColValue['isFunction']) {
                $setColumn .= " $aColName = {$aColValue['colValue']}, ";
            }
            if (isset($aColValue['isBindAble']) && $aColValue['isBindAble']) {
                $setColumn .= " $aColName = :$aColName, ";
                $bindValue[$aColName] = $aColValue['colValue'];
            }
            if (isset($aColValue['isBindAble']) && !$aColValue['isBindAble']) {
                if (!isset($aColValue['isFunction'])) {
                    $setColumn .= is_numeric($aColValue['colValue']) ?
                        " $aColName = {$aColValue['colValue']}, " : " $aColName = '{$aColValue['colValue']}', ";
                }
            }
            if ((!isset($aColValue['isFunction']) && !isset($aColValue['isBindAble']))) {
                $setColumn .= is_numeric($aColValue['colValue']) ?
                    " $aColName = {$aColValue['colValue']}, " : " $aColName = '{$aColValue['colValue']}', ";
            }
        }
        $setColumn = rtrim($setColumn, ", ");

        $whereClause = $this->generateWhereClause($whereCols);
        $where = $whereClause['where'];
        $bindValue = ($bindValue) ? array_merge($bindValue, $whereClause['bindValue']) : $whereClause['bindValue'];

        $query = "UPDATE {$this->_table} SET $setColumn $where ";
        if ($this->_showQuery) {
            $this->_theQuery = $query;
            $this->_theQueryData = $bindValue;
            $this->_showQuery = false;
            return Functions::NO_INT_VALUE;
        }

        try {
            $PDOStatment = $this->_PDO->prepare($query);
            ($bindValue) ? $execute = $PDOStatment->execute($bindValue) : $execute = $PDOStatment->execute();
            if ($execute) {
                $rowCount = $PDOStatment->rowCount();
            } else {
                $this->sendE("Query Error: " . $PDOStatment->errorInfo()[2], $this->_file, $line, "Exception");
            }
            $PDOStatment->closeCursor();
        } catch (PDOException $e) {
            $this->sendE("Db Error: " . $e->getMessage(), $this->_file, $line, "Exception");
        }

        return $rowCount;
    }

    /**
     * perform SQL DELETE query
     *
     * @param array $whereCols where clause [[column, comparsion, value/bindAbleValue, logic], ...]
     * @return int;
     */
    public function delete(int $line, array $whereCols): int
    {
        $this->whereParameter($line, $whereCols);

        $rowCount = Functions::NO_INT_VALUE;
        $whereClause = $this->generateWhereClause($whereCols);

        $query = "DELETE FROM {$this->_table} {$whereClause['where']} ";
        try {
            $PDOStatment = $this->_PDO->prepare($query);
            ($whereClause['bindValue']) ? $execute = $PDOStatment->execute($whereClause['bindValue']) :
                $execute = $PDOStatment->execute();
            if ($execute) {
                $rowCount = $PDOStatment->rowCount();
            } else {
                $this->sendE("Query Error: " . $PDOStatment->errorInfo()[2], $this->_file, $line, "Exception");
            }
            $PDOStatment->closeCursor();
        } catch (PDOException $e) {
            $this->sendE("Db Error: " . $e->getMessage(), $this->_file, $line, "Exception");
        }
        return $rowCount;
    }

    /**
     * 
     * Show the [query, data] generate by a method
     *
     * @param string $method the method/query name
     * @param array $data the parameter been passed to the method
     * @return array
     */
    public function getGeneratedQuery(string $method, array $data = []): array
    {
        $methodCollection = ['select', 'insert', 'update', 'delete'];
        if (!in_array(strtolower($method), $methodCollection))
            throw new DatabaseExpection("unsupported method/query");
        $this->_showQuery = true;
        if ($method == "select") {
            if (isset($data[0]) && !isset($data[1]) && !isset($data[2]) && !isset($data[3]))
                $this->{$method}(__LINE__, $data[0]);
            if (isset($data[0]) && isset($data[1]) && !isset($data[2]) && !isset($data[3]))
                $this->{$method}(__LINE__, $data[0], $data[1]);
            if (isset($data[0]) && isset($data[1]) && isset($data[2]) && !isset($data[3]))
                $this->{$method}(__LINE__, $data[0], $data[1], $data[2]);
            if (isset($data[0]) && isset($data[1]) && isset($data[2]) && isset($data[3])) {
                $this->{$method}(__LINE__, $data[0], $data[1], $data[2], $data[3]);
            }
        }
        if ($method == "insert") {
            if (!$data) throw new DatabaseExpection("incomplete parameter, valid parameter 2 expected");
            $this->{$method}(__LINE__, $data[0]);
        }
        if ($method == "update") {
            if (!$data) throw new DatabaseExpection("incomplete parameter, valid parameter 2 expected");
            $this->{$method}(__LINE__, $data[0], $data[1]);
        }
        return ['query' => $this->_theQuery, 'data' => $this->_theQueryData];
    }

    /**
     * Put data in a column inside an array
     *
     * @param int $line the line where the method is called
     * @param string $columnName the column whose data is to put in an array
     * @return array
     */
    public function getDataInColumn(int $line, string $columnName): array
    {
        $columnData = [];
        if ($data = $this->select($line, [$columnName])) {
            foreach ($data as $aDatum) $columnData[] = $aDatum[$columnName];
        }
        return $columnData;
    }

    /**
     * Check if a datum is in a column
     *
     * @param int $line the line where the method is called
     * @param mix $datum the record been checked for
     * @param string $columnName the column been checked
     * @return boolean
     */
    public function isDataInColumn(int $line, $datum, string $columnName): bool
    {
        $found = false;
        $where = [['column' => $columnName, 'comparsion' => '=', 'bindAbleValue' => "$datum"]];
        if ($this->select($line, [], $where)) $found = true;
        return $found;
    }

    /**
     * check if the data supplied for where clause is ok or throw an expection
     *  
     * @param int $line the line method was called
     * @param array $whereCols the where clause data
     * @return void
     */
    private function whereParameter(int $line, array $whereCols): void
    {
        $dbExpectionMsg = "illegal parameter";
        if (!is_array($whereCols)) {
            $errorMsg = $dbExpectionMsg . ", 3rd parameter must a 2X2 array";
            $this->sendE($errorMsg, $this->_file, $line);
        }
        $kanter = 0;
        foreach ($whereCols as $aWhereCol) {
            ++$kanter;
            if (!isset($aWhereCol['column'])) {
                $errorMsg = $dbExpectionMsg . ", column key is required in 3rd parameter";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            if (!isset($aWhereCol['comparsion'])) {
                $errorMsg = $dbExpectionMsg . ", comparsion key is required in 3rd parameter";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            if (!isset($aWhereCol['value']) && !isset($aWhereCol['bindAbleValue'])) {
                $errorMsg = $dbExpectionMsg . ", value or bindAbleValue key is required in 3rd parameter";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            if (!is_array($aWhereCol)) {
                $errorMsg = $dbExpectionMsg . ", an element in 3rd parameter is invalid";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            if (!(array_keys($aWhereCol) !== range(0, count($aWhereCol) - 1))) {
                $errorMsg = $dbExpectionMsg . ", an element in 3rd parameter is not an associative array";
                $this->sendE($errorMsg, $this->_file, $line);
            }
            if ($kanter != count($whereCols) && !isset($aWhereCol['logic'])) {
                $errorMsg = $dbExpectionMsg . ", a non last element in 3rd parameter must have logic key";
                $this->sendE($errorMsg, $this->_file, $line);
            }
        }
    }

    /**
     * generates where clause in query
     *
     * @param array $criteria where clause [[column, comparsion, value/bindAbleValue, logic], ...]
     * @return array
     */
    private function generateWhereClause(array $criteria): array
    {
        $kanter = 0;
        $where = " WHERE ";
        $bindValue = [];
        foreach ($criteria as $aCriteria) {
            ++$kanter;
            $comparsionOperator = ($aCriteria['comparsion']) ? $aCriteria['comparsion'] : "";
            if (isset($aCriteria['value']) || isset($aCriteria['bindAbleValue'])) {
                if (isset($aCriteria['bindAbleValue'])) {
                    $bindParam = ":b_{$aCriteria['column']}$kanter";
                    $bindValue["b_{$aCriteria['column']}$kanter"] = $aCriteria['bindAbleValue'];
                }
                if (isset($aCriteria['value'])) {
                    $aCriteria['value'] = $this->_PDO->quote($aCriteria['value']);
                    $bindParam = $aCriteria['value'];
                }
            } else {
                $bindParam = "";
            }
            $logicOperator = ($kanter == count($criteria)) ? "" : $aCriteria['logic'];
            $where .= " {$aCriteria['column']} $comparsionOperator $bindParam $logicOperator ";
        }

        return ['where' => $where, 'bindValue' => $bindValue];
    }

    /**
     * For sending error
     *
     * @param string $msg the error message
     * @param string $file file where error originated from
     * @param integer $line line no where the error originated
     * @param string $exception either Exception or DatabaseExpection
     * @return void
     */
    private function sendE(string $msg, string $file, int $line, string $exception = "DatabaseExpection")
    {
        new ErrorLog($msg, $file, $line);
        if (DEVELOPMENT && $exception == "DatabaseExpection") throw new DatabaseExpection($msg);
        if (DEVELOPMENT && $exception == "Exception") throw new Exception($msg);
    }
}
