<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = "MatriculasApp";
$findBy = "id_matricula";
$rowcount = 0;
$data = null;
$postdata = null;
$count = 0;
$i = 0;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction('update');
        $bc->beginTransaction();
        if (isset($_POST[$findBy])) {
            $data = array();
            $postdata = $bc->getPostData();
            $count = count($postdata[$findBy]);
            if ($count >= 1) {
                $findbyvalues = $postdata[$findBy];
                for ($i = 0; $i < $count; $i++) {
                    $subdata = array();
                    $subdata[$findBy] = $findbyvalues[$i];
                    $subdata['id_sede'] = $postdata['id_sede_destino'];
                    $subdata['id_grupo'] = $postdata['id_grupo_destino'];
                    $bc->setPostData($subdata);
                    $result = $bc->execute(false);
                    if ($bc->getRowCount() > 0) {
                        $rowcount++;
                    } else {
                        break;
                    }
                }
            }
        }
        if ($rowcount == $count) {
            $bc->commit();
        } else {
            $bc->rollback();
        }
        if ($result !== null) {
            $result = json_decode($result, true);
            $result['rowCount'] = $rowcount;
            $result = json_encode($result);
        }else{
            $result=array();
            $result['data']=null;
            $result['rowcount']=0;
            $result['message']='No ha seleccionado ningun Estudiante';
            $result = json_encode($result);
        }
        echo $result;
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>