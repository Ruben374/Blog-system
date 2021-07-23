<?php
	//******************************Conexão com o banco de dados******************************//
	function pdo(){
		$db_host = DB_HOST;
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
		//******************************Inclusão de paginas do administrador******************************//
	function paginacaoadm(){
		$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'dashboard';
		$explode = explode('/', $url);
		$dir = 'pages/php/';
		$ext = '.php';

		if(file_exists($dir.$explode[0].$ext) && isset($_SESSION['admlogin'])){
			include($dir.$explode[0].$ext);
		}else{
			include($dir."login".$ext);
		}
	}
		//******************************Funcão de alerta no formulario de login****************************//

	function alerta($tipo, $mensagem){
		echo "<div class='alert alert-{$tipo}'>{$mensagem}</div>";
	}
	
		//******************************Funcão de redirecionamento****************************//

	function redireciona($tempo, $dir){
		echo "<meta http-equiv='refresh' content='{$tempo}; url={$dir}'>";
	}
		//******************************Funcão para validadção de login****************************//

	function login(){
		if(isset($_POST['log']) && $_POST['log'] == "in"){
			$pdo = pdo();

			$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE Usuario = :usuario");
			$stmt->execute([':usuario' => $_POST['usuario']]);
			$total = $stmt->rowCount();

			if($total > 0){
				$dados = $stmt->fetch(PDO::FETCH_ASSOC);

				if(password_verify($_POST['senha'], $dados['Senha'])){
					$_SESSION['admlogin'] = $dados['Nome'];
					header('Location: dashboard');
				}else{
					alerta("danger", "Usuário ou senha inválidos");
				}
			}
			else{
				alerta("dark","Usuario inexistente");
			}
		}
	}
		//***************************Funcão para verificar se usuario esta logado*************************//

	function verificaLogin(){
		if(isset($_SESSION['admlogin'])){
			header('Location: dashboard');
			exit();
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////