<?php

ob_start();
include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'UsuariosApp';
$findBy = 'username_usuario';
$action = 'find';
$where = '';
$columns = null;
$user = null;
$password = null;
$idtipousuario = null;
$enterprise = 1;
$login = null;
$tipousuario = null;
$crypt = new MyCrypt();
$sql = null;

if (isset($_POST) && $_POST != null && isset($_POST['token']) && $_POST['token'] != null) {

    if (strcmp($_POST['token'], '92a04b22c02d12e8') != 0) {
        exit();
    }

    if ($_POST['id_escuela'] != null) {
        $enterprise = $_POST['id_escuela'];
    }
    $result = null;
    $bc = new BaseController();
    $bc->connect();
    $bc->preparePostData();
    $bc->setModel($model);
    $bc->setFindBy($findBy);
    $user = $_POST['mynickname'];
    $pw = $_POST['mypassword'];
    $password = $crypt->crypt($pw);
    $idtipousuario = $_POST['id_tipousuario'];
    $sql = "SELECT Us.id_persona as userid, Us.username_usuario as user, Us.id_tipousuario as userrole, concat(Pe.nombre1_persona,' ',Pe.apellido1_persona) as fullname, Es.nombre_escuela as enterprisename "
            . "FROM UsuariosApp Us INNER JOIN PersonasApp Pe ON Us.id_persona=Pe.id_persona INNER JOIN EscuelasApp Es ON Us.id_escuela=Es.id_escuela "
            . "WHERE Us.username_usuario='$user' and Us.password_usuario='$password' and Us.id_tipousuario='$idtipousuario' and Us.id_escuela=$enterprise and Us.status_usuario=1 and Pe.status_persona=1 and Es.status_escuela=1";
    //print_r($_POST);
    //print_r($sql);
    $result = $bc->selectSimple($sql);

    $array = array();
    $array['message'] = '';
    $array['error'] = null;
    $array['status'] = 0;
    $array['data'] = null;
    if ($result == null || strcmp($result, '') == 0 || strcmp($result, '[]') == 0) {
        $array['message'] = 'User / Password Wrong!.';
        $array['status'] = 0;
    } else {
        $array['status'] = 1;
        $login = json_decode($result, true);
        $login = $login[0];
        if (!$session->hasLogin()) {
            $session->setLogin($login['userid'], $login['user'], $login['userrole'], $login['fullname'], $enterprise);
            $session->setEnterpriseNameForm($login['enterprisename']);
            $array['token'] = $session->getToken();
            $array['data'] = json_encode($login);
        } else {
            $array['status'] = 0;
            $array['message'] = 'You have a Active Session!.';
        }
    }
    
    $sql = "SELECT * FROM TiposUsuariosApp WHERE id_tipousuario='" . $idtipousuario . "'";
    $result = $bc->selectSimple($sql);
    if ($session->hasLogin() && !isset($result) || $result !== null || strcmp($result, '') !== 0 || strcmp($result, '[]') !== 0) {
        $tipousuario = json_decode($result, true);
        $tipousuario = $tipousuario[0];
        if ($tipousuario !== null) {
            $session->setUserPermissions($tipousuario['superadmin_tipousuario'], $tipousuario['admin_tipousuario'], $tipousuario['management_tipousuario'], $tipousuario['standard_tipousuario'], $tipousuario['limited_tipousuario'], $tipousuario['external_tipousuario']);
        }
    }
    $login = null;
    $tipousuario = null;
    $array = json_encode($array);
    echo $array;

    $result = null;
    $array = null;
    $bc->disconnect();
}
ob_end_flush();
?>
