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
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['id_escuela'] = $session->getEnterpriseID();
        $postdata['fecha_matricula'] = $ahora['year'] . '-' . $ahora['mon'] . '-' . $ahora['mday'] . ' ' . $ahora['hours'] . ':' . $ahora['minutes'] . ':' . $ahora['seconds'];
        $bc->setPostData($postdata);
        if (isset($_POST['action']) && $_POST['action'] === 'find') {
            $bc->setAction('find');
        }
        $result = null;
        $result = $bc->execute(true);
        $result = null;
        if (isset($_POST['action']) && $_POST['action'] === 'insertorupdate' && $bc->getRowCount() > 0) {
            $array = Array();
            $array['id_programa'] = $postdata['id_programa'];
            $array['id_periodo'] = $postdata['id_periodo'];
            $array['id_planestudio'] = $postdata['id_planestudio'];
            $array['numgrado_programa'] = $postdata['numgrado_programa'];
            $array['id_grupo'] = $postdata['id_grupo'];
            $array['id_matricula'] = $postdata['id_matricula'];

            $sql = "UPDATE MatriculaAsignaturasApp "
                    . " SET id_programa=:id_programa,"
                    . " id_periodo=:id_periodo,"
                    . " id_planestudio=:id_planestudio,"
                    . " numgrado_programa=:numgrado_programa, "
                    . " id_grupo=:id_grupo "
                    . " WHERE id_matricula=:id_matricula ";
            $bc->query($sql, $array);
            $sql = "UPDATE CalificacionesApp "
                    . " SET id_programa=:id_programa,"
                    . " id_periodo=:id_periodo,"
                    . " id_planestudio=:id_planestudio,"
                    . " numgrado_programa=:numgrado_programa, "
                    . " id_grupo=:id_grupo "
                    . " WHERE id_matricula=:id_matricula ";
            $bc->query($sql, $array);
        }

        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>