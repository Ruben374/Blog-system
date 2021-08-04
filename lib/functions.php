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

		$pdo = pdo();
		$stmt = $pdo->prepare("INSERT INTO categorias (NOME) VALUES(:nome)");
		$stmt->execute([':nome' => $_POST["categoria"]]);
		$total = $stmt->rowCount();
		if ($total > 0) {
			alerta("success", "categoria cadastrada com sucesso");
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
			echo "<li>{$dados['NOME']}<a href='admin/deletar-categoria/{$dados['ID']}' class='btn btn-danger btn-sm float-right'>Deletar</a></li>";
		}
	} else {

		alerta("danger", "Nenhuma Categorias registrada");
		exit();
	}
}
/////////////////////////////////////////////////////////////////
function calculaDias($diaX, $diaY)
{
	$data1 = new DateTime($diaX);
	$data2 = new DateTime($diaY);

	$intervalo = $data1->diff($data2);
	if ($intervalo->y > 1) {
		return $intervalo->y . " Anos atrás";
	} elseif ($intervalo->y == 1) {
		return $intervalo->y . " Ano atrás";
	} elseif ($intervalo->m > 1) {
		return $intervalo->m . " Meses atrás";
	} elseif ($intervalo->m == 1) {
		return $intervalo->m . " Mês atrás";
	} elseif ($intervalo->d > 1) {
		return $intervalo->d . " Dias atrás";
	} elseif ($intervalo->d > 0) {
		return $intervalo->d . " Dia atrás";
	} elseif ($intervalo->h > 0) {
		return $intervalo->h . " Horas atrás";
	} elseif ($intervalo->i > 1 && $intervalo->i < 59) {
		return $intervalo->i . " Minutos atrás";
	} elseif ($intervalo->i == 1) {
		return $intervalo->i . " Minuto atrás";
	} elseif ($intervalo->s < 60 && $intervalo->i <= 0) {
		return $intervalo->s . " Segundo atrás";
	}
}
/////////////////////////////////////////////////////

function deletecategoria($categoria)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT * FROM posts WHERE categoria=:categoria");
	$stmt->execute([':categoria' => $categoria]);

	$total = $stmt->rowCount();
	if ($total > 0) {
		alerta("danger", "Não é possivel deletar esta categoria porque existem posts vinculados a ela!");
		redireciona(2, "admin/gerenciar-categorias");
	} else {
		delete("categorias", "ID", $categoria, "admin/gerenciar-categorias");
	}
}

///////////////////////////////////////////////////
function blockacesso()
{
	if (getadmData("SuperAdmin") != "1")
		redireciona(0, "admin/dashboard");
}
////////////////////////////////////////////////////

