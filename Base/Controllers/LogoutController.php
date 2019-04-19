<?php
ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
if ($session->hasLogin()) {
    $session->logout();
    
}
echo $session->getSessionStateJSON();
ob_end_flush();
?>

