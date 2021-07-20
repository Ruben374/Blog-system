
<head>
  <meta charset="UTF-8" />
  <title><?php echo BLOG_TITLE ?> </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <base href="<?php echo URL ?>">
  <link rel="stylesheet" type="text/css" href="css/style.css"/>
 

</head>

<body>
<div class="container">
    <a class="links" id="paracadastro"></a>
    <a class="links" id="paralogin"></a>

    <div class="content">
      <!--FORMULÁRIO DE LOGIN-->
      <div id="login">
        <form method="POST">
          <h1>Login</h1>
          <p>
            <label for="nome_login">Seu nome</label>
            <input id="nome_login" name="nome_login" type="text" placeholder="ex. contato@htmlecsspro.com" />
          </p>

          <p>
            <label for="email_login">Seu e-mail</label>
            <input id="email_login" name="email_login"  type="password" placeholder="ex. senha" />
          </p>

          <p>
            <input type="checkbox" name="manterlogado" id="manterlogado" value="" />
            <label for="manterlogado">Manter-me logado</label>
          </p>

          <p>
            <input type="submit" value="Logar"name="logar" />
          </p>

          <p class="link">
            Ainda não tem conta?
            <a href="#paracadastro">Cadastre-se</a>
          </p>
          <input type="hidden" name="log" value="in">
        </form>
        <?php
       login();
        ?>
      </div>

    </div>
 </div>

</body>

