<div class="panel-content">
   <h4 class="titulo">Meus Dados</h4>
   <br>

  <form method="POST">
    <label>Nome</label>
    <input type="text" name="nome" class="form-control" value="<?php echo getAdmdata("Nome");?>"><br>

    <label>Usuário</label>
    <input type="text" name="email" class="form-control" value="<?php echo getAdmdata("Usuario");?>" disabled><br>

    <label>Senha</label>
    <input type="password" name="senha" class="form-control" value="<?php echo getAdmdata("Senha");?>"><br>


    <p align="right"><input type="submit" value="Guardar" class="btn btn-primary btn-lg btn-block"></p>
    <input type="hidden" name="env" value="alt">
  </form>
 <?php 
  if(isset($_SESSION["x"] )) {
      alerta("success",$_SESSION["x"]);
      unset($_SESSION["x"]);
  } 
 
 
 
 updateAdmdata();
 
 
 ?>
  </div>