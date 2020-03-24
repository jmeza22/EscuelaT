<?php
session_start();
include_once 'Security/SessionManager.php';
include_once 'Security/SystemVariableManager.php';
include_once 'Security/MyCrypt.php';
include_once 'XML/DataSettings.php';
include_once 'Database/SQLDatabase.php';
include_once 'Database/BasicController.php';
include_once 'Others/UploadImage.php';
include_once 'Libraries/PHPMailer/src/Exception.php';
include_once 'Libraries/PHPMailer/src/SMTP.php';
include_once 'Libraries/PHPMailer/src/PHPMailer.php';
include_once 'Others/SendEmail.php';
include_once 'Others/SendSMS.php';