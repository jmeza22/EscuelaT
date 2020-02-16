<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$crypt = new MyCrypt();
$bc = null;
$result = null;
$model = 'PersonasApp';
$findBy = 'id_persona';
$action = 'insertorupdate';
$postdata = null;
$idpersona = null;
$idtipousuario = null;
$datos = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1 || $session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        if (isset($_POST['id_tipousuario']) && $_POST['id_tipousuario'] !== null) {
            $idtipousuario = $_POST['id_tipousuario'];
            unset($_POST['id_tipousuario']);
        }
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if (isset($_POST['action']) && $_POST['action'] !== null && strcmp($_POST['action'], 'find') === 0) {
            $bc->setAction('find');
        }
        $result = $bc->execute(true);
        $result = null;
        $postdata = $bc->getPostData();
        if ($bc->getRowCount() > 0) {
            $idpersona = $bc->getLastInsertId();
            $sql = "UPDATE $model SET id_persona=CONCAT('P',num_persona) WHERE id_persona='" . $bc->getPostData()[$findBy] . "' ";
            $bc->executeSQL($sql);
            $sql = "DELETE FROM $model WHERE status_persona=0 ";
            $bc->executeSQL($sql);
            if ($idpersona !== null && $idpersona !== 0 && $idpersona !== '') {
                if ($idtipousuario !== null) {
                    $datos = array();
                    $datos['id_escuela'] = $session->getEnterpriseID();
                    $datos['id_persona'] = 'P' . $idpersona;
                    $datos['nombrecompleto_persona'] = $postdata['apellido1_persona'] . ' ' . $postdata['apellido2_persona'] . ' ' . $postdata['nombre1_persona'] . ' ' . $postdata['nombre2_persona'];
                    $datos['username_usuario'] = strtoupper(str_replace(" ", "", $postdata['nombre1_persona'])) . $idpersona;
                    $datos['password_usuario'] = $crypt->crypt($postdata['documento_persona']);
                    $datos['id_tipousuario'] = $idtipousuario;
                    $datos['status_usuario'] = '1';
                    $bc->setModel('UsuariosApp');
                    $bc->setAction('insert');
                    $bc->setPostData($datos);
                    $bc->setFindBy('username_usuario');
                    $bc->execute(false);
                }
                if ($idtipousuario == 'Student') {
                    $datos = array();
                    $datos['id_estudiante'] = 'P' . $idpersona;
                    $datos['nombrecompleto_estudiante'] = $postdata['apellido1_persona'] . ' ' . $postdata['apellido2_persona'] . ' ' . $postdata['nombre1_persona'] . ' ' . $postdata['nombre2_persona'];
                    $datos['status_estudiante'] = '1';
                    $bc->setModel('ObservadorEstudianteApp');
                    $bc->setAction('insertorupdate');
                    $bc->setPostData($datos);
                    $bc->setFindBy('id_estudiante');
                    $bc->execute(false);
                }
                if ($idtipousuario == 'Teacher') {
                    $datos = array();
                    $datos['id_docente'] = 'P' . $idpersona;
                    $datos['nombrecompleto_docente'] = $postdata['apellido1_persona'] . ' ' . $postdata['apellido2_persona'] . ' ' . $postdata['nombre1_persona'] . ' ' . $postdata['nombre2_persona'];
                    $datos['status_docente'] = '1';
                    $bc->setModel('DocentesApp');
                    $bc->setAction($action);
                    $bc->setPostData($datos);
                    $bc->setFindBy('id_docente');
                    $bc->execute(false);
                }
            }
        }
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>
