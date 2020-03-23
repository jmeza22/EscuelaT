<?php

include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idarea = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    $arraywhere = $bc->parseFindByToArray($_POST);
    if (isset($arraywhere['id_area'])) {
        $idarea = $arraywhere['id_area'];
    }
    echo $bc->getLecturas($session->getEnterpriseID(), $idarea, null);
    $bc->disconnect();
    $bc = null;
}
?>