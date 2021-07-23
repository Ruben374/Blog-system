<?php 
  $dados = getCompletePost(); 
  $data = getData(); 
  atualizaVisitas($dados['id']);
?>
<div class="content-post">
            <div class="title">
              <a href="<?php echo $dados['subtitulo'];?>"><?php echo $dados['titulo'];?></a> 
            </div>
            <img src="<?php echo $dados['imagem'];?>" class="img-fluid">
            <div class="post">
              <?php echo $dados['postagem'];?>
            </div>
            <div class="infos">
              <i class="fas fa-user"></i> <?php echo getDadosUserSite($dados['id_postador'], "nome"); ?> |
              <i class="fas fa-tag"></i> <a href="categoria/<?php echo $dados['categoria'];?>" class="badge badge-primary"><?php echo getNomeCategoria($dados['categoria']); ?></a> |
              <i class="fas fa-eye"></i> <?php echo $dados['visualizacoes'];?> Visitas |  
              <i class="fas fa-comment"></i> <?php echo contador_ComentariosFromPost($dados['id']);?> Comentários |
              <i class='far fa-clock'></i> <?php echo calculaDias($data, $dados['data']);?>
            </div>
          </div>         
         
        <hr>
        <a name="comentar">
        <div class="comentar">
          <h4>Comentar / Comentários</h4>
          <hr>
          <form method="POST">
            <p>
              <input type="text" name="nome" placeholder="Seu nome" class="form-control" required>
            </p>
            <p>
              <textarea name="comentario" placeholder="Seu comentário" class="form-control" rows="5" required></textarea>
            </p>
            <p align="right">
              <input type="submit" value="comentar" class="btn btn-primary">
              <input type="hidden" name="env" value="comentario">
            </p>
          </form>
          <?php enviarComentario($dados['id'], $dados['subtitulo']);?>
        </div>
        </a>
        <hr>

        <a name="comentarios">
        <div class="comentarios">
          <?php pegaComentariosPost($dados['id']);?>
        </div>
      </a>