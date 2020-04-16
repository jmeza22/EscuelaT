<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    $tipo = null;
    if (isset($arraywhere['tipo_persona'])) {
        $tipo = $arraywhere['tipo_persona'];
    }
    echo $bc->getPersonas($tipo);
    $bc->disconnect();
    $bc = null;
}
?>