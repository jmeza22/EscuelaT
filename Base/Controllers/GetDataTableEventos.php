<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;

if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    
    $idprograma = null;
    if (isset($arraywhere['id_programa'])) {
        $idprograma = $arraywhere['id_programa'];
    }
    
    $visible = null;
    if (isset($arraywhere['visible_evento'])) {
        $visible = $arraywhere['visible_evento'];
    }
    
    $idevento = null;
    if (isset($arraywhere['id_evento'])) {
        $idevento = $arraywhere['id_evento'];
    }
    echo $bc->getEventos($session->getEnterpriseID(), $idprograma, $visible, $idevento);
    $bc->disconnect();
    $bc = null;
}
?>