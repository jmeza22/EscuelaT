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
if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction('insertorupdate');
        if (isset($_POST[$findBy])) {

            $data = array();
            $postdata = $bc->getPostData();
            $count = count($_POST[$findBy]);

            if ($count >= 1) {
                $postdata = $bc->parseMultiRows($postdata);
                $count = count($postdata);
                for ($i = 0; $i < $count; $i++) {
                    $postdata[$i]['id_corte']=$variables->getIdCortePeriodo();
                    $bc->setPostData($postdata[$i]);
                    $result = $bc->execute(false);
                    if ($bc->getRowCount() > 0) {
                        $rowcount++;
                    } else {
                        break;
                    }
                }
            }
        }
        echo $result;
        $result = null;
        $bc->executeSQL("DELETE FROM $model WHERE status_asistencia=0 ");
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>