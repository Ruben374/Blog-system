<?php
//******************************Conexão com o banco de dados******************************//
function pdo()
{
	$db_host = DB_HOST;
	$db_usuario = DB_USER;
	$db_senha = DB_PASSWORD;
	$db_banco = DB_DATABASE;
	try {
		return $pdo = new PDO("mysql:host={$db_host};dbname={$db_banco}", $db_usuario, $db_senha, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		exit("Erro ao conectar-se ao banco: " . $e->getMessage());
	}
}
//******************************Inclusão de paginas do administrador******************************//
function paginacaoadm()
{
	$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'dashboard';
	$explode = explode('/', $url);
	$dir = 'pages/php/';
	$ext = '.php';

	if (file_exists($dir . $explode[0] . $ext) && isset($_SESSION['admlogin'])) {
		include($dir . $explode[0] . $ext);
	} else {
		include($dir . "login" . $ext);
	}
}
//******************************Funcão de alerta no formulario de login****************************//

function alerta($tipo, $mensagem)
{
	echo "<div class='alert alert-{$tipo}'>{$mensagem}</div>";
}

//******************************Funcão de redirecionamento****************************//

function redireciona($tempo, $dir)
{
	echo "<meta http-equiv='refresh' content='{$tempo}; url={$dir}'>";
}
//******************************Funcão para validadção de login****************************//

function login()
{
	if (isset($_POST['log']) && $_POST['log'] == "in") {
		$pdo = pdo();

		$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE Usuario = :usuario");
		$stmt->execute([':usuario' => $_POST['usuario']]);
		$total = $stmt->rowCount();

		if ($total > 0) {
			$dados = $stmt->fetch(PDO::FETCH_ASSOC);

			if (password_verify($_POST['senha'], $dados['Senha'])) {
				$_SESSION['admlogin'] = $dados['Usuario'];
				header('Location: dashboard');
			} else {
				alerta("danger", "Usuário ou senha inválidos");
			}
		} else {
			alerta("dark", "Usuario inexistente");
		}
	}
}
//***************************Funcão para verificar se usuario esta logado*************************//

function verificaLogin()
{
	if (isset($_SESSION['admlogin'])) {
		header('Location: dashboard');
		exit();
	}
}
/////////////////////////Função para retornar os dados do adm//////////////////////////////

function getadmData($var)
{
	if (isset($_SESSION["admlogin"])) {
		$pdo = pdo();
		$smtp = $pdo->prepare("SELECT * FROM usuarios WHERE Usuario = :usuario");
		$smtp->execute([":usuario" => $_SESSION["admlogin"]]);
		$dados = $smtp->fetch(PDO::FETCH_ASSOC);
		return $dados[$var];
	}
}
/////////////////////////////////////Função para pegar todas as categorias /////////////////////////////////

function getCategorias(){
	$pdo=pdo();
	$smtp=$pdo->prepare("SELECT *FROM categorias");
	$smtp->execute();
	$total=$smtp->rowcount();
	if($total>0){
while($dados=$smtp->fetch(PDO::FETCH_ASSOC)){
	echo "<option value='{$dados['ID']}'>{$dados['NOME']}</option>";
} 


	}
	else{
	
		alerta("danger","é necessario ter categorias");	
		exit();
	}


}
/////////////////////////////////////////Tirar acentos de strings///////////////////////////////////////////
function tirarAcentos($string){
	return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}
//////////////////////////////////////////Função para enviar posts/////////////////////////////////////////

function getData(){
	date_default_timezone_set('Africa/Luanda');
	return date('d-m-Y H:i:s');
}
//////////////
function setPost(){

	if(isset($_POST["env"]) && $_POST["env"]=="post"){
		$subtitulo=tiraracentos($_POST["titulo"]);

        $data=getData();
		$pdo=pdo();
		
		$pdo=pdo();
	
	
		$stmt = $pdo->prepare("INSERT INTO posts (
			titulo,
			subtitulo,
			postagem,
		
			data,
			categoria,
			id_postador) VALUES(
			:titulo,
			:subtitulo,
			:postagem,
		
			:data,
			:categoria,
			:id_postador
			)
			");
		$stmt->execute([
			':titulo' => $_POST['titulo'],
			':subtitulo' => $subtitulo,
			':postagem' => $_POST['post'],
		
			':data' => $data,
			':categoria' => $_POST['categoria'],
			':id_postador' => $_SESSION['admlogin']
		]);


	/*
		$smtp=$pdo->prepare("INSERT INTO postagens (
		titulo,
		subtitulo,	
		postagem,	
		imagem,
		dataDopost,	
		categoria,	
		Postador,
		visualizacoes	
	)
		VALUES(
		:titulo,
		:subtitulo,	
		:postagem,	
		:imagem,
		:dataDopost,	
		:categoria,	
		:Postador,
		:vizualizacoes	
		
		)");
	
	$smtp->execute([
		':titulo' => $_POST['titulo'],
		':subtitulo' => $subtitulo,
		':postagem' => $_POST['post'],
		':imagem'=>"img",
		':dataDopost' => $data,	
		':categoria' => $_POST['categoria'],
		':Postador' => $_SESSION['admlogin'],
		':vizualizacoes' => "viso"
	]);*/
	}
}
