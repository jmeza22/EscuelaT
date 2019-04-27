<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$variables = new SystemVariableManager();
$ok1 = false;
$ok2 = false;
$result = null;
if ($session->hasLogin() && isset($_POST) && $_POST !== null) {
    $result = $session->getSessionStateJSON();
    
    if (isset($_POST['action']) && $_POST['action'] == 'insertorupdate') {
        if (($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1)) {
            if (isset($_POST['enterpriseid']) && isset($_POST['enterprisename']) && $_POST['enterpriseid'] !== NULL && $_POST['enterpriseid'] !== '') {
                $session->setEnterpriseIdForm($_POST['enterpriseid']);
                $session->setEnterpriseNameForm($_POST['enterprisename']);
                $ok1 = true;
            }
        }
        if (($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getStandard() === 1)) {
            if (isset($_POST['id_periodo']) && isset($_POST['id_corte']) && isset($_POST['numero_corte']) && $_POST['id_periodo'] !== 'NULL' && $_POST['id_corte'] !== 'NULL' && $_POST['numero_corte'] !== 'NULL' && $_POST['id_periodo'] !== '' && $_POST['id_corte'] !== '' && $_POST['numero_corte'] !== '') {
                $variables->setIdPeriodoAnualForm($_POST['id_periodo']);
                $variables->setIdCortePeriodoForm($_POST['id_corte']);
                $variables->setNumCortePeriodoForm($_POST['numero_corte']);
                $variables->setEstadoCortePeriodoForm($_POST['estado_corte']);
                $ok2 = true;
            }
        }
    }

    $result = json_decode($result, true);
    if (isset($_POST['action']) && $_POST['action'] == 'find') {
        $data = array();
        $data['VariablesSesion'] = array();
        $data['VariablesSesion']['userid'] = $session->getUserID();
        $data['VariablesSesion']['usertype'] = $session->getUserType();
        $data['VariablesSesion']['nickname'] = $session->getNickname();
        $data['VariablesSesion']['fullname'] = $session->getFullname();
        $data['VariablesSesion']['enterpriseid'] = $session->getEnterpriseID();
        $data['VariablesSesion']['enterprisename'] = $session->getEnterpriseName();
        $data['VariablesSesion']['id_periodo'] = $variables->getIdPeriodoAnual();
        $data['VariablesSesion']['id_corte'] = $variables->getIdCortePeriodo();
        $data['VariablesSesion']['numero_corte'] = $variables->getNumCortePeriodo();
        $data['VariablesSesion']['estado_corte'] = $variables->getEstadoCortePeriodo();
        $data = json_encode($data);
        $result['data'] = $data;
        $ok1 = true;
    }

    if ($ok1 === true) {
        $result['message'] = 'Operacion Exitosa!.';
        $result['status'] = 1;
    } else if ($ok2 === true) {
        $result['message'] = 'Operacion Exitosa!.';
        $result['status'] = 1;
    } else {
        $result['message'] = 'Operacion Fallida!.';
        $result['status'] = 0;
    }

    $result = json_encode($result);

    echo $result;
} else {
    echo $session->getSessionStateJSON();
}
ob_end_flush();
?>
