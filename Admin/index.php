<?php
include_once("../lib/includes.php");
?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8" />
  <title><?php echo BLOG_TITLE ?> </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <base href="<?php echo URL ?>">
  <link rel="stylesheet" type="text/css" href="css/style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>
 <?php
 paginacaoadm();
 ?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>

</html>