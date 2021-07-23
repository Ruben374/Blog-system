<?php
//////////////////////////////////////////////////////////////////////
	function pdo(){
		$db_host =DB_HOST;
		$db_usuario = DB_USER;
		$db_senha = DB_PASSWORD;
		$db_banco = DB_DATABASE;


		try{
			return $pdo = new PDO("mysql:host={$db_host};dbname={$db_banco}", $db_usuario, $db_senha, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			exit("Erro ao conectar-se ao banco: ".$e->getMessage());
		}
	}

	
///////////////////////////////////////////////////////////////
	function paginacaoadm(){
		$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'dashboard';
		$explode = explode('/', $url);
		$dir = 'pags/php/';
		$ext = '.php';

		if(file_exists($dir.$explode[0].$ext) && isset($_SESSION['admlogin'])){
			include($dir.$explode[0].$ext);
		}else{
			include($dir."login".$ext);
		}
	}
//////////////////////////////////////////////////////////////////////
	function Alerta($mensagem){
		echo"<div class='alert'>{$mensagem}</div>";
	 }
////////////////////////////////////////////////////////////////////
	 function login(){
		if(isset($_POST['x']) && $_POST['x'] == "y"){
			$pdo = pdo();
	
			$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
			$stmt->execute([':usuario' => $_POST['email_login']]);
			$total = $stmt->rowCount();
	
			if($total > 0){
				$dados = $stmt->fetch(PDO::FETCH_ASSOC);
	
				if(password_verify($_POST['senha_login'], $dados['Senha'])){
					$_SESSION['admlogin'] = $dados['Nome'];
					header('Location: dashboard');
				}else{
					alerta("usuario ou senha invalidos");
				}
			}
			else{
				echo"usuario inexistente";
				
			}
		}
	}

//////////////////////////////////////////////////////////////////////////////////