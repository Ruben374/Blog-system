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

function getCategorias()
{
	$pdo = pdo();
	$smtp = $pdo->prepare("SELECT *FROM categorias");
	$smtp->execute();
	$total = $smtp->rowcount();
	if ($total > 0) {
		while ($dados = $smtp->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value='{$dados['ID']}'>{$dados['NOME']}</option>";
		}
	} else {

		alerta("danger", "é necessario ter categorias");
		exit();
	}
}
/////////////////////////////////////////Tirar acentos de strings///////////////////////////////////////////
function tirarAcentos($string)
{
	return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
}
//////////////////////////////////////////Função para enviar posts/////////////////////////////////////////

function getData()
{
	date_default_timezone_set('Africa/Luanda');
	return date('d-m-Y H:i:s');
}
//////////////
function setPost()
{
	if (isset($_POST['env']) && $_POST['env'] == "post") {
		$pdo = pdo();
		$subtitulo = tirarAcentos($_POST['titulo']);
		$data = getData();
		//upar imagem no site
		$uploaddir = '../images/uploads/';
		$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
		//guardar url da imagem no banco de dados
		$uploaddir2 = 'images/uploads/';
		$uploadfile2 = $uploaddir2 . basename($_FILES['userfile']['name']);

		if ($_FILES['userfile']['size'] > 0) {
			$stmt = $pdo->prepare("INSERT INTO posts (
			titulo,
			subtitulo,
			postagem,
			imagem,
			data,
			categoria,
			id_postador) VALUES(
			:titulo,
			:subtitulo,
			:postagem,
			:imagem,
			:data,
			:categoria,
			:id_postador
			)
			");
			$stmt->execute([
				':titulo' => $_POST['titulo'],
				':subtitulo' => $subtitulo,
				':postagem' => $_POST['post'],
				':imagem' => $uploadfile2,
				':data' => $data,
				':categoria' => $_POST['categoria'],
				':id_postador' => getadmData("ID")
			]);

			$total = $stmt->rowCount();

			if ($total > 0) {
				move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
				alerta("success", "Publicação cadastrada com sucesso!");
			} else {
				alerta("danger", "ERRO AO ENVIAR A PUBLICAÇÃO");
			}
		} else {
			alerta("danger", "INSIRA UMA IMAGEM!");
		}
	}
}
//////////////FUNÇÃO PARA RETORNAR O NOME DE UMA CATEGORIA DEPOIS DE RECEBER O CODIGO DELA///////////////
function getcategoriaNome($id)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT NOME FROM categorias WHERE ID=:id");
	$stmt->execute([':id' => $id]);
	$dados = $stmt->fetch(PDO::FETCH_ASSOC);
	return $dados["NOME"];
}

///////////////FUNÇÃO PARA MOSTRAR TODAS AS POSTAGENS FEITA PARA O ADM LOGADO NO MOMENTO//////////////////
function getpostAdmin()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
	$stmt->execute();
	$total = $stmt->rowCount();
	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr>
			<td>{$dados['id']}</td>
			<td>{$dados['titulo']}</td>
			<td><span class='badge badge-primary'>" . getcategoriaNome($dados['categoria']) . "</span></td>
			<td>
			  <button id='btnGroupDrop1' type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Gerenciar</button>
			  <div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>
				<a class='dropdown-item bg-dark text-light' href='{$dados['subtitulo']}' target='_blank'>Ver Publicação</a>
				<a class='dropdown-item bg-info text-light' href='admin/editar-post/{$dados['id']}'>Editar Publicação</a>
				<a class='dropdown-item bg-danger text-light' href='admin/deletar-post/{$dados['id']}'>Deletar Publicação</a>
			  </div>
			</td>
		  </tr>";
		}
	}
}
/////////////////////////////////////////////////////////////////////////////
function getDadospost($id, $dado)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
	$stmt->execute([':id' => $id]);
	$dados = $stmt->fetch(PDO::FETCH_ASSOC);
	return $dados[$dado];
}

///////////////////////////////////////////////////////////////////////////////

