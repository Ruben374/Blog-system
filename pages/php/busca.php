<div class="busca">
  BUSCANDO POR <b><?php echo strtoupper(isset($_POST['busca']) ? $_POST['busca'] : '');?></b>
</div>
<br>

<?php getPostsFromBusca();?>

        