function addAdm()
{
	if (isset($_POST["env"]) && $_POST["env"] == "adm") {

		$senha = password_hash($_POST["senha"], PASSWORD_BCRYPT);
		$statuscheckbox = 0;

		if (isset($_POST["superadmin"]) && $_POST["superadmin"] == 1)
			$statuscheckbox = 1;
		else
			$statuscheckbox = 0;




		$pdo = pdo();
		$stmt = $pdo->prepare("INSERT INTO usuarios (Nome,Usuario,Senha,SuperAdmin)
	VALUES(:Nome,:Usuario,:Senha,:SuperAdmin)");
		$stmt->execute([
			'Nome' => $_POST["nome"],
			'Usuario' => $_POST["email"],
			'Senha' => $senha,
			'SuperAdmin' => $statuscheckbox
		]);
		$total = $stmt->rowCount();
		if ($total > 0)
			alerta("success", "adm cadastrado");
		else
			alerta("danger", "erro ao cadastrar");
	}
}
//////////////////////////////////////////////////////////////////
function listaAdministradores()
{
	$pdo = pdo();

	$stmt = $pdo->prepare("SELECT * FROM usuarios ORDER BY Nome ASC");
	$stmt->execute();

	$total = $stmt->rowCount();

	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<li>{$dados['Nome']} <a href='admin/deletar-administrador/{$dados['ID']}' class='btn btn-danger btn-sm float-right'>Deletar</a></li>";
		}
	}
}
////////////////////////////////////////////////////////////////////
function updateAdmdata()
{
	if (isset($_POST["env"]) && $_POST["env"] == "alt") {

		if ($_POST["senha"] == getadmData("Senha")) {



			$pdo = pdo();
			$stmt = $pdo->prepare("UPDATE usuarios SET Nome=:Nome,Usuario=:Usuario WHERE Usuario=:Usuario");
			$success = $stmt->execute([
				'Nome' => $_POST["nome"],
				'Usuario' => $_SESSION["admlogin"]
			]);

			if ($success)
				alerta("success", "Dados guardados com sucesso");
			else
				alerta("danger", "erro ao guardar");
		} else {

			$senha = password_hash($_POST["senha"], PASSWORD_BCRYPT);

			$pdo = pdo();
			$stmt = $pdo->prepare("UPDATE usuarios SET Nome=:Nome,Usuario=:Usuario,Senha=:Senha WHERE Usuario=:Usuario");
			$success = $stmt->execute([
				'Nome' => $_POST["nome"],
				'Usuario' => $_SESSION["admlogin"],
				'Senha' => $senha
			]);

			if ($success) {
				$_SESSION["x"] = "Dados guardados com sucesso";
				redireciona(0, "admin/me");
			} else
				alerta("danger", "erro ao guardar");
		}
	}
}
////////////////////////////////////////////////////////
function getcountAdmin()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) AS quant FROM usuarios");
	$stmt->execute();
	$quant = $stmt->fetch();
	return $quant["quant"];
}
//////////////////////////////////////////////////////////////////////
function getcountPosts()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) AS quant FROM posts");
	$stmt->execute();
	$quant = $stmt->fetch();
	return $quant["quant"];
}
////////////////////////////////////////////////////////////////////////
function getcountView()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT SUM(visualizacoes) AS quant FROM posts");
	$stmt->execute();
	$quant = $stmt->fetch();
	return $quant["quant"];
}
////////////////////////////////////////////////////////////////////
function getcountViewFrompost($id)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT visualizacoes FROM posts WHERE id=:id");
	$stmt->execute([':id' => $id]);
	$dados = $stmt->fetch(PDO::FETCH_ASSOC);

	return $dados['visualizacoes'];
}
/////////////////////////////////////////////////////////////////////////
function getcountComents()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) AS quant FROM comentarios");
	$stmt->execute();
	$quant = $stmt->fetch();
	return $quant["quant"];
}
///////////////////////////////////////////////////////////////////////
function getcomentarioAdm()
{
	$dataAtual = getdata();
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT *FROM comentarios  ORDER BY id DESC LIMIT 30");
	$stmt->execute();
	$total = $stmt->rowCount();
	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr>
				<td>{$dados['id']}</td>
				<td>{$dados['nome']}</td>
				<td>" . calculaDias($dados['data'], $dataAtual) . "</td>
				<td>
				  <button  id='btnGroupDrop1' type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Gerenciar</button>
				  <div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>
					<a class='dropdown-item bg-dark text-light' data-toggle='modal' data-target='#exampleModalCenter{$dados['id']}'>Ver Comentário</a>
					<a class='dropdown-item bg-success text-light' href='{$dados['id_post']}' target='_blank'>Ver Publicação</a>
					<a class='dropdown-item bg-danger text-light' href='admin/deletar-comentario/{$dados['id']}'>Deletar Comentário</a>
				  </div>
				</td>
				</tr>";
			lauchModal($dados['id'], $dados['nome'], $dados['comentario']);
		}
	}
}

///////////////////////////////////////////////////////////////////////
function lauchModal($id, $nome, $mensagem)
{
	echo "<div class='modal fade' id='exampleModalCenter{$id}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
	  <div class='modal-dialog modal-dialog-centered' role='document'>
	    <div class='modal-content'>
	      <div class='modal-header'>
	        <h5 class='modal-title' id='exampleModalCenterTitle'>{$nome} Comentou</h5>
	        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
	          <span aria-hidden='true'>&times;</span>
	        </button>
	      </div>
	      <div class='modal-body'>
	        {$mensagem}
	      </div>
    </div>
  </div>
</div>";
}

