<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
if ($session->hasLogin()) {
    $session->logout();
    
}
echo $session->getSessionStateJSON();

?>

