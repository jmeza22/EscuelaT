<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
echo 'Estos son los datos de la Sesion en el Sistema:  ';
if ($session->hasLogin()) {
    if ($session->getSuperAdmin() == 1) {
        echo $session->getSessionStateJSON();
    } else {
        echo 'Usted No tiene Autorizacion.';
    }
}else{
    echo 'No hay Sesion Iniciada.';
    print_r($_SESSION);
}
?>