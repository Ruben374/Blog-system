<?php

/////////////////////////////////////////////////////////////////////////////////////////////
function pdo()
{
    $db_host = DB_HOST;
    $db_user = DB_USER;
    $db_password = DB_PASSWORD;
    $db_database = DB_DATABASE;

    try {
        $pdo = new PDO("mysql:host={$db_host};dbname={$db_database}", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit("Erro ao se conectar ao banco de dados:" . $e->getMessage());
    }
}



/////////////////////////////////////////////////////////////////////////////////////////////


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

        include($dir . $explode[0] . $ext);
    } else {
        include($dir . "login" . $ext);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
function login()
{
    if (isset($_POST["x"]) && $_POST["x"]=="y") {
        $pdo=pdo();
        $query=$pdo->prepare("SELECT *FROM usuarios WHERE usuario=:usuario");
        $query->execute([":usuarios" => $_POST["email_login"]]);
        $total=$query->rowcount();
        if($total>0){
            $dados=$pdo->fetch(PDO::FETCH_ASSOC);
            if(password_verify($_POST["senha_login"],$dados["senha"] )){
                echo"<br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                
                logado";
            }
            else{
                echo"<br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                <br><br><br><br>
                
                
                dados incorretos";
            }
          
        }
        else{
            echo"<br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            <br><br><br><br>
            
            usuario inexistente";
            
        }
    } 


}
///////////////////////////////////////////////////////////////////////////////////////////////////////////