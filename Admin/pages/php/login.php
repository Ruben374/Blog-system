

<!----------------------------------------------->
<?php verificaLogin();?>
<!----------------------------------------------->


<div class="panel-content col-sm-6 offset-md-3">
   <h4 class="titulo">Logue-se para continuar</h4>
   <br>

    <form method="POST">
      <label>Usuário</label>
      <input type="text" name="usuario" class="form-control" required><br>

      <label>Senha</label>
      <input type="password" name="senha" class="form-control" required><br>


      <p align="right"><input type="submit" value="Entrar" class="btn btn-primary btn-lg btn-block"></p>
      <input type="hidden" name="log" value="in">
    </form>
    <?php login();?>
  </div>