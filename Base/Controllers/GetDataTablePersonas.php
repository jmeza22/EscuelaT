<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    $idpersona = null;
    if (isset($arraywhere['id_persona'])) {
        $idpersona = $arraywhere['id_persona'];
    }
    $nombre1 = null;
    if (isset($arraywhere['nombre1_persona'])) {
        $nombre1 = $arraywhere['nombre1_persona'];
    }
    $apellido1 = null;
    if (isset($arraywhere['apellido1_persona'])) {
        $apellido1 = $arraywhere['apellido1_persona'];
    }
    $sexo = null;
    if (isset($arraywhere['sexo_persona'])) {
        $sexo = $arraywhere['sexo_persona'];
    }
    $tipo = null;
    if (isset($arraywhere['tipo_persona'])) {
        $tipo = $arraywhere['tipo_persona'];
    }
    echo $bc->getPersonas($tipo, $nombre1, $apellido1, $sexo, $idpersona);
    $bc->disconnect();
    $bc = null;
}
?>