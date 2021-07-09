<?php
function paginacaoadm()
{
 
    if (isset($_GET['pagina'])) {
        $url = $_GET['pagina'];
    } else {
        $url = 'dashboard';
    }
    $explode = explode('/', $url);
    $dir = 'pages/php/';
    $ext = '.php';


    if (file_exists($dir . $explode[0] . $ext) && isset($_SESSION['admlogin'])) {

        include($dir.$explode[0].$ext);
    } else {
        include($dir . "login" . $ext);
    }
}
