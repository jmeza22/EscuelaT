<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$model = 'TerminalesEleccionesApp';
$findBy = 'id_terminal';
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
if (isset($_POST) && $_POST != null && isset($_POST['token']) && $_POST['token'] != null) {
    if (!$session->hasLogin()) {
        if (strcmp($_POST['token'], '92a04b22c02d12e8') != 0) {
            exit();
        }

        if ($_POST['id_escuela'] != null) {
            $enterprise = $_POST['id_escuela'];
        }
        $result = null;
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $user = $_POST['mynickname'];
        $password = $_POST['mypassword'];
        $params = Array();
        $params['p_codigo_terminal'] = $user;
        $params['p_password_terminal'] = $password;
        $params['p_id_escuela'] = $enterprise;

        $sql = "SELECT T.id_terminal AS userid, T.codigo_terminal AS user, T.nombre_terminal AS fullname, Es.nombre_escuela AS enterprisename, 'Student' AS userrole "
                . " FROM TerminalesEleccionesApp T INNER JOIN EscuelasApp Es ON T.id_escuela=Es.id_escuela "
                . " WHERE T.codigo_terminal=:p_codigo_terminal "
                . " AND T.password_terminal=:p_password_terminal "
                . " AND T.id_escuela=:p_id_escuela "
                . " AND T.status_terminal=1 ";
        $result = $bc->selectJSONArray($sql, $params);
        $array = array();
        $array['message'] = '';
        $array['error'] = null;
        $array['status'] = 0;
        $array['data'] = null;
        if ($result == null || strcmp($result, '') == 0 || strcmp($result, '[]') == 0) {
            $array['message'] = 'Codigo / Contraseña Errados!.';
            $array['status'] = 0;
        } else {
            $array['status'] = 1;
            $login = json_decode($result, true);
            $login = $login[0];
            if (isset($login['userid'])) {
                $session->setLogin($login['userid'], $login['user'], 'Student', $login['fullname'], $enterprise);
                $session->setEnterpriseNameForm($login['enterprisename']);
                $array['token'] = $session->getToken();
                $array['data'] = json_encode($login);
                $array['session_id'] = session_id();
            }
        }


        $session->setUserPermissions(0, 0, 0, 0, 1, 0);

        $login = null;
        $array = json_encode($array);
        echo $array;

        $result = null;
        $array = null;
        $bc->disconnect();
    } else {
        echo $session->getSessionStateJSON();
    }
    exit();
}
?>
