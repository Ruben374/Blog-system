<?php blockAcesso();?>
<div class="panel-content">
         <h4 class="titulo">Gerenciar Administradores</h4>
         <br>

          <div class="container">
            <div class="row">
              <div class="col-sm-6">
                <h4>Adicionar</h4>
                <hr>
                <form method="POST">
                  <label>Nome</label>
                  <input type="text" name="nome" class="form-control"><br>

                  <label>Usuário</label>
                  <input type="email" name="email" class="form-control"><br>

                  <label>Senha</label>
                  <input type="password" name="senha" class="form-control"><br>

                  <label>É super admin?</label>
                  <input type="checkbox" name="superadmin" value="1"><br>
                  <code>Superadmin pode deletar outros administradores</code>
                  <br><br>

                  <p align="right"><input type="submit" value="Cadastrar" class="btn btn-primary btn-lg btn-block"></p>
                  <input type="hidden" name="env" value="adm">
                </form>
                <?php addAdm();?>
              </div>

              <div class="col-sm-6">
                <h4>Administradores</h4>
                <hr>
                <ul class="ul-adm">
                  <?php listaAdministradores();?>
                </ul>
              </div>
            </div>
          </div>
        </div>