<?php

ini_set('session.cookie_httponly', 1);
date_default_timezone_set('America/Bogota');
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
}
?>