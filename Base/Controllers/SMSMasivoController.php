<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$sms = null;
$result = false;
$array = null;
if ($session->hasLogin() && $session->checkToken()) {
    $sms = new SendSMS();
    if (isset($_POST['server_sms']) && isset($_POST['username_sms']) && isset($_POST['password_sms']) && isset($_POST['mensaje_sms'])) {
        if ($_POST['server_sms'] !== '' && $_POST['username_sms'] !== '' && $_POST['password_sms'] !== '' && $_POST['mensaje_sms'] !== '') {
            $sms->setUSERNAME($_POST['username_sms']);
            $sms->setPASSWORD($_POST['password_sms']);
            $sms->setFROM($session->getEnterpriseName());
            $to = Array();
            if ($_POST['destino_sms'] !== '') {
                $to[] = $_POST['indicativo_sms'] . $_POST['destino_sms'];
            }
            if (isset($_POST['telefono_persona']) && is_array($_POST['telefono_persona']) && isset($_POST['indicativo_sms']) && is_numeric($_POST['indicativo_sms'])) {
                $telefonos1 = $_POST['telefonoacudiente1_estudiante'];
                $telefonos2 = $_POST['telefono_persona'];
                for ($i = 0; $i < count($_POST['nombrecompleto_estudiante']); $i++) {
                    if ($telefonos1[$i] !== '' && is_numeric($telefonos1[$i]) && strlen($telefonos1[$i]) >= 7) {
                        $to[] = $_POST['indicativo_sms'] . $telefonos1[$i];
                    }
                    if ($telefonos2[$i] !== '' && is_numeric($telefonos2[$i]) && strlen($telefonos2[$i]) >= 7) {
                        $to[] = $_POST['indicativo_sms'] . $telefonos2[$i];
                    }
                }
            }
            $sms->setTO($to);
            $sms->setMESSAGE($_POST['mensaje_sms']);
            if (isset($_POST['server_sms']) && $_POST['server_sms'] === 'WauSMS') {
                $result = $sms->SendWauSMS($_POST['mensaje_sms']);
            }
        }
    }
    if ($result !== null) {
        $result = json_decode($result, true);
        if (is_array($result)) {
            if ($result != NULL) {
                $array = ["data" => json_encode($result), "message" => 'Observe los Resultados para Verificar que se hayan enviado los SMS.', "status" => 1, "error" => null, "lastInsertId" => 0, "rowcount" => count($sms->getTO())];
            } else {
                $array = ["data" => null, "message" => 'Hubo un error en el envio de Mensajes de Texto.', "status" => 0, "error" => null, "lastInsertId" => 0, "rowcount" => 0];
            }
        }
    }
    $array = json_encode($array);
    echo $array;
}
if ($array === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