function getcategoriaActual($id)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT * FROM categorias WHERE ID=:id");
	$stmt->execute([':id' => $id]);
	$dados = $stmt->fetch(PDO::FETCH_ASSOC);
	echo "<option value='{$dados['ID']}'>{$dados['NOME']}(Atual)</option>";
}
/////////////////////////////////////////////////////////////////////////////////
function updatePost($id)
{
	if (isset($_POST['env']) && $_POST['env'] == "alt") {
		$pdo = pdo();
		$subtitulo = tirarAcentos($_POST['titulo']);

		if ($_FILES['userfile']['size'] > 0) {
			$uploaddir = '../images/uploads/';
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

			$uploaddir2 = 'images/uploads/';
			$uploadfile2 = $uploaddir2 . basename($_FILES['userfile']['name']);

			$stmt = $pdo->prepare("UPDATE posts SET 
												titulo = :titulo,
												subtitulo = :subtitulo,
												postagem = :postagem,
												categoria = :categoria, imagem = :imagem WHERE
												id = :id");
			$success = $stmt->execute([
				':titulo' => $_POST['titulo'],
				':subtitulo' => $subtitulo,
				':postagem' => $_POST['post'],
				':categoria' => $_POST['categoria'],
				':imagem' => $uploadfile2,
				':id' => $id
			]);
			move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
		} else {
			$stmt = $pdo->prepare("UPDATE posts SET 
												titulo = :titulo,
												subtitulo = :subtitulo,
												postagem = :postagem,
												categoria = :categoria WHERE
												id = :id");
			$success = $stmt->execute([
				':titulo' => $_POST['titulo'],
				':subtitulo' => $subtitulo,
				':postagem' => $_POST['post'],
				':categoria' => $_POST['categoria'],
				':id' => $id
			]);
		}

		$total = $stmt->rowCount();


		if ($success) {
			alerta("success", "Publicação alterada com sucesso!");
			redireciona(2, "admin/editar-post/{$id}");
		} else {
			alerta("danger", "Erro ao alterar");
		}
	}
}
/////////////////////////////////
function deletaFoto($imagem)
{
	$pdo = pdo();

	$stmt = $pdo->prepare("SELECT imagem FROM posts WHERE imagem = :imagem");
	$stmt->execute([':imagem' => $imagem]);
	$total = $stmt->rowCount();

	if ($total == 1) {
		$dados = $stmt->fetch(PDO::FETCH_ASSOC);
		unlink("../{$dados['imagem']}") or die("Erro ao deletar imagem");
	}
}
////////////////////////////////////////////////////////////

function delete($tabela, $coluna, $id, $backpage)
{
	$pdo = pdo();

	$stmt = $pdo->prepare("DELETE FROM " . $tabela . " WHERE " . $coluna . " = :id");
	$stmt->execute([':id' => $id]);
	$total = $stmt->rowCount();

	if ($total <= 0) {
		alerta("danger", "Erro ao deletar");
	} else {
		redireciona(0, $backpage);
	}
}
/////////////////////////////////////////////////////
function addCategoria()
{
	if (isset($_POST["env"]) && $_POST["env"] == "cat") {

		$pdo=pdo();
		$stmt=$pdo->prepare("INSERT INTO categorias (NOME) VALUES(:nome)");
		$stmt->execute([':nome'=>$_POST["categoria"]]);
		$total=$stmt->rowCount();
		if($total>0){
			echo "categoria cadastrada com sucesso";
		}
	}
}
/////////////////////////////////////////////////////////////
function getCategoriasMenu()
{
	$pdo = pdo();
	$smtp = $pdo->prepare("SELECT *FROM categorias");
	$smtp->execute();
	$total = $smtp->rowcount();
	if ($total > 0) {
		while ($dados = $smtp->fetch(PDO::FETCH_ASSOC)) {
			echo "<li>{$dados['NOME']}</li>";
		}
	} else {

		alerta("danger", "é necessario ter categorias");
		exit();
	}
}
/////////////////////////////////////////////////////
