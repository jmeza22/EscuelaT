<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    $idcuestionario = null;
    if (isset($arraywhere['id_cuestionario'])) {
        $idcuestionario = $arraywhere['id_cuestionario'];
    }
    $idpersona = null;
    if (isset($arraywhere['id_persona'])) {
        $idpersona = $arraywhere['id_persona'];
    } else {
        if ($session->getUserType() !== 'SuperAdmin') {
            $idpersona = $session->getUserID();
        }
    }
    $idintento = null;
    if (isset($arraywhere['id_intento'])) {
        $idintento = $arraywhere['id_intento'];
    }
    echo $bc->getIntentosCuestionarios($idcuestionario, $idpersona, $idintento);
    $bc->disconnect();
    $bc = null;
}
?>