<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLDatabase
 *
 * @author José José Meza Meza
 * 1083434734
 * 
 * Para que funcione en Oracle descargar instantclient-basic-win-x86-64-10.2.0.2.0-20060503.zip
 * Descomprimir dentro "oraclexe" con el nombre "instantclient"
 * Colocar la ruta de "instantclient" en la Variable de entorno PATH
 * Crear Variable de entorno ORACLE_HOME con la ruta "C:\Oracle_Express_Edition\app\oracle\product\10.2.0" o su equivalente
 * Descomentar "extension=php_pdo_oci.dll", "extension=php_oci8.dll", "extension=php_oci8_11g.dll" (Quitar punto y coma ";" al inicio)
 * 
 */
class SQLDatabase {

    private $hostdb = 'localhost';
    private $portdb = 3306;
    private $namedb = '';
    private $userdb = 'root';
    private $passdb = '';
    private $dbms = 'mysql';
    private $persistent = false;
    public $link = null;
    private $errorcode = 0;
    private $errormessage = '';
    private $stmt;

    public function __construct($host, $database, $user, $password, $port = NULL, $dbms = NULL, $persistent = FALSE) {

        $this->hostdb = $host;
        $this->namedb = $database;
        $this->userdb = $user;
        $this->passdb = $password;
        $this->persistent = $persistent;
        if ($port != null && isset($port)) {
            $this->portdb = $port;
        }
        if ($dbms != null && isset($dbms)) {
            $this->dbms = $dbms;
        }
    }

    private function printError($error, $sql) {
        return 'Error: ' . $error . ' IN [... ' . $sql . ' ...]';
    }

    public function getErrorCode() {
        return $this->errorcode;
    }

    public function getErrorMessage() {
        return $this->errormessage;
    }

    public function setHost($host) {
        $this->hostdb = $host;
    }

    public function setPort($port) {
        $this->portdb = $port;
    }

    public function setDatabase($database) {
        $this->namedb = $database;
    }

    public function setUser($user) {
        $this->userdb = $user;
    }

    public function setPassword($pass) {
        $this->passdb = $pass;
    }

    public function setDBMS($dbms) {
        $this->dbms = $dbms;
    }

    public function connect() {
        try {
            if ($this->dbms == null || strcmp($this->dbms, '') == 0 || strcmp($this->dbms, 'mysql') == 0) {
                $this->link = new PDO("{$this->dbms}:host={$this->hostdb};port={$this->portdb};dbname={$this->namedb}", $this->userdb, $this->passdb, array(PDO::ATTR_PERSISTENT => $this->persistent))
                        or die('MySql Database Connection Error!.');
                $this->link->query("SET NAMES 'utf8'");
            }
            if ($this->link == null) {
                echo 'Error de Conexion';
            }
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            $this->errorcode = $e->getCode();
            $this->errormessage = $e->getMessage();
            $this->printError($e->getCode(), "Open Conection.");
        }
        return false;
    }

    public function getLink() {
        return $this->link;
    }

    public function disconnect() {
        return $this->link = null;
    }

    public function commit() {
        return $this->link->commit();
    }

    public function rollback() {
        return $this->link->rollback();
    }

    public function beginTransaction() {
        return $this->link->beginTransaction();
    }

    private function executeSTMT() {
        $result = false;
        $this->errorcode = 0;
        $this->errormessage = '';
        if ($this->stmt != null) {
            try {
                $this->stmt->execute();
                $result = true;
            } catch (Exception $e) {
                $this->errorcode = $e->getCode();
                $this->errormessage = $e->getMessage();
            }
        }
        return $result;
    }

    public function exec($do) {
        $result = false;
        $this->errorcode = 0;
        $this->errormessage = '';
        if ($this->link != null) {
            try {
                $this->link->exec($do);
                $result = true;
            } catch (Exception $e) {
                $this->errorcode = $e->getCode();
                $this->errormessage = $e->getMessage();
                $this->printError($e->getMessage(), $do);
            }
        }
        return $result;
    }

