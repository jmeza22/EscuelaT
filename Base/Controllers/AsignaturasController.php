<?php

ob_start();
include_once 'Libraries/Controllers.php';
$bc = null;
$result = null;
$model = "AsignaturasApp";
$findBy = "id_asignatura";
$action = "insertorupdate";
$rowcount = 0;
$data = null;
$postdata = null;
$count = 0;
$i = 0;
if (isset($_POST) && $_POST != null) {
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel($model);
    $bc->setFindBy($findBy);
    $bc->setAction($action);
    $bc->beginTransaction();
    if (isset($_POST[$findBy])) {

        $data = array();
        $postdata = $bc->getPostData();
        $count = count($_POST[$findBy]);

        if ($count >= 1) {
            $postdata = $bc->parseMultiRows($postdata);
            $count = count($postdata);
            for ($i = 0; $i < $count; $i++) {
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
    if ($rowcount == $count) {
        $bc->commit();
    } else {
        $bc->rollback();
    }
    echo $result;
    $result = null;
    $bc->disconnect();
}

ob_end_flush();
?>