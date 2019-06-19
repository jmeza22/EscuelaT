<html>
    <head>
        <title>Cargar Imagen</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cargar Imagen"/>
        <meta name="author" content="Jose Meza"/>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>

        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div class="panel panel-default" style="width: 80% !important;">
            <div class="panel-heading">Cargar Imagen</div>
            <div class="panel-body" >
                <?php
                include_once 'Base/Controllers/Security/SetsAndHeaders.php';
                include_once 'Base/Controllers/Others/UploadImage.php';
                include_once 'Base/Controllers/Security/SessionManager.php';
                $session = new SessionManager();
                $upload = null;
                $result = null;
                if ($session->hasLogin() && isset($_POST) && $_POST != null) {
                    $result = $session->getSessionStateJSON();
                    $result = json_decode($result, true);
                    $upload = new UploadImage();
                    $upload->setFileName('imageFile');
                    $upload->setPrefix($_POST['prefix_image']);
                    $upload->setNewName($_POST['name_image']);
                    $upload->setExtension('.png');
                    if ($upload->Upload()) {
                        $result['message'] = 'La Imagen se ha Cargado con Exito!.';
                        $result['status'] = 1;
                    } else {
                        $result['message'] = 'Hubo un Error al Cargar la Imagen!.';
                        $result['status'] = 0;
                    }
                    echo '<center><h1>' . $result['message'] . '</h1></center>';
                    $result = json_encode($result);
                } else {
                    echo $session->getSessionStateJSON();
                }
                ?>
                <center>
                    <button type="button" onclick="history.back()" class="btn btn-default">Volver</button>
                </center>
            </div>
        </div>
    </body>
</html>