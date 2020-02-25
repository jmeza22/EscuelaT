<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
if ($session->hasLogin() && $session->checkToken()) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $result = null;
        $result = $bc->execute(true);
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>

