<div class="busca">
  BUSCANDO NA CATEGORIA <b><?php echo strtoupper(getcategoriaNome($explode['1']));?></b>
</div>
<br>

<?php getPosts_fromCategoria($explode['1']);?>

<ul class="pagination justify-content-center">
  <?php pageslistFromCategoria($explode['1']);?>
</ul>
        