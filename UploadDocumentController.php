<html>
    <head>
        <title>Cargar Documento</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cargar Documento"/>
        <meta name="author" content="Jose Meza"/>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>

        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div class="panel panel-default" style="width: 80% !important;">
            <div class="panel-heading">Cargar Documento</div>
            <div class="panel-body" >
                <?php
                session_start();
                include_once 'Base/Controllers/Security/SessionManager.php';
                include_once 'Base/Controllers/Security/SetsAndHeaders.php';
                include_once 'Base/Controllers/Others/UploadDocument.php';
                $session = new SessionManager();
                $upload = null;
                $result = null;
                if ($session->hasLogin() && isset($_POST['prefix_document']) && isset($_POST['name_document'])) {

                    $result = $session->getSessionStateJSON();
                    $result = json_decode($result, true);
                    $upload = new UploadDocument();
                    $upload->setFileName('document-file');
                    if (isset($_POST['prefix_document']) && isset($_POST['name_document'])) {
                        if ($_POST['prefix_document'] !== '' && $_POST['name_document'] !== '' && $_POST['prefix_document'] !== 'NULL' && $_POST['name_document'] !== 'NULL') {
                            $upload->setPrefix($_POST['prefix_document']);
                            $upload->setNewName($_POST['name_document']);
                        }
                    }
                    if (isset($_POST['fullname_document'])) {
                        $upload->setFullName($_POST['fullname_document']);
                    }
                    if ($upload->Upload()) {
                        $result['message'] = 'El Documento se ha Cargado con Exito!.';
                        $result['status'] = 1;
                    } else {
                        $result['message'] = 'Hubo un Error al Cargar el Documento!.';
                        $result['status'] = 0;
                    }
                    echo '<center><h1>' . $result['message'] . '</h1></center>';
                    $result = json_encode($result);
                } else {
                    echo $session->getSessionStateJSON();
                }
                ?>
                <center>
                    <button type="button" onclick="window.back(); window.close();" class="btn btn-default">Volver</button>
                </center>
            </div>
        </div>
    </body>
</html>