    public function query($do) {
        $result = false;
        $this->errorcode = 0;
        $this->errormessage = '';
        if ($this->link != null) {
            try {
                $this->link->query($do);
                $result = true;
            } catch (Exception $e) {
                $this->errorcode = $e->getCode();
                $this->errormessage = $e->getMessage();
                $this->printError($e->getMessage(), $do);
                $result = null;
            }
        }
        return $result;
    }

    public function getResultSet($sql) {
        $result = null;
        $this->errorcode = 0;
        $this->errormessage = '';
        if ($this->link != null) {
            try {
                $stmt = $this->link->query($sql);
                $result = $stmt;
            } catch (Exception $e) {
                $this->errorcode = $e->getCode();
                $this->errormessage = $e->getMessage();
                $this->printError($e->getMessage(), $sql);
                $result = null;
            }
        }
        return $result;
    }

    public function getLastInsertID($name = null) {
        if ($this->link != null) {
            try {
                $lastid = $this->link->lastInsertId($name);
                return $lastid;
            } catch (Exception $ex) {
                
            }
        }
        return null;
    }

    public function getRowCount() {
        if ($this->link != null && $this->stmt != null) {
            $rowcount = null;
            try {
                $rowcount = $this->stmt->rowCount();
                return $rowcount;
            } catch (Exception $ex) {
                
            }
        }
        return null;
    }

    public function bindParams($stmt, $array) {
        if ($stmt != null && is_array($array)) {
            $array_pdo_types = $this->getPDOColumnTypes($array);
            $array_columns = $this->getColumns($array);
            $array_values = $this->getValues($array);
            if ($this->getCount($array_columns) == $this->getCount($array_values)) {
                $i = 0;
                for ($i = 0; $i < count($array); $i++) {
                    $stmt->bindParam(":" . $array_columns[$i], $array_values[$i], $array_pdo_types[$i]);
                }
            }
        }
        return $stmt;
    }

    public function getValues($array, $arrayval = NULL) {
        $array_result = array();
        if (isset($arrayval) && is_array($arrayval)) {
            $array_result = $arrayval;
        }
        if (isset($array) && ($array)) {
            foreach ($array as $valor) {
                if (is_array($valor)) {
                    $array_result = $this->getValues($valor, $array_result);
                } else {
                    array_push($array_result, $valor);
                }
            }
            return $array_result;
        }
        return null;
    }

    public function getColumns($array) {
        $array_result = array();
        if (isset($array) && ($array)) {
            $array_result = array_keys($array);
            return $array_result;
        }
        return null;
    }

    public function getVarTypes($array) {
        $arrayval = $this->getValues($array);
        $arraytype = array();
        if (is_array($array)) {
            foreach ($arrayval as $valor) {
                array_push($arraytype, gettype($valor));
            }
            return $arraytype;
        }
        return null;
    }

    public function getSQLColumnTypes($array) {
        $arraytype = $this->getVarTypes($array);
        $arrayresult = array();
        if ($arraytype != null && is_array($arraytype)) {
            foreach ($arraytype as $valor) {
                if (strcmp($valor, "integer") == 0 || strcmp($valor, "boolean") == 0) {
                    array_push($arrayresult, "i");
                } else
                if (strcmp($valor, "double") == 0) {
                    array_push($arrayresult, "d");
                } else {
                    array_push($arrayresult, "s");
                }
            }
            return $arrayresult;
        }
        return null;
    }

    public function getPDOColumnTypes($array) {
        $arraytype = $this->getVarTypes($array);
        $arrayresult = array();
        if ($arraytype != null && is_array($arraytype)) {
            foreach ($arraytype as $valor) {
                if (strcmp($valor, "integer") == 0) {
                    array_push($arrayresult, PDO::PARAM_INT);
                } else if (strcmp($valor, "boolean") == 0) {
                    array_push($arrayresult, PDO::PARAM_BOOL);
                } else if (strcmp($valor, "double") == 0) {
                    array_push($arrayresult, PDO::PARAM_STR);
                } else {
                    array_push($arrayresult, PDO::PARAM_STR);
                }
            }
            return $arrayresult;
        }
        return null;
    }

