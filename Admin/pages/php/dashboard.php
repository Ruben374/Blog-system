

<div class="panel-content">
<h4>Bem vindo <b><?php  echo getadmData("Nome"); ?></b></h4>
<hr>
Aqui algumas estatísticas do blog
<hr>
<div class="container">
  <div class="row">
    <div class="col-sm-3" align="center">
      <h2 class="number"><?php echo getcountPosts() ?></h2>
      <small>Publicações</small>
    </div>

    <div class="col-sm-3" align="center">
      <h2 class="number"><?php echo getcountComents() ?></h2>
      <small>Comentários</small>
    </div>

    <div class="col-sm-3" align="center">
      <h2 class="number"><?php echo getcountView() ?></h2>
      <small>Visualizações</small>
    </div>

    <div class="col-sm-3" align="center">
      <h2 class="number"><?php echo getcountAdmin(); ?> </h2>
      <small>Administradores</small>
    </div>
  </div>
</div>
<hr>

</div>
