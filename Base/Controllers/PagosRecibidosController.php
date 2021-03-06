<?php

include_once 'Libraries/Controllers.php';
$bc = null;
$result = null;
$session = new SessionManager();
$model = 'PagosRecibidosApp';
$findBy = 'id_pago';
$action = 'insertorupdate';
$postdata = null;
$ahora = getdate();
if ($session->hasLogin() && $session->checkToken() && ($session->getManagement() == 1 || $session->getAdmin() == 1 || $session->getSuperAdmin() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();

        if (!isset($postdata['fecha_pago']) || $postdata['fecha_pago'] == null || $postdata['fecha_pago'] == '') {
            $postdata['fecha_pago'] = $ahora['year'] . '-' . $ahora['mon'] . '-' . $ahora['mday'] . ' ' . $ahora['hours'] . ':' . $ahora['minutes'] . ':' . $ahora['seconds'];
        }
        $postdata['usuariorecibe_pago'] = $session->getNickname();
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
?>
