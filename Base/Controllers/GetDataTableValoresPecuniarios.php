<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    echo $bc->getValoresPecuniarios($session->getEnterpriseID());
    $bc->disconnect();
    $bc = null;
}

?>