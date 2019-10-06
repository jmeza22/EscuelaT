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
$idpersona = null;
$crypt = new MyCrypt();
$sql = null;
//pw admin 92042202128fb12b1241f0dce1e6fe8c56c9fb6911c527e063aabcd
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
    $params=Array();
    $params['p_username_usuario']=$user;
    $params['p_password_usuario']=$password;
    $params['p_id_tipousuario']=$idtipousuario;
    $params['p_id_escuela']=$enterprise;
    
    $sql = "SELECT IFNULL(Us.id_persona,'') as userid, IFNULL(Us.username_usuario,'') as user, IFNULL(Us.id_tipousuario,'') as userrole, IFNULL(Es.nombre_escuela,'') as enterprisename, CONCAT(IFNULL(Pe.nombre1_persona,''),' ',IFNULL(Pe.apellido1_persona,'')) as fullname "
            . " FROM UsuariosApp Us LEFT JOIN PersonasApp Pe ON Us.id_persona=Pe.id_persona LEFT JOIN EscuelasApp Es ON Us.id_escuela=Es.id_escuela  "
            . " WHERE Us.username_usuario=:p_username_usuario and Us.password_usuario=:p_password_usuario and Us.id_tipousuario=:p_id_tipousuario and Us.id_escuela=:p_id_escuela "
            . " and Us.status_usuario=1 and Es.status_escuela=1";
    $result = $bc->selectJSONArray($sql, $params);
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
            $session->setLogin($login['userid'], $login['user'], $login['userrole'], null, $enterprise);
            $session->setEnterpriseNameForm($login['enterprisename']);
            $array['token'] = $session->getToken();
            $array['data'] = json_encode($login);
            $idpersona = $login['userid'];
        } else {
            $array['status'] = 0;
            $array['message'] = 'You have a Active Session!.';
        }
    }

    $sql = "SELECT * FROM TiposUsuariosApp WHERE id_tipousuario=:p_id_tipousuario";
    $paramsTU=Array();
    $paramsTU['p_id_tipousuario']=$idtipousuario;
    $result = $bc->selectJSONArray($sql,$paramsTU);
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
