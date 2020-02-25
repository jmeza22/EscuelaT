<?php


include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'PagosRecibidosApp';
$findBy = 'id_pago';
$action = 'find';
$postdata = null;
$ahora = getdate();
$sql = null;
if ($session->hasLogin()) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $result = null;
        $modelPOST = null;
        if (isset($_POST['model']) && $_POST['model'] !== null) {
            $modelPOST = $_POST['model'];
        }
        if ($modelPOST === 'PagosRecibidosApp') {
            $result = $bc->execute(false);
        }
        if ($modelPOST === 'MatriculasApp') {
            if (isset($_POST['id_matricula'])) {
                $idmatricula = $_POST['id_matricula'];
                $bc->setModel($modelPOST);
                $bc->setFindBy('id_matricula');
                $result = $bc->execute(false);
                $result = json_decode($result, true);
                $sql = "SELECT IFNULL(SUM(valor_pago),0) AS valorpagado_matricula "
                        . " FROM PagosRecibidosApp WHERE status_pago=1 "
                        . " AND tipodoc_pago LIKE '%Matricula%' "
                        . " AND numdoc_pago='$idmatricula' ";
                $result['data'] = $bc->selectJSONArray($sql);
                $result = json_encode($result);
            }
        }
        echo $result;
        $result = null;
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>