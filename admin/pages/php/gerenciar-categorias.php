<div class="panel-content">
    <h4 class="titulo">Gerenciar Categorias</h4>
    <br>

    <div class="row">
        <div class="col-sm-7">
            <h5>Cadastrar</h5>
            <hr>

            <form method="POST" class="form-inline">
                <input type="text" name="categoria" class="form-control" placeholder="Nome da Categoria">
                <input type="submit" value="Cadastrar" class="btn btn-primary">
                <input type="hidden" name="env" value="cat">
            </form><br>

            <?php addCategoria(); ?>

        </div>

        <div class="col-sm-5">
            <h5>Categorias</h5>
            <hr>
            <ul class="ul-adm">

            </ul>
        </div>
    </div>
</div>