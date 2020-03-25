<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idpregunta = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (isset($arraywhere['id_pregunta'])) {
        $idpregunta = $arraywhere['id_pregunta'];
    }
    echo $bc->getOpcionesRespuesta($idpregunta, null);
    $bc->disconnect();
    $bc = null;
}
?>