/////////////////////////////////////////////////////////////////////////////////

function paginacaoBlog()
{
	$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'inicio';
	$explode = explode('/', $url);
	$dir = 'pages/php/';
	$ext = '.php';

	if (file_exists($dir . $explode[0] . $ext)) {
		include($dir . $explode[0] . $ext);
	} else {
		include($dir . "p" . $ext);
	}
}

///////////////////////////////////////////////////////////////////////////////////////////
function getPosts()
{
	$pdo = pdo();
	$data = getData();
	$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'dashboard';
	$explode = explode('/', $url);
	$pg = (isset($explode['2'])) ? $explode['2'] : 1;
	$maximo = 10;
	$inicio = ($pg - 1) * $maximo;

	$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC limit $inicio,$maximo");
	$stmt->execute();

	$total = $stmt->rowCount();

	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {

			echo "<div class='content-post'>
  <div class='title'>
    <a href='{$dados['subtitulo']}'>{$dados['titulo']}</a> 
  </div>
  <div class='content'>
    <div class='container'>
      <div class='row'>
        <div class='col-sm-3'>
          <img src='{$dados['imagem']}' class='img-fluid'>
        </div>
        <div class='col-sm'>
         " . limitaCaracters(strip_tags($dados['postagem'])) . " 
        </div>
      </div>
  <div class='infos'>
    <i class='fas fa-user'></i> " . getData_fromUser($dados['id_postador'], "Nome", "id") . " |
    <i class='fas fa-tag'></i> <a href='categoria/{$dados['categoria']}' class='badge badge-primary'>" . getCategorianome($dados['categoria']) . "</a> |
    <i class='fas fa-eye'></i> " . $dados['visualizacoes'] . " Visitas |  
    <i class='fas fa-comment'></i> " . getcoments_Frompost($dados['id']) . " Comentários |
    <i class='far fa-clock'></i> " . calculaDias($data, $dados['data']) . "
  </div>
    </div>
  </div>
</div>";
		}
	}
}

function limitaCaracters($texto)
{
	if (strlen($texto) <= 365) {
		return $texto;
	} else {
		return mb_substr($texto, 0, 365) . "...";
	}
}
////////////////////////////////////////////////////////////////////////////
function getData_fromUser($usuario, $var, $where)
{

	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE $where = :usuario");
	$stmt->execute([':usuario' => $usuario]);

	$dados = $stmt->fetch(PDO::FETCH_ASSOC);
	return $dados[$var];
}
///////////////////////////////////////////////////////////////////////////////////////////
function getComents_FromPost($id_post)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) AS quant FROM comentarios WHERE id_post = :id_post");
	$stmt->execute([':id_post' => $id_post]);
	$quant = $stmt->fetch();
	return $quant["quant"];
}
/////////////////////////////////////////////////////////////////////////////////////////

