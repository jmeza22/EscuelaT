<?php


include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'MatriculaAsignaturasApp';
$findBy = 'id_matasig';
$action = 'insertorupdate';
$postdata = null;
$ahora = getdate();
$idmatricula = null;
$datosmatricula = null;
$idplanestudio = null;
$planestudio = null;
$grado = null;
$count = 0;
$rowcount = 0;
$i = 0;
//print_r($_POST);
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        if ($_POST['id_matricula'] !== null && $_POST['id_matricula'] !== 0 && $_POST['id_matricula'] !== '' && isset($_POST['action']) && strcmp($_POST['action'], 'insertorupdate') == 0) {
            $idmatricula = $_POST['id_matricula'];
            $datosmatricula = $bc->selectWithoutModel('MatriculasApp', '*', "id_matricula='$idmatricula'");
            if ($datosmatricula !== null && $datosmatricula !== '' && $datosmatricula !== '[]') {
                $datosmatricula = json_decode($datosmatricula, true);
                if ($datosmatricula !== null) {
                    $datosmatricula = $datosmatricula[0];
                    $idplanestudio = $datosmatricula['id_planestudio'];
                    $grado = $datosmatricula['numgrado_programa'];
                    $planestudio = $bc->selectWithoutModel('PlanEstudioDetalleApp', '*', "id_planestudio='$idplanestudio' and numgrado_programa='$grado' ");
                    $planestudio = json_decode($planestudio, true);
                }
            }
            if ($planestudio !== null && is_array($planestudio) && is_array($planestudio[0])) {
                $count = count($planestudio);
            } else {
                if (is_array($planestudio)) {
                    $count = 1;
                }
            }
            if ($count >= 1) {
                $bc->beginTransaction();
                for ($i = 0; $i < $count; $i++) {
                    for ($j = 0; $j < count($planestudio[$i]); $j++) {
                        unset($planestudio[$i][$j]);
                    }
                    $planestudio[$i]['id_matricula'] = $idmatricula;
                    $planestudio[$i]['id_estudiante'] = $datosmatricula['id_estudiante'];
                    $planestudio[$i]['id_grupo'] = $datosmatricula['id_grupo'];
                    $planestudio[$i]['id_periodo'] = $datosmatricula['id_periodo'];
                    $planestudio[$i]['numgrado_programa'] = $datosmatricula['numgrado_programa'];
                    $planestudio[$i]['id_matasig'] = $planestudio[$i]['id_matricula'] . $planestudio[$i]['id_asignatura'];
                    unset($planestudio[$i]['hteoricas_asignatura']);
                    unset($planestudio[$i]['hpracticas_asignatura']);
                    unset($planestudio[$i]['status_planestudiodetalle']);
                }
                $i = 0;
                for ($i = 0; $i < $count; $i++) {
                    $bc->setPostData($planestudio[$i]);
                    $result = $bc->execute(false);
                    if ($bc->getRowCount() > 0) {
                        $rowcount++;
                    } else {
                        break;
                    }
                }
                if ($rowcount == $count) {
                    $bc->commit();
                } else {
                    $bc->rollback();
                }
            }
        }
        if (isset($_POST['id_matasig']) && isset($_POST['action']) && strcmp($_POST['action'], 'delete') == 0) {
            $bc->setFindBy($findBy);
            $bc->setAction('delete');
            $result = $bc->execute(false);
        }
    }
    echo $result;
    $result = null;
    $bc->disconnect();
} else {
    echo $session->getSessionStateJSON();
}

?>