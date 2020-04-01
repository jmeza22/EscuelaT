<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personas
 *
 * @author JOSE MEZA
 */
class Personas extends ReportsBank {

    private $crypt = null;
    private $postdata = null;
    private $model = null;
    private $findby = null;
    private $statusfield = null;
    private $idtipousuario = null;
    private $idpersona = null;

    public function __construct($urlsettingsdb = null) {
        parent::__construct($urlsettingsdb);
        $this->crypt = new MyCrypt();
        $this->connect();
    }

    public function setArray($postdata) {
        if ($postdata !== null && is_array($postdata)) {
            $this->postdata = $postdata;
            if (isset($this->postdata['id_tipousuario']) && $this->postdata['id_tipousuario'] !== null) {
                $this->idtipousuario = $this->postdata['id_tipousuario'];
                unset($this->postdata['id_tipousuario']);
            }
            $this->idpersona = $this->postdata['id_persona'];
            $this->setData();
        }
    }

    public function setData() {
        $this->model = 'PersonasApp';
        $this->findby = 'id_persona';
        $this->statusfield = 'status_persona';
        $this->setModel($this->model);
        $this->setFindBy($this->findby);
        $this->setPostData($this->postdata);
        $this->preparePostData();
        $this->postdata = $this->getPostData();
    }

    public function insertPersona() {
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
                $sql = "UPDATE PersonasApp SET id_persona = CONCAT('P',num_persona) WHERE id_persona = '" . $this->postdata[$this->findby] . "' ";
                $this->executeSQL($sql);
                if ($this->getLastInsertId() !== '0') {
                    $this->idpersona = 'P' . $this->getLastInsertId();
                } else {
                    $this->idpersona = $this->postdata['id_persona'];
                }
            }
            return $result;
        }
        return null;
    }

    public function updatePersona() {
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
    
    public function deletePersona() {
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

    public function insertUsuario() {
        $result = null;
        if ($this->postdata !== null && ($this->session->getManagement() == 1 || $this->session->getAdmin() == 1 || $this->session->getSuperAdmin() == 1)) {
            $datos = array();
            $datos['id_escuela'] = $this->session->getEnterpriseID();
            $datos['id_persona'] = $this->idpersona;
            $datos['nombrecompleto_persona'] = $this->postdata['apellido1_persona'] . ' ' . $this->postdata['apellido2_persona'] . ' ' . $this->postdata['nombre1_persona'] . ' ' . $this->postdata['nombre2_persona'];
            $datos['username_usuario'] = strtoupper(str_replace(" ", "", $this->postdata['nombre1_persona'])) . $this->idpersona;
            $datos['password_usuario'] = $this->crypt->crypt($datos['username_usuario']);
            $datos['id_tipousuario'] = $this->idtipousuario;
            $datos['status_usuario'] = '1';
            $this->setModel('UsuariosApp');
            $this->setAction('insert');
            $this->setPostData($datos);
            $this->setFindBy('username_usuario');
            $result = $this->execute(false);
            $this->setData();
        }
        return $result;
    }

    public function insertEstudiante() {
        $result = null;
        if ($this->postdata !== null && $this->idpersona !== null) {
            $datos = array();
            $datos['id_estudiante'] = $this->idpersona;
            $datos['nombrecompleto_estudiante'] = $this->postdata['apellido1_persona'] . ' ' . $this->postdata['apellido2_persona'] . ' ' . $this->postdata['nombre1_persona'] . ' ' . $this->postdata['nombre2_persona'];
            $datos['status_estudiante'] = '1';
            $this->setModel('ObservadorEstudianteApp');
            $this->setAction('insertorupdate');
            $this->setPostData($datos);
            $this->setFindBy('id_estudiante');
            $result = $this->execute(false);
            $this->setData();
        }
        return $result;
    }

    public function insertDocente() {
        $result = null;
        if ($this->postdata !== null && $this->idpersona !== null) {
            $datos = array();
            $datos['id_docente'] = $this->idpersona;
            $datos['nombrecompleto_docente'] = $this->postdata['apellido1_persona'] . ' ' . $this->postdata['apellido2_persona'] . ' ' . $this->postdata['nombre1_persona'] . ' ' . $this->postdata['nombre2_persona'];
            $datos['status_docente'] = '1';
            $this->setModel('DocentesApp');
            $this->setAction('insertorupdate');
            $this->setPostData($datos);
            $this->setFindBy('id_docente');
            $result = $this->execute(false);
            $this->setData();
        }
        return $result;
    }

    public function findPersona() {
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
