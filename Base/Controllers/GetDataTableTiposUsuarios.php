<?php


include_once 'Libraries/Controllers.php';
include_once 'Libraries/Reports.php';
$session = new SessionManager();
$model = 'TiposUsuariosApp';
$bc = null;
if (isset($_POST) && $_POST !== null) {
    $bc = new ReportsBank();
    $bc->connect();
    echo $bc->getTiposUsuarios();
    $bc->disconnect();
    $bc = null;
}

?>