function Pageslist()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) AS quant FROM posts");
	$stmt->execute();
	$quant = $stmt->fetch();
	$maximo = 10;
	$links = ceil($quant["quant"] / $maximo);
	$pg = (isset($explode['2'])) ? $explode['2'] : 1;
	for ($i = 1; $i < $pg + $links; $i++) {
		echo "<li class='page-item'><a class='page-link' href='inicio/posts/{$i}'>{$i}</a></li>";
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////

function getMostpopularposts()
{

	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT *FROM posts ORDER BY visualizacoes DESC LIMIT 10");
	$stmt->execute();
	$total = $stmt->rowCount();
	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<div class='content margin-top'>
	<div class='media'>
	  <img src='{$dados['imagem']}' class='mr-3'>
	  <div class='media-body'>
	    <h5 class='mt-0'><a href='{$dados['subtitulo']}'>{$dados['titulo']}</a></h5>
	  </div>
	</div>
</div>";
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////

function getCategoriasblog()
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT *FROM categorias ORDER BY NOME DESC");
	$stmt->execute();
	$total = $stmt->rowCount();
	if ($total > 0) {
		while ($dados = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			echo "<li><a href='categoria/{$dados['ID']}'>{$dados['NOME']} " . getTotalPostsByCategoria($dados['ID']) . "</a></li>";
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getTotalPostsByCategoria($id)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) AS quant FROM posts WHERE categoria=:id");
	$stmt->execute([':id' => $id]);
	$quant = $stmt->fetch();
	return $quant["quant"];
}
//////////////////////////////////////////////////////////////////////////////////////////////////
function getPosts_fromCategoria($id)
{
	$pdo = pdo();
	$data = getdata();

	///////////////////////////

	$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'dashboard';
	$explode = explode('/', $url);
	$pg = (isset($explode['3'])) ? $explode['3'] : 1;
	$maximo = 10;
	$inicio = ($pg - 1) * $maximo;

	/////////////////////////
	$stmt = $pdo->prepare("SELECT *FROM posts WHERE categoria =:id ORDER BY id DESC limit $inicio,$maximo");
	$stmt->execute([':id' => $id]);
	$total = $stmt->rowCount();


	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<div class='content-post'>
		<div class='title'>
		  <a href='{$dados['subtitulo']}'>{$dados['titulo']}</a> 
		</div>
		<div class='content'>
		  <div class='container'>
			<div class='row'>
			  <div class='col-sm-3'>
				<img src='{$dados['imagem']}' class='img-fluid'>
			  </div>
			  <div class='col-sm'>
			   " . limitaCaracters(strip_tags($dados['postagem'])) . " 
			  </div>
			</div>
		<div class='infos'>
		  <i class='fas fa-user'></i> " . getData_fromUser($dados['id_postador'], "Nome", "id") . " |
		  <i class='fas fa-tag'></i> <a href='categoria/{$dados['categoria']}' class='badge badge-primary'>" . getCategorianome($dados['categoria']) . "</a> |
		  <i class='fas fa-eye'></i> " . $dados['visualizacoes'] . " Visitas |  
		  <i class='fas fa-comment'></i> " . getcomentarioFrompost($dados['id']) . " Comentários |
		  <i class='far fa-clock'></i> " . calculaDias($data, $dados['data']) . "
		</div>
		  </div>
		</div>
	  </div>";
		}
	}
}
//////////////////////////////////////////////////////////////////////////
function getcomentarioFrompost($id)
{
	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT COUNT(*) as quant FROM comentarios WHERE id_post=:id");
	$stmt->execute([':id' => $id]);
	$quant = $stmt->fetch();
	return $quant["quant"];
}
////////////////////////////////////////////////////////////////////////////
function pageslistFromCategoria($categoria)
{
	$pdo = pdo();

	$stmt = $pdo->prepare("SELECT * FROM posts WHERE categoria = :categoria");
	$stmt->execute([':categoria' => $categoria]);
	$total = $stmt->rowCount();

	$maximo = 10;
	$links = ceil($total / $maximo);
	$pg = (isset($explode['2'])) ? $explode['2'] : 1;

	for ($i = 1; $i < $pg + $links; $i++) {
		echo "<li class='page-item'><a class='page-link' href='categoria/{$categoria}/posts/{$i}'>{$i}</a></li>";
	}
}
////////////////////////////////////////////////////////////////////////
function getPostsFromBusca()
{
	$pdo = pdo();
	$data = getData();

	if (isset($_POST['busca'])) {
		$busca = "%{$_POST['busca']}%";
	} else {
		$busca = "";
	}


	$stmt = $pdo->prepare("SELECT * FROM posts WHERE titulo LIKE :titulo OR postagem LIKE :postagem ORDER BY id DESC ");
	$stmt->execute([':titulo' => $busca, ':postagem' => $busca]);

	$total = $stmt->rowCount();

	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {

			echo "<div class='content-post'>
<div class='title'>
<a href='{$dados['subtitulo']}'>{$dados['titulo']}</a> 
</div>
<div class='content'>
<div class='container'>
  <div class='row'>
	<div class='col-sm-3'>
	  <img src='{$dados['imagem']}' class='img-fluid'>
	</div>
	<div class='col-sm'>
	 " . limitaCaracters(strip_tags($dados['postagem'])) . " 
	</div>
  </div>
<div class='infos'>
<i class='fas fa-user'></i> " . getData_fromUser($dados['id_postador'], "Nome", "id") . " |
<i class='fas fa-tag'></i> <a href='categoria/{$dados['categoria']}' class='badge badge-primary'>" . getCategorianome($dados['categoria']) . "</a> |
<i class='fas fa-eye'></i> " . $dados['visualizacoes'] . " Visitas |  
<i class='fas fa-comment'></i> " . getComentarioFrompost($dados['id']) . " Comentários |
<i class='far fa-clock'></i> " . calculaDias($data, $dados['data']) . " 
</div>
</div>
</div>
</div>";
		}
	}
}
////////////////////////////////////////////////////////
function getcompletePost()
{
	$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'inicio';
	$explode = explode('/', $url);

	$pdo = pdo();
	$stmt = $pdo->prepare("SELECT *FROM posts WHERE subtitulo=:subtitulo");
	$stmt->execute(['subtitulo' => $explode[0]]);
	$total = $stmt->rowCount();
	if ($total <= 0) {
		include("pages/php/404.php");
		exit();
	} else {
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}
///////////////////////////////////////////////////////////////////////

function sendComent($id_post, $subtitulo)
{
	if (isset($_POST['env']) && $_POST['env'] == "comentario") {
		$pdo = pdo();
		$data = getData();

		$stmt = $pdo->prepare("INSERT INTO comentarios (id_post, nome, comentario, data) VALUES (:id_post, :nome, :comentario, :data)");
		$stmt->execute([
			':id_post' => $id_post,
			':nome' => $_POST['nome'],
			':comentario' => $_POST['comentario'],
			':data' => $data
		]);
		$total = $stmt->rowCount();

		if ($total > 0) {
			alerta("success", "Comentário enviado com sucesso!");
			redireciona(3, $subtitulo . "#comentarios");
		} else {
			alerta("danger", "Erro ao enviar o comentário");
		}
	}
}
///////////////////////////////////////////////////////////////////
function getComentPost($id_post)
{
	$pdo = pdo();
	$data = getData();

	$stmt = $pdo->prepare("SELECT * FROM comentarios WHERE id_post = :id_post ORDER BY id DESC");
	$stmt->execute([':id_post' => $id_post]);
	$stmt->execute();

	$total = $stmt->rowCount();

	if ($total > 0) {
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<div class='container'>
<div class='row'>
<div class='col-sm-2'>
  <img src='images/template/nophoto.png' class='img-fluid'>
</div>
<div class='col-sm'>
  <div class='card'>
	<div class='card-header'>
	  <b>{$dados['nome']}</b> Comentou 
	  <span class='float-right small'>" . calculaDias($data, $dados['data']) . "</span>
	</div>
	<div class='card-body'>
	  {$dados['comentario']}
	</div>
  </div>
</div>
</div>
</div><br>";
		}
	}
}
///////////////////////////////////////////////////////////////////
function geraTitulo($titulo)
{
	$url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'inicio';
	$explode = explode('/', $url);

	switch ($explode['0']) {
		case 'inicio':
			echo $titulo . " | Inicio";
			break;

		case 'sobre':
			echo $titulo . " | Sobre";
			break;

		case 'contato':
			echo $titulo . " | Contato";
			break;

		case 'categoria':
			echo "Buscando na categoria: " . strtoupper(getCategorianome($explode['1']));
			break;

		case 'busca':
			echo "Buscando por: " . strtoupper($_POST['busca']);
			break;

		case '404':
			echo "ERROR 404";
			break;

		default:
			$dados = getCompletePost($explode[0]);
			echo $dados['titulo'];
			break;
	}
}

////////////////////////////////////////////////////////////////////
function countViews($id_post)
{
	$nVisitas =  getcountViewFrompost($id_post) + 1;

	$pdo = pdo();

	$stmt = $pdo->prepare("UPDATE posts SET visualizacoes = :visualizacoes WHERE id = :id");
	$stmt->execute([':visualizacoes' => $nVisitas, ':id' => $id_post]);
}
