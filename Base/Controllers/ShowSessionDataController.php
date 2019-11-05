<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
if ($session->hasLogin()) {
    if ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1) {
        echo $session->getSessionStateJSON();
    } else {
        echo 'Usted No tiene Autorizacion.';
    }
}else{
    echo $session->getSessionStateJSON();
}
?>