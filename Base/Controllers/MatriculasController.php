<?php


include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'MatriculasApp';
$findBy = 'id_matricula';
$action = 'insertorupdate';
$postdata = null;
$ahora = getdate();
$sql = null;
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1 || $session->getManagement() == 1 || $session->getStandard() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BaseController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        $postdata['fecha_matricula'] = $ahora['year'] . '-' . $ahora['mon'] . '-' . $ahora['mday'] . ' ' . $ahora['hours'] . ':' . $ahora['minutes'] . ':' . $ahora['seconds'];
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] !== null && strcmp($_POST['action'], 'find') === 0) {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        $result = null;
        if (isset($_POST['action']) && $_POST['action'] !== null && strcmp($_POST['action'], 'insertorupdate') === 0 && $bc->getRowCount() > 0) {
            $sql = "UPDATE MatriculaAsignaturasApp SET id_programa='" . $postdata['id_programa'] . "' WHERE id_matricula='" . $postdata['id_matricula'] . "' ";
            $bc->executeSQL($sql);
            $sql = "UPDATE MatriculaAsignaturasApp SET id_planestudio='" . $postdata['id_planestudio'] . "' WHERE id_matricula='" . $postdata['id_matricula'] . "' ";
            $bc->executeSQL($sql);
            $sql = "UPDATE MatriculaAsignaturasApp SET numgrado_programa='" . $postdata['numgrado_programa'] . "' WHERE id_matricula='" . $postdata['id_matricula'] . "' ";
            $bc->executeSQL($sql);
            $sql = "UPDATE MatriculaAsignaturasApp SET id_grupo='" . $postdata['id_grupo'] . "' WHERE id_matricula='" . $postdata['id_matricula'] . "' ";
            $bc->executeSQL($sql);
        }

        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}

?>