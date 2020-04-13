<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MatriculaAsignaturas
 *
 * @author LENOVO
 */
class MatriculaAsignaturas extends ReportsBank {

    private $postdata = null;
    private $model = null;
    private $findby = null;
    private $statusfield = null;
    private $idmatricula = null;
    private $idmatasig = null;
    private $numgrado = null;
    private $idplanestudio = null;

    public function __construct($urlsettingsdb = null) {
        parent::__construct($urlsettingsdb);
        $this->connect();
    }

    public function setArray($postdata) {
        if ($postdata !== null && is_array($postdata)) {
            $this->postdata = $postdata;
            $this->idmatricula = $this->postdata['id_matricula'];
            $this->idmatasig = $this->postdata['id_matasig'];
            $this->setData();
        }
    }

    public function setData() {
        $this->model = 'MatriculaAsignaturasApp';
        $this->findby = 'id_matasig';
        $this->statusfield = 'status_matasig';
        $this->setModel($this->model);
        $this->setFindBy($this->findby);
        $this->setPostData($this->postdata);
        $this->preparePostData();
        $this->postdata = $this->getPostData();
    }

    public function insertMatriculaAsignaturas() {
        if (isset($this->postdata[$this->findby])) {
            $this->setData();
            if ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1) {
                $this->setAction('insertorupdate');
            } else if ($this->session->getStandard()) {
                $this->setAction('insert');
            } else {
                $this->setAction('');
            }
            $result = $this->execute(false);
            if ($this->getRowCount() > 0 && $this->postdata[$this->findby] !== '') {
                
            }
            return $result;
        }
        return null;
    }

    public function updateMatriculaAsignaturas() {
        if (isset($this->postdata[$this->findby])) {
            $this->setData();
            if ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1) {
                $this->setAction('update');
            } else {
                $this->setAction('');
            }
            $result = $this->execute(false);
            return $result;
        }
        return null;
    }

    public function deleteMatriculaAsignaturas() {
        if (isset($this->postdata[$this->findby])) {
            $this->setData();
            if ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1) {
                $this->setAction('delete');
            } else {
                $this->setAction('');
            }
            $result = $this->execute(false);
            return $result;
        }
        return null;
    }

    public function findMatriculaAsignaturas() {
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

    public function insertAuto() {
        $result = null;
        if ($this->postdata['id_matricula'] !== 0 && $this->postdata['id_matricula'] !== '' && $this->getAction() === 'insertorupdate') {
            $this->setData();
            $rowcount = 0;
            $this->idmatricula = $this->postdata['id_matricula'];
            $arraywhere = array();
            $arraywhere['id_matricula'] = $this->idmatricula;
            $sql = "SELECT * FROM MatriculasApp WHERE id_matricula=:id_matricula ";
            $datosmatricula = $this->selectJSONArray($sql, $arraywhere);
            if ($datosmatricula !== null && $datosmatricula !== '' && $datosmatricula !== '[]') {
                $datosmatricula = json_decode($datosmatricula, true);
                if (is_array($datosmatricula)) {
                    $datosmatricula = $datosmatricula[0];
                }
            }
            $planestudio = null;
            if (is_array($datosmatricula)) {
                $this->idplanestudio = $datosmatricula['id_planestudio'];
                $this->numgrado = $datosmatricula['numgrado_programa'];
                $arraywhere = array();
                $arraywhere['id_planestudio'] = $this->idplanestudio;
                $arraywhere['numgrado'] = $this->numgrado;
                $sql = "SELECT * FROM PlanEstudioDetalleApp WHERE id_planestudio=:id_planestudio AND numgrado_programa=:numgrado ";
                $planestudio = $this->selectJSONArray($sql, $arraywhere);
                $planestudio = json_decode($planestudio, true);
            }
            $count = 0;
            if ($planestudio !== null && is_array($planestudio) && is_array($planestudio[0])) {
                $count = count($planestudio);
            }
            if ($count >= 1) {
                $this->beginTransaction();
                for ($i = 0; $i < $count; $i++) {
                    for ($j = 0; $j < count($planestudio[$i]); $j++) {
                        unset($planestudio[$i][$j]);
                    }
                    $planestudio[$i]['id_matricula'] = $this->idmatricula;
                    $planestudio[$i]['id_matasig'] = $planestudio[$i]['id_matricula'] . $planestudio[$i]['id_asignatura'];
                    $planestudio[$i]['id_estudiante'] = $datosmatricula['id_estudiante'];
                    $planestudio[$i]['id_periodo'] = $datosmatricula['id_periodo'];
                    unset($planestudio[$i]['id_escuela']);
                    unset($planestudio[$i]['id_programa']);
                    unset($planestudio[$i]['id_planestudio']);
                    unset($planestudio[$i]['numgrado_programa']);
                    unset($planestudio[$i]['hteoricas_asignatura']);
                    unset($planestudio[$i]['hpracticas_asignatura']);
                    unset($planestudio[$i]['status_planestudiodetalle']);
                }
                $i = 0;
                for ($i = 0; $i < $count; $i++) {
                    $this->setPostData($planestudio[$i]);
                    $result = $this->execute(false);
                    if ($this->getRowCount() > 0) {
                        $rowcount++;
                    } else {
                        echo $this->getErrorMessage();
                        break;
                    }
                }
                if ($rowcount == $count) {
                    $this->commit();
                } else {
                    $this->rollback();
                }
            }
        }
        $array = array();
        if ($result != NULL) {
            $array = ["data" => NULL, "message" => 'Se ha ejecutado Matricula Automatica de Asignaturas.', "status" => 1, "error" => $this->getErrorMessage(), "lastInsertId" => $this->getLastInsertId(), "rowcount" => $rowcount];
        } else {
            $array = ["data" => NULL, "message" => 'Hubo error en Matricula Automatica de Asignaturas.', "status" => 0, "error" => $this->getErrorMessage(), "lastInsertId" => 0, "rowcount" => 0];
        }
        $array = json_encode($array);
        return $array;
    }

}
