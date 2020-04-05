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
$fachai = null;
$fechaf = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getStandard() == 1 || $session->getUserType() === 'Teacher')) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction('insertorupdate');
        $sql = "SELECT * FROM CortesPeriodosApp WHERE status_corte=1 AND id_corte=:p_id_corte";
        $arraywhere = array();
        $arraywhere['p_id_corte'] = $variables->getIdCortePeriodo();
        $datoscorte = $bc->selectJSONArray($sql, $arraywhere);
        if (isset($datoscorte) && $datoscorte != '[]') {
            $datoscorte = json_decode($datoscorte, true);
            if (is_array($datoscorte) && is_array($datoscorte[0])) {
                $fechai = $datoscorte[0]['finicio_corte'];
                $fechaf = $datoscorte[0]['ffinal_corte'];
                $fachai = strtotime($fechai);
                $fachaf = strtotime($fechaf);
            }
        }
        if (isset($_POST[$findBy])) {

            $data = array();
            $postdata = $bc->getPostData();
            $count = count($_POST[$findBy]);

            if ($count >= 1) {
                $postdata = $bc->parseMultiRows($postdata);
                $count = count($postdata);
                for ($i = 0; $i < $count; $i++) {
                    $fechaA = strtotime($postdata[$i]['fecha_asistencia']);
                    if ($postdata[$i]['id_matasig'] !== '{{id_matasig}}' && ($fechaA >= $fachai && $fechaA <= $fachaf)) {
                        $postdata[$i]['id_corte'] = $variables->getIdCortePeriodo();
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
        }
        echo $result;
        $bc->executeSQL("DELETE FROM $model WHERE status_asistencia=0 ");
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>