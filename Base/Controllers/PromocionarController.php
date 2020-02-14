<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$bc = null;
$result = null;
$model = "AsistenciaApp";
$findBy = "id_asistencia";
$rowcount = 0;
$data = null;
$postdata = null;
$count = 0;
$i = 0;
$fachai = null;
$fechaf = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        
    }
} else {
    echo $session->getSessionStateJSON();
}
?>