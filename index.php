<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
            header('Access-Control-Allow-Origin: *');
            header('location: login.html');
        }
        ?>
    </body>
</html>
