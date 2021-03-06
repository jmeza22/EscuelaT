<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BasicController
 *
 * @author JOSEMEZA
 */
include_once 'SQLDatabase.php';

class BasicController {

    public $config = null;
    public $db = null;
    private $token = "0";
    private $model = "model";
    private $action = "select";
    private $findBy = "id";
    private $where = "";
    private $postData = null;
    private $state = 0;
    private $stateField = 'state';
    private $message = '';
    private $data = null;

    public function __construct($urlsettings = null, $code = 1) {
        $this->config = new DataSettings($urlsettings);
        $index = null;
        $index = $this->config->getSettingIndex($code);
        if ($index != null) {
            $this->db = new SQLDatabase($this->config->getHostDB($index), $this->config->getNameDB($index), $this->config->getUserDB($index), $this->config->getPasswordDB($index), 'mysql');
        } else {
            $this->db = new SQLDatabase('localhost', 'myapp', 'root', '', 'mysql');
        }
        $this->connect();
    }

    public function connect() {
        $this->db->connect();
    }

    public function disconnect() {
        $this->db->disconnect();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollback() {
        return $this->db->rollback();
    }

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function getLastInsertId($name = null) {
        return $this->db->getLastInsertID($name);
    }

    public function getRowCount() {
        return $this->db->getRowCount();
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function setAction($action) {
        $this->action = $action;
    }

    public function setFindBy($findBy) {
        $this->findBy = $findBy;
    }

    public function setWhere($where) {
        $this->where = $where;
    }

    public function setStateFieldName($stateField) {
        $this->stateField = $stateField;
    }

    public function setStateValue($state) {
        $this->state = $state;
    }

    public function getToken() {
        return $this->token;
    }

    public function getModel() {
        return $this->model;
    }

    public function getAction() {
        return $this->action;
    }

    public function getFindBy() {
        return $this->findBy;
    }

    public function getWhere() {
        return $this->where;
    }

    public function getStateFieldName() {
        return $this->stateField;
    }

    public function getStateValue() {
        return $this->state;
    }

    public function getErrorMessage() {
        return $this->db->getErrorMessage();
    }

    private function parseWhereParam($param) {
        if ($param != null && $param != '' && is_numeric($param) == false) {
            return "'" . $param . "'";
        } else if (is_numeric($param)) {
            return $param;
        }
        return null;
    }

    public function setPostData($postData = null) {
        if ($postData != null && is_array($postData)) {
            $this->postData = $postData;
            return true;
        } else {
            if (isset($_POST)) {
                $this->postData = $_POST;
                return true;
            }
        }
        return false;
    }

    public function getPostData() {
        return $this->postData;
    }

    public function preparePostData() {
        if ($this->postData == null || !isset($this->postData)) {
            $this->setPostData();
        }
        if (isset($this->postData) && is_array($this->postData)) {
            if (isset($this->postData['url'])) {
                unset($this->postData['url']);
            }
            if (isset($this->postData['token'])) {
                $this->token = $this->postData['token'];
                unset($this->postData['token']);
            }
            if (isset($this->postData['model'])) {
                $this->model = $this->postData['model'];
                unset($this->postData['model']);
            }
            if (isset($this->postData['action'])) {
                $this->action = $this->postData['action'];
                unset($this->postData['action']);
            }
            if (isset($this->postData['findBy'])) {
                $this->findBy = $this->postData['findBy'];
                unset($this->postData['findBy']);
            }
            if (isset($this->postData['where'])) {
                $this->where = $this->postData['where'];
                unset($this->postData['where']);
            }
            return true;
        }
        return false;
    }

    public function parseMultiRows($postdata) {
        if (isset($postdata) && is_array($postdata)) {
            $columns = array_keys($postdata);
            $numrows = count($postdata[$columns[0]]);
            $newarray = array();
            for ($i = 0; $i < $numrows; $i++) {
                $newarray[$i] = array();
                foreach ($columns as $col) {
                    if (isset($postdata[$col][$i]) && is_array($postdata[$col])) {
                        $newarray[$i][$col] = $postdata[$col][$i];
                    }
                }
            }
            return $newarray;
        }
        return null;
    }

    public function parseFindByToArray($postdata) {
        if (isset($postdata) && is_array($postdata)) {
            $arrayfindby = array();
            if (isset($postdata['findby']) && isset($postdata['findbyvalue'])) {
                if ($postdata['findby'] !== null && $postdata['findby'] !== '' && $postdata['findbyvalue'] !== null && $postdata['findbyvalue'] !== '') {
                    $arrayfindby[$postdata['findby']] = $postdata['findbyvalue'];
                }
            }
            for ($i = 0; $i < count($postdata); $i++) {
                if (isset($postdata['findby' . $i]) && isset($postdata['findbyvalue' . $i])) {
                    if ($postdata['findby' . $i] !== null && $postdata['findby' . $i] !== '' && $postdata['findbyvalue' . $i] !== null && $postdata['findbyvalue' . $i] !== '') {
                        $arrayfindby[$postdata['findby' . $i]] = $postdata['findbyvalue' . $i];
                    }
                }
            }
            return $arrayfindby;
        }
        return null;
    }

    public function executeSQL($sql) {
        if (isset($sql) && $sql !== null) {
            return $this->db->exec($sql);
        }
        return null;
    }

    public function insert() {
        if (isset($this->db) && isset($this->postData)) {
            if ($this->db->insertStmt($this->model, $this->postData)) {
                return true;
            }
        }
        return false;
    }

    public function insertOrUpdate() {
        if (isset($this->db) && isset($this->postData)) {
            if ($this->db->insertOrUpdateStmt($this->model, $this->postData)) {
                return true;
            }
        }
        return false;
    }

    public function replace() {
        if (isset($this->db) && isset($this->postData)) {
            if ($this->db->replaceStmt($this->model, $this->postData)) {
                return true;
            }
        }
        return false;
    }

    public function update($arraydata = null) {
        $data = $this->postData;
        if ($arraydata !== null && is_array($arraydata)) {
            $data = $arraydata;
        }
        if (isset($this->db) && isset($this->postData) && isset($this->findBy)) {
            if ($this->db->updateStmt($this->model, $data, "" . $this->findBy . "=" . $this->parseWhereParam($this->postData[$this->findBy]))) {
                return true;
            }
        }
        return false;
    }

    public function updateState() {
        $data = array();
        $data[$this->stateField] = $this->state;
        if (isset($this->db) && isset($this->postData) && isset($this->findBy)) {
            if ($this->db->updateStmt($this->model, $data, "" . $this->findBy . "=" . $this->parseWhereParam($this->postData[$this->findBy]))) {
                return true;
            }
        }
        return false;
    }

    public function delete() {
        if (isset($this->db) && isset($this->postData) && isset($this->findBy)) {
            if ($this->db->deleteStmt($this->model, "" . $this->findBy . "=" . $this->parseWhereParam($this->postData[$this->findBy]))) {
                return true;
            }
        }
        return false;
    }

    public function find($columns = '*') {
        $result = null;
        if (isset($this->db) && isset($this->postData) && isset($this->findBy)) {
            $result = $this->db->selectJSON($this->model, $columns, "" . $this->findBy . "=" . $this->parseWhereParam($this->postData[$this->findBy]), false);
            return $result;
        }
        return false;
    }

    public function select($table = null, $columns = '*', $where = null, $arraywhere = null, $groupby = null, $orderby = null) {
        $result = null;
        if ($table == null) {
            $table = $this->model;
        }
        if ($where == null) {
            $where = $this->where;
        }
        if (isset($this->db) && isset($this->model)) {
            $result = $this->db->selectStmt($table, $columns, $where, $arraywhere, $groupby, $orderby);
            $result = json_encode($result);
            return $result;
        }
        return null;
    }

    public function selectWithoutModel($table = null, $columns = '*', $where = null) {
        $array = $this->select($table, $columns, $where);
        $array = json_decode($array, true);
        if ($array != null && isset($array[$table])) {
            $array = $array[$table];
        }
        $array = json_encode($array);
        return $array;
    }

    public function selectJSONArray($sql, $arraywhere = null) {
        if ($sql != null) {
            return $this->db->selectJSONArray($sql, $arraywhere);
        }
        return null;
    }

    public function selectAssocArray($sql, $arraywhere = null) {
        if ($sql != null) {
            return $this->db->selectAssocArray($sql, $arraywhere);
        }
        return null;
    }

    public function query($sql, $arraywhere = null) {
        if ($sql != null) {
            return $this->db->queryStmt($sql, $arraywhere);
        }
        return null;
    }
    
    public function printArrayToHTML($array) {
        return $this->db->printArrayToHTML($array);
    }

    public function parseResults($result = NULL, $message = '', $status = 0) {
        $array = array();
        if ($result != NULL) {
            $array = ["data" => $result, "message" => $message, "status" => $status, "error" => null, "lastInsertId" => $this->getLastInsertId(), "rowcount" => $this->getRowCount()];
        } else {
            $array = ["data" => NULL, "message" => $message, "status" => $status, "error" => $this->db->getErrorMessage(), "lastInsertId" => $this->getLastInsertId(), "rowcount" => $this->getRowCount()];
        }
        $array = json_encode($array);
        return $array;
    }

    public function execute($print = false) {
        $result = false;
        if (isset($this->action)) {

            if ($this->action == null || strcmp($this->action, '') == 0) {
                $result = $this->parseResults(null, "Operacion No Permitida!", 0);
            }
            if (strcmp($this->action, 'find') == 0) {
                $result = $this->find();
                if ($result != null && !is_bool($result)) {
                    $result = $this->parseResults($result, "", 1);
                } else {
                    $result = $this->parseResults($result, "", 0);
                }
            }
            if (strcmp($this->action, 'insert') == 0) {
                $result = $this->insert();
                if ($result == true) {
                    $result = $this->parseResults($result, "Registro Exitoso!", 1);
                } else {
                    $result = $this->parseResults($result, "Registro Fallido!", 0);
                }
            }
            if (strcmp($this->action, 'insertorupdate') == 0) {
                $result = $this->insertOrUpdate();
                if ($result == true) {
                    $result = $this->parseResults($result, "Operacion Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Operacion Fallida!", 0);
                }
            }
            if (strcmp($this->action, 'replace') == 0) {
                $result = $this->replace();
                if ($result == true) {
                    $result = $this->parseResults($result, "Operacion Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Operacion Fallida!", 0);
                }
            }
            if (strcmp($this->action, 'update') == 0) {
                $result = $this->update();
                if ($result == true) {
                    $result = $this->parseResults($result, "Actualizacion Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Actualizacion Fallida!", 0);
                }
            }
            if (strcmp($this->action, 'delete') == 0) {
                $result = $this->delete();
                if ($result == true) {
                    $result = $this->parseResults($result, "Eliminacion Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Eliminacion Fallida!", 0);
                }
            }
            if (strcmp($this->action, 'findall') == 0) {
                $result = $this->select();
                if (is_array($result)) {
                    $result = $this->parseResults($result, "Consulta Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Consulta Fallida!", 0);
                }
            }
            if (strcmp($this->action, 'findavalue') == 0) {
                $result = $this->getValue();
                if (is_array($result)) {
                    $result = $this->parseResults($result, "Consulta Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Consulta Fallida!", 0);
                }
            }
            if (strcmp($this->action, 'updatestate') == 0) {
                $result = $this->updateState();
                if ($result == true) {
                    $result = $this->parseResults($result, "Operacion Exitosa!", 1);
                } else {
                    $result = $this->parseResults($result, "Operacion Fallida!", 0);
                }
            }
        }
        if ($print == true) {
            if ($result != NULL) {
                echo $result;
            }
        }
        return $result;
    }

    public function getComboboxData($colname = 'colname', $colvalue = 'colvalue', $othervalue = 'othervalue', $where = null, $arraywhere = null, $groupby = null, $orderby = null) {
        $result = null;
        if (isset($this->db) && isset($this->model) && isset($colname) && isset($colvalue)) {
            $columns = array();
            array_push($columns, $colname . ' AS iname');
            array_push($columns, $colvalue . ' AS ivalue');
            array_push($columns, $othervalue . ' AS iothervalue');
            $result = $this->db->selectStmt($this->model, $columns, $where, $arraywhere, $groupby, $orderby);
            $result = json_encode($result);
            return $result;
        }
        return null;
    }

    public function getValue($column = '*', $idname = null, $idvalue = null) {
        $result = null;
        $where = null;
        if ($idname === null) {
            $idname = $this->findBy;
        }
        if ($idvalue === null) {
            if (isset($this->postData[$idname]) && $this->postData[$idname] !== null) {
                $idvalue = $this->postData[$idname];
            }
        }
        if (isset($this->db) && isset($this->model) && isset($idname) && isset($idvalue)) {
            $where = '' . $idname . '=' . $this->parseWhereParam($idvalue);
            $result = $this->db->selectJSON($this->model, $column, $where);
            return $result;
        }
        return null;
    }

}

?>