    public function getCount($array) {
        if (is_array($array)) {
            return count($array);
        } else {
            if (isset($array)) {
                return 1;
            }
        }
        return 0;
    }

    public function makeValuesReferenced(&$array) {
        $refs = array();
        foreach ($array as $key => $value) {
            $refs[$key] = &$array[$key];
        }
        return $refs;
    }

    private function buildInsertString($table, $values) {
        if ($table != null && $values != null) {
            $sql = 'INSERT INTO ' . $table . ' values(' . $values . ')';
            return $sql;
        }
        return '';
    }

    private function buildReplaceString($table, $values) {
        if ($table != null && $values != null) {
            $sql = 'REPLACE INTO ' . $table . ' values(' . $values . ')';
            return $sql;
        }
        return '';
    }

    private function buildUpdateString($table, $setvalues, $where = NULL) {
        if ($table != null && $setvalues != null) {
            $sql = 'UPDATE ' . $table . ' SET ' . $setvalues;
            if ($where != null && strcmp($where, '') !== 0) {
                $sql = $sql . ' WHERE ' . $where;
            }
            return $sql;
        }
        return '';
    }

    private function buildDeleteString($table, $where = NULL) {
        if ($table != null) {
            $sql = 'DELETE FROM ' . $table;
            if ($where != null && strcmp($where, '') !== 0) {
                $sql = $sql . ' WHERE ' . $where;
            }
            return $sql;
        }
        return '';
    }

    private function buildSelectString($table, $columns, $where = NULL) {
        if ($table != null && $columns != null) {
            $sql = 'SELECT ' . $columns . ' FROM ' . $table;
            if ($where != null && strcmp($where, '') !== 0) {
                $sql = $sql . ' WHERE ' . $where;
            }
            return $sql;
        }
        return null;
    }

    private function buildInsertStmtString($table, $array) {
        if (is_array($array)) {
            $sql = "INSERT INTO " . $table . " ";
            $sql = $sql . "(";
            $columns = $this->getColumns($array);
            foreach ($columns as $column) {
                $sql = $sql . " " . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            $sql = $sql . ")";
            $sql = $sql . " VALUES ";
            $sql = $sql . "(";
            foreach ($columns as $column) {
                $sql = $sql . " :" . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            $sql = $sql . ")";
            return $sql;
        }
        return null;
    }

    private function buildInsertOrUpdateStmtString($table, $array) {
        if (is_array($array)) {
            $sql = "INSERT INTO " . $table . " ";
            $sql = $sql . "(";
            $columns = $this->getColumns($array);
            foreach ($columns as $column) {
                $sql = $sql . " " . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            $sql = $sql . ")";
            $sql = $sql . " VALUES ";
            $sql = $sql . "(";
            foreach ($columns as $column) {
                $sql = $sql . " :" . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            $sql = $sql . ")";
            $sql = $sql . " ON DUPLICATE KEY UPDATE ";
            foreach ($columns as $column) {
                $sql = $sql . " " . $column . "= :" . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            return $sql;
        }
        return null;
    }

    private function buildReplaceStmtString($table, $array) {
        if (is_array($array)) {
            $sql = "REPLACE INTO " . $table . " ";
            $sql = $sql . "(";
            $columns = $this->getColumns($array);
            foreach ($columns as $column) {
                $sql = $sql . " " . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            $sql = $sql . ")";
            $sql = $sql . " VALUES ";
            $sql = $sql . "(";
            foreach ($columns as $column) {
                $sql = $sql . " :" . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            $sql = $sql . ")";
            return $sql;
        }
        return null;
    }

    private function buildUpdateStmtString($table, $arraycolumns, $where = NULL, $arraywhere = NULL) {
        if (is_array($arraycolumns)) {
            $sql = "UPDATE " . $table . " SET ";
            $columns = $this->getColumns($arraycolumns);
            foreach ($columns as $column) {
                $sql = $sql . " " . $column . "= :" . $column . ",";
            }
            $sql = substr($sql, 0, -1);
            if ($where != null && strcmp($where, '') !== 0) {
                $sql = $sql . ' WHERE ' . $where;
            }
            return $sql;
        }
        return null;
    }

    private function buildSelectStmtString($table, $arraycolumns, $where = NULL, $arraywhere = NULL) {
        if ($table != null && $arraycolumns != null) {
            $sql = 'SELECT';
            if (is_array($arraycolumns)) {
                $columns = $this->getValues($array);
                foreach ($columns as $column) {
                    $sql = $sql . " " . $column . ",";
                }
                $sql = substr($sql, 0, -1);
            } else {
                $sql = $sql . ' ' . $arraycolumns;
            }

            $sql = $sql . ' FROM ' . $table;

            if ($where != null && strcmp($where, '') !== 0) {
                $sql = $sql . ' WHERE ' . $where;
            }
            return $sql;
        }
        return null;
    }

    public function insertStmt($table, $array) {
        $result = false;
        $this->stmt = null;
        if ($this->link != null && $table != null && isset($table) && $array != null && isset($array)) {
            $sql = $this->buildInsertStmtString($table, $array);
            $this->stmt = $this->link->prepare($sql);
            $this->stmt = $this->bindParams($this->stmt, $array);
            $this->executeSTMT();
            if ($this->stmt->rowCount() > 0) {
                $result = true;
            }
        }
        return $result;
    }

    public function insertOrUpdateStmt($table, $array) {
        $result = false;
        $this->stmt = null;
        if ($this->link != null && $table != null && isset($table) && $array != null && isset($array)) {
            $sql = $this->buildInsertOrUpdateStmtString($table, $array);
            $this->stmt = $this->link->prepare($sql);
            $this->stmt = $this->bindParams($this->stmt, $array);
            $this->executeSTMT();
            if ($this->stmt->rowCount() > 0) {
                $result = true;
            }
        }
        return $result;
    }

    public function replaceStmt($table, $array) {
        $result = false;
        $this->stmt = null;
        if ($this->link != null && $table != null && isset($table) && $array != null && isset($array)) {
            $sql = $this->buildReplaceStmtString($table, $array);
            $this->stmt = $this->link->prepare($sql);
            $this->stmt = $this->bindParams($this->stmt, $array);
            $this->executeSTMT();
            if ($this->stmt->rowCount() > 0) {
                $result = true;
            }
        }
        return $result;
    }

    public function updateStmt($table, $array, $where = null, $arraywhere = null) {
        $result = false;
        $this->stmt = null;
        if ($this->link != null && $table != null && isset($table) && $array != null && isset($array)) {
            $sql = $this->buildUpdateStmtString($table, $array, $where, $arraywhere);
            $this->stmt = $this->link->prepare($sql);
            $this->stmt = $this->bindParams($this->stmt, $array);
            $this->stmt = $this->bindParams($this->stmt, $arraywhere);
            $this->executeSTMT();
            if ($this->stmt->rowCount() > 0) {
                $result = true;
            }
        }
        return $result;
    }

    public function deleteStmt($table, $where = null, $arraywhere = null) {
        $result = false;
        $this->stmt = null;
        if ($this->link != null && $table != null && isset($table)) {
            $sql = $this->buildDeleteString($table, $where);
            $this->stmt = $this->link->prepare($sql);
            $this->stmt = $this->bindParams($this->stmt, $arraywhere);
            $this->executeSTMT();
            if ($this->stmt->rowCount() > 0) {
                $result = true;
            }
        }
        return $result;
    }

    public function queryStmt($sql, $array1 = null, $array2 = null) {
        $result = null;
        $this->stmt = null;
        if ($this->link != null && $sql != null && isset($sql)) {
            $this->stmt = $this->link->prepare($sql);
            $this->stmt = $this->bindParams($this->stmt, $array1);
            $this->stmt = $this->bindParams($this->stmt, $array2);
            $this->executeSTMT();
            if ($this->stmt->rowCount() > 0) {
                $result = true;
            }
            if ($this->stmt != null && isset($this->stmt)) {
                $myarray = array();
                while ($row = $this->stmt->fetch()) {
                    array_push($myarray, $row);
                }
                $result = $myarray;
            }
        }
        return $result;
    }

    public function selectStmt($table, $array, $where = null, $arraywhere = null) {
        $result = false;
        $this->stmt = null;
        if ($this->link != null && $table != null && isset($table) && $array != null && isset($array)) {
            $sql = $this->buildSelectStmtString($table, $array, $where, $arraywhere);
            return $this->queryStmt($sql, $array, $arraywhere);
        }
        return $result;
    }

    public function selectAssocArray($sql) {
        $resultset = $this->getResultSet($sql);
        if ($resultset != null) {
            $myarray = array();
            while ($row = $resultset->fetch()) {
                array_push($myarray, $row);
            }
            return $myarray;
        }
        return null;
    }

    public function selectArray($sql) {
        $resultset = $this->getResultSet($sql);
        if ($resultset != null) {
            $myarray = array();
            for ($i = 0; $i < $resultset->rowCount(); $i++) {
                $row = $resultset->fetchAll();
                for ($j = 0; $j < $resultset->columnCount(); $j++) {
                    $myarray[$i][$j] = $row[$j];
                }
            }
            return $myarray;
        }
        return null;
    }

    public function selectJSONArray($sql, $table = null) {
        $resultset = $this->getResultSet($sql);
        if ($resultset != null) {
            $json = array();
            while ($row = $resultset->fetch()) {
                if ($table != null) {
                    $json[$table][] = $row;
                } else {
                    $json[] = $row;
                }
            }
            $json = json_encode($json);
            return $json;
        }
        return null;
    }

    public function select($table, $columns, $where = '') {
        $sql = $this->buildSelectString($table, $columns, $where);
        $myarray = $this->selectArray($sql);
        return $myarray;
    }

    public function selectAssoc($table, $columns, $where = '') {
        $sql = $this->buildSelectString($table, $columns, $where);
        $myarray = $this->selectAssocArray($sql);
        return $myarray;
    }

    public function selectJSON($table, $columns, $where = '', $model = true) {
        $sql = $this->buildSelectString($table, $columns, $where);
        if ($model == true) {
            $myarray = $this->selectJSONArray($sql, $table);
        } else {
            $myarray = $this->selectJSONArray($sql, null);
        }
        return $myarray;
    }

    public function SelectAllJSON($table) {
        return $this->selectJSON($table, '*', null);
    }

    public function insert($table, $values) {
        $sql = $this->buildInsertString($table, $values);
        $result = false;
        if ($this->exec($sql) > 0) {
            $result = true;
        }
        return $result;
    }

    public function replace($table, $values) {
        $sql = $this->buildReplaceString($table, $values);
        $result = false;
        if ($this->exec($sql) > 0) {
            $result = true;
        }
        return $result;
    }

    public function update($table, $setvalues, $where) {
        $sql = $this->buildUpdateString($table, $setvalues, $where);
        $result = false;
        if ($this->exec($sql) > 0) {
            $result = true;
        }
        return $result;
    }

    public function delete($table, $where) {
        $sql = $this->buildDeleteString($table, $where);
        $result = false;
        if ($this->exec($sql) > 0) {
            $result = true;
        }
        return $result;
    }

    public function printArray($myarray) {
        echo '<table border=0>';
        for ($i = 0; $i < count($myarray); $i++) {
            echo '<tr>';
            for ($j = 0; $j < count($myarray[0]); $j++) {
                echo '<td>' . $myarray[$i][$j] . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    public function printSelect($table, $columns, $where) {
        $myarray = $this->select($table, $columns, $where);
        $this->printArray($myarray);
    }

    public function getTableInfoJSON($table) {
        $result = $this->selectJSONArray("desc {$table}", $table);
        if ($result != null && isset($result)) {
            return $result;
        }
        return null;
    }

    public function getTableInfoArray($table) {
        return json_decode($this->getTableInfoJSON($table), true);
    }

}
