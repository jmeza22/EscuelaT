<?php

include_once 'Libraries/Controllers.php';
$session = new SessionManager();
$bc = null;
$result = null;
$model = 'CalificacionesApp';
$findBy = 'id_calificacion';
$action = 'update';
$postdata = null;
$configuracion = null;
$sql = "";
if ($session->hasLogin() && $session->checkToken() && ($session->getSuperAdmin() == 1 || $session->getAdmin() == 1)) {
    if (isset($_POST) && $_POST != null) {
        $bc = new BasicController();
        $bc->connect();
        $bc->setModel($model);
        $bc->setFindBy($findBy);
        $bc->setAction($action);
        $sql = "SELECT (p1_porcentaje_configuracion/100) AS p1,"
                . " (p2_porcentaje_configuracion/100) AS p2,"
                . " (p3_porcentaje_configuracion/100) AS p3,"
                . " (p4_porcentaje_configuracion/100) AS p4,"
                . " (p5_porcentaje_configuracion/100) AS p5,"
                . " (p6_porcentaje_configuracion/100) AS p6, "
                . " valaprueba_configuracion AS valaprueba "
                . " FROM ConfiguracionApp "
                . " WHERE status_configuracion=1 "
                . " AND id_escuela='" . $session->getEnterpriseID() . "'";

        $configuracion = $bc->selectAssocArray($sql);

        if (isset($_POST['id_periodo']) && isset($_POST['id_programa']) && isset($_POST['numgrado_programa']) && isset($_POST['id_grupo']) && $configuracion !== null && is_array($configuracion)) {
            $configuracion = $configuracion[0];
            $sql = "UPDATE CalificacionesApp "
                    . " SET pfin_nd_calificacion="
                    . "("
                    . "(IFNULL(p1_nd_calificacion,0)*" . $configuracion['p1'] . ") + "
                    . "(IFNULL(p2_nd_calificacion,0)*" . $configuracion['p2'] . ") + "
                    . "(IFNULL(p3_nd_calificacion,0)*" . $configuracion['p3'] . ") + "
                    . "(IFNULL(p4_nd_calificacion,0)*" . $configuracion['p4'] . ") + "
                    . "(IFNULL(p5_nd_calificacion,0)*" . $configuracion['p5'] . ") + "
                    . "(IFNULL(p6_nd_calificacion,0)*" . $configuracion['p6'] . ")  "
                    . "),"
                    . " pfin_ausencias_calificacion = (IFNULL(p1_ausencias_calificacion,0) + IFNULL(p2_ausencias_calificacion,0) + IFNULL(p3_ausencias_calificacion,0) + IFNULL(p4_ausencias_calificacion,0) + IFNULL(p5_ausencias_calificacion,0) + IFNULL(p6_ausencias_calificacion,0))"
                    . " WHERE id_escuela=:p_id_escuela "
                    . " AND id_periodo=:p_id_periodo "
                    . " AND id_programa=:p_id_programa "
                    . " AND numgrado_programa=:p_numgrado_programa "
                    . " AND id_grupo=:p_id_grupo "
                    . " AND status_calificacion=1 ";
            $arraywhere = array();
            $arraywhere['p_id_escuela'] = $session->getEnterpriseID();
            $arraywhere['p_id_periodo'] = $_POST['id_periodo'];
            $arraywhere['p_id_programa'] = $_POST['id_programa'];
            $arraywhere['p_numgrado_programa'] = $_POST['numgrado_programa'];
            $arraywhere['p_id_grupo'] = $_POST['id_grupo'];
            $result = $bc->query($sql, $arraywhere);

            if ($result !== null) {
                $result = $bc->parseResults($result, 'Operaciones Realizadas con Exito!.', 1);
            } else {
                $result = $bc->parseResults($result, 'Operaciones han fallado!.', 0);
            }

            $sql = "UPDATE CalificacionesApp "
                    . " SET pfin_comentarios_calificacion="
                    . " 'El Estudiante Aprobó la Asignatura.' "
                    . " WHERE id_escuela=:p_id_escuela "
                    . " AND id_periodo=:p_id_periodo "
                    . " AND id_programa=:p_id_programa "
                    . " AND numgrado_programa=:p_numgrado_programa "
                    . " AND id_grupo=:p_id_grupo "
                    . " AND status_calificacion=1 "
                    . " AND pfin_nd_calificacion >= " . $configuracion['valaprueba'] . " ";
            $bc->query($sql, $arraywhere);

            $sql = "UPDATE CalificacionesApp "
                    . " SET pfin_comentarios_calificacion="
                    . " 'El Estudiante Reprobó la Asignatura.' "
                    . " WHERE id_escuela=:p_id_escuela "
                    . " AND id_periodo=:p_id_periodo "
                    . " AND id_programa=:p_id_programa "
                    . " AND numgrado_programa=:p_numgrado_programa "
                    . " AND id_grupo=:p_id_grupo "
                    . " AND status_calificacion=1 "
                    . " AND pfin_nd_calificacion < " . $configuracion['valaprueba'] . " ";
            $bc->query($sql, $arraywhere);

            echo $result;
        }
        $bc->disconnect();
    }
} else {
    echo $session->getSessionStateJSON();
}
?>