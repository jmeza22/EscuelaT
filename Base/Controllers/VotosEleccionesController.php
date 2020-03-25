<?php

ob_start();
include_once 'Libraries/Controllers.php';
$bc = null;
$result = null;
$session = new SessionManager();
$model = 'VotosEleccionesApp';
$findBy = 'id_voto';
$action = 'insertorupdate';
$postdata = null;
$date = date("Y-m-d");
$time = date("G:i:s");
$lastdate = date("Y-m-d G:i:s");
if ($session->hasLogin() && ( $session->getLimited() == 1)) {
    if (isset($_POST[$findBy]) && $_POST[$findBy] != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->preparePostData();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $postdata = $bc->getPostData();
        $postdata['fechahora_voto'] = '' . $lastdate;
        $postdata['id_terminal'] = $session->getUserID();
        $bc->setPostData($postdata);
        $arraywhere = array();
        $arraywhere['p_id_eleccion'] = $postdata['id_eleccion'];
        $arraywhere['p_fecha'] = $date;
        $arraywhere['p_hora'] = $time;
        $sql = "SELECT * FROM EleccionesEstudiantilesApp "
                . " WHERE status_eleccion=1 "
                . " AND id_eleccion=:p_id_eleccion "
                . " AND fechainicio_eleccion=:p_fecha "
                . " AND :p_hora BETWEEN horainicio_eleccion AND horafin_eleccion ";
        $eleccion = $bc->selectJSONArray($sql, $arraywhere);
        $result = null;
        $eleccion = json_decode($eleccion, true);
        if (isset($eleccion[0])) {
            $result = $bc->execute(true);
        } else {
            $result = $session->getSessionStateJSON();
        }
        $bc->disconnect();
    }
}
if ($result === null) {
    echo $session->getSessionStateJSON();
}
$result = null;
ob_end_flush();
?>
