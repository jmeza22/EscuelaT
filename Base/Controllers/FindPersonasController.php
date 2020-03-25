<?php


include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'PersonasApp';
$findBy = 'id_persona';
$action = 'find';
if ($session->hasLogin()) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $result = null;
        $result = $bc->execute(true);
        $bc->disconnect();
    }
} 
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>