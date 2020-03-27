<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = "TiposUsuariosApp";
$findBy = "id_tipousuario";
$rowcount = 0;
$data = null;
$postdata = null;
$count = 0;
$i = 0;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction('insertorupdate');
        if (isset($_POST[$findBy])) {
            $data = array();
            $postdata = $bc->getPostData();
            $count = count($postdata[$findBy]);
            if (is_array($postdata[$findBy]) && $count > 0) {
                $postdata = $bc->parseMultiRows($postdata);
                $count = count($postdata);
                for ($i = 0; $i < $count; $i++) {
                    $bc->setPostData($postdata[$i]);
                    $temp = $bc->execute(false);
                    if ($bc->getRowCount() > 0) {
                        $result = $temp;
                        $rowcount++;
                    }
                }
            }
        }
        if ($rowcount > 0) {
            echo $result;
        }
        $bc->executeSQL("DELETE FROM $model WHERE status_tipousuario=0 ");
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>