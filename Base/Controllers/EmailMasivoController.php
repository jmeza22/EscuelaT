<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$email = null;
$result = false;
$array = null;
if ($session->hasLogin()) {
    $email = new SendEmail();
    if (isset($_POST['server_email']) && isset($_POST['username_email']) && isset($_POST['password_email']) && isset($_POST['asunto_email']) && isset($_POST['mensaje_email'])) {
        if ($_POST['server_email'] !== '' && $_POST['username_email'] !== '' && $_POST['password_email'] !== '' && $_POST['asunto_email'] !== '' && $_POST['mensaje_email'] !== '') {
            $email->setUSERNAME($_POST['username_email']);
            $email->setPASSWORD($_POST['password_email']);
            $email->setFROM($_POST['username_email']);
            $email->setSUBJECT($_POST['asunto_email']);
            $to = Array();
            if ($_POST['destino_email'] !== '') {
                $to[] = $_POST['destino_email'];
            }
            if (isset($_POST['email_persona']) && is_array($_POST['email_persona'])) {
                $emailsAddress = $_POST['email_persona'];
                for ($i = 0; $i < count($emailsAddress); $i++) {
                    if ($_POST['email_persona'][$i] !== '') {
                        $to[] = $_POST['email_persona'][$i];
                    }
                }
            }
            $email->setTO($to);
            $email->setMESSAGE($_POST['mensaje_email']);
            $email->prepareSMPT($_POST['server_email']);
            $result = $email->sendMailSMTP();
        }
    }
    if ($result === true) {
        $message = 'Los Emails se han enviado con Exito!';
        $array = ["data" => $email->getAllAddress(), "message" => $message, "status" => 1, "error" => null, "lastInsertId" => 0, "rowcount" => $email->getEmailCount()];
    } else {
        $message = 'Hubo un Error durante el envio de los Emails!';
        $array = ["data" => NULL, "message" => $message, "status" => 0, "error" => $email->getErrorInfo(), "lastInsertId" => 0, "rowcount" => 0];
    }
    $array = json_encode($array);
    echo $array;
} 
if ($array === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
