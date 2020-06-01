<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'IntentosCuestionariosApp';
$findBy = 'id_intento';
$action = 'insertorupdate';
$postdata = null;
if ($session->hasLogin() && $session->checkToken()) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'insert' && $postdata[$findBy] === '' || $postdata[$findBy] === '0') {
                $postdata['id_persona'] = $session->getUserID();
                $postdata['fechainicio_intento'] = date('Y-m-d');
                $postdata['horainicio_intento'] = date('H:i:s');
                $postdata['tiemposegundos_intento'] = 0;
                $postdata['finalizado_intento'] = 0;
                $sql1 = "SELECT numintentos_cuestionario FROM CuestionariosApp WHERE id_cuestionario=:p_id_cuestionario";
                $arraywhere1 = array();
                $arraywhere1['p_id_cuestionario'] = $postdata['id_cuestionario'];
                $numMaxIntentos = $bc->selectAssocArray($sql1, $arraywhere1);
                $numMaxIntentos= $numMaxIntentos[0]['numintentos_cuestionario'];
                $sql2 = "SELECT COUNT(*) AS cantIntentos FROM IntentosCuestionariosApp WHERE status_intento=1 AND id_cuestionario=:p_id_cuestionario AND id_persona=:p_id_persona";
                $arraywhere2 = array();
                $arraywhere2['p_id_persona'] = $postdata['id_persona'];
                $arraywhere2['p_id_cuestionario'] = $postdata['id_cuestionario'];
                $cantIntentos = $bc->selectAssocArray($sql2, $arraywhere2);
                $cantIntentos = $cantIntentos[0]['cantIntentos'];
                if($cantIntentos>=$numMaxIntentos){
                    $bc->setAction('none');
                }
            }
            if ($_POST['action'] === 'delete') {
                $bc->setAction('update');
            }
            if ($_POST['action'] === 'find') {
                $bc->setAction('find');
            }
        }
        $bc->setPostData($postdata);
        $result = null;
        $result = $bc->execute(true);
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>