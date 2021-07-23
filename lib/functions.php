<?php

/////////////////////////////////////////////////////////////////////////////////////////////
function pdo()
{
    $db_host = DB_HOST;
    $db_user = DB_USER;
    $db_password = DB_PASSWORD;
    $db_database = DB_DATABASE;

    try {
       return $pdo = new PDO("mysql:host={$db_host};dbname={$db_database}", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit("error to conect to the database:" . $e->getMessage());
    }
}



/////////////////////////////////////////////////////////////////////////////////////////////


function paginacaoadm()
{

    $url= (isset($_GET["pagina"])) ? $_GET["pagina"] :'dashboard';
    $explode = explode('/', $url);
    $dir = 'pages/php/';
    $ext = '.php';


    if (file_exists($dir.$explode[0].$ext) && isset($_SESSION['admlogin'])) {
        include($dir.$explode[0].$ext);
    } else {
        include($dir."login" .$ext);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
function Alerta($mensagem){
   echo"<div class='alert'>{$mensagem}</div>";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
function login()
{
    if (isset($_POST["x"]) && $_POST["x"]=="y") {
        $pdo=pdo();
        $query=$pdo->prepare("SELECT *FROM usuarios WHERE usuario=:usuario");
        $query->execute([":usuario" => $_POST["email_login"]]);
        $total=$query->rowcount();
        if($total>0){
            $dados=$query->fetch(PDO::FETCH_ASSOC);


            if(password_verify($_POST["senha_login"],$dados["Senha"] )){
              $_SESSION["admlogin"]=$dados["Nome"];
              header("location: dashboard");
            }
            else{
               alerta("usuario ou senha invalidos");
            }
          
        }
        else{
            echo"usuario inexistente";
            
        }
    } 


}
///////////////////////////////////////////////////////////////////////////////////////////////////////////