<?php include_once("lib/includes.php");?>
<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?php echo URL;?>">

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/template/logo-site.png" type="image/png" size="16x16">
    

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/97bdcc5c17.js"></script>

    <title><?php geraTitulo(BLOG_TITLE);?></title>
  </head>
  <body>

    <nav>
      <div align="right"><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
      </button></div>

      <div class="navbar-collapse" id="navbarSupportedContent">
        <ul>
          <li><a href="inicio">Inicio</a></li>
          <li><a href="sobre">Sobre</a></li>
          <li><a href="contato">Contato</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-sm-8">
          <?php paginacaoBlog();?>
          </div>

        <div class="col-sm-4">
          <div class="menu-right">
              <div class="title">BUSCAR</div>
              <div class="content">
                <form method="POST" class="form-inline margin-top" action="busca">
                  <input type="text" name="busca" class="form-control" required>
                  <input type="submit" value="BUSCAR" class="btn btn-primary">
                </form>
              </div>
          </div>

          <div class="menu-right">
            <div class="title">MAIS POPULARES</div>
            <?php getMostpopularposts(); ?>
          </div>

            <div class="menu-right">
              <div class="title">CATEGORIAS</div>
              <div class="content">
                <ul>
                  <?php getCategoriasblog();?>	
                </ul>
              </div>
            </div>
        </div>
      </div>
    </div>

  <footer><?php echo BLOG_TITLE;?> &copy; Criado por <b>Tutoriais e Informática</b></footer>
  </body>
</html>