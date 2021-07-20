<?php


function pdo(){
    $db_host =DB_HOST;
    $db_user =DB_USER;
    $db_password =DB_PASSWORD;
    $db_database =DB_DATABASE;

    try{
$pdo=new PDO("mysql:host={$db_host};dbname={$db_database}",$db_user,$db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
exit("Erro ao se conectar ao banco de dados:".$e->getMessage());
    }
}






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


function login(){
    if(isset($_POST['log']) && $_POST['log'] == "in" ){
        echo"oooooo";
    }
    else{
        echo"gozarei";
    }
}
