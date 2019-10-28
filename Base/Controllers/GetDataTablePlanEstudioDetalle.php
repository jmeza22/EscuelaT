<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$bc = null;
$idplanestudio = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    if (isset($_POST['findby']) && $_POST['findby'] == 'id_planestudio' && isset($_POST['findbyvalue']) && $_POST['findbyvalue'] !== '') {
        $idplanestudio = $_POST['findbyvalue'];
    }
    echo $bc->getPlanEstudioDetalle($session->getEnterpriseID(), null, $idplanestudio);
    $bc->disconnect();
    $bc = null;
}

?>