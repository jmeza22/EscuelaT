<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Matriculas
 *
 * @author LENOVO
 */
class Matriculas extends ReportsBank {

    private $postdata = null;
    private $model = null;
    private $findby = null;
    private $statusfield = null;
    private $idmatricula = null;

    public function __construct($urlsettingsdb = null) {
        parent::__construct($urlsettingsdb);
        $this->connect();
    }

    public function setArray($postdata) {
        if ($postdata !== null && is_array($postdata)) {
            $this->postdata = $postdata;
            $this->idmatricula = $this->postdata['id_matricula'];
            $this->setData();
        }
    }

    public function setData() {
        $this->model = 'MatriculasApp';
        $this->findby = 'id_matricula';
        $this->statusfield = 'status_matricula';
        $this->setModel($this->model);
        $this->setFindBy($this->findby);
        $this->setPostData($this->postdata);
        $this->preparePostData();
        $this->postdata = $this->getPostData();
    }

    public function insertMatricula() {
        if (isset($this->postdata[$this->findby])) {
            $this->setData();
            if ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1) {
                $this->setAction('insertorupdate');
            } else if ($this->session->getStandard()) {
                $this->setAction('insert');
            } else {
                $this->setAction('');
            }
            if (!isset($this->postdata['fecha_matricula'])) {
                $this->postdata['id_escuela'] = $this->session->getEnterpriseID();
                $fecha = date('Y-m-d H:i:s');
                $this->postdata['fecha_matricula'] = $fecha;
            }
            $this->setPostData($this->postdata);
            $result = $this->execute(false);
            return $result;
        }
        return null;
    }

    public function updateOtrasTablas() {
        if ($this->postdata !== null && $this->postdata[$this->findby] !== '' && $this->getAction() === 'insertorupdate') {
            $array = Array();
            $array['id_planestudio'] = $this->postdata['id_planestudio'];
            $array['numgrado_programa'] = $this->postdata['numgrado_programa'];
            $array['id_grupo'] = $this->postdata['id_grupo'];
            $array['id_matricula'] = $this->postdata['id_matricula'];
            $sql = "UPDATE CalificacionesApp "
                    . " SET id_planestudio=:id_planestudio,"
                    . " numgrado_programa=:numgrado_programa, "
                    . " id_grupo=:id_grupo "
                    . " WHERE id_matricula=:id_matricula ";
            $this->query($sql, $array);
        }
    }

    public function updateMatricula() {
        if (isset($this->postdata[$this->findby])) {
            if ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1) {
                $this->setAction('update');
            } else {
                $this->setAction('');
            }
            $this->setData();
            $result = $this->execute(false);
            return $result;
        }
        return null;
    }

    public function deleteMatricula() {
        if (isset($this->postdata[$this->findby])) {
            $this->updateMatricula();
            if ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1) {
                $this->setAction('delete');
            } else {
                $this->setAction('');
            }
            $this->setData();
            $result = $this->execute(false);
            return $result;
        }
        return null;
    }

    public function findMatricula() {
        $result = null;
        $this->setData();
        if ($this->postdata !== null) {
            $this->setAction('find');
            $data = array();
            $data[$this->findby] = $this->postdata[$this->findby];
            $this->setPostData($data);
            $result = $this->execute(false);
            $this->setData();
        }
        return $result;
    }

}
