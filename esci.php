<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./file/style.css?v=1">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
	</head>

<body>

<nav class="navbar navbar-expand-sm">
   <a class="navbar-brand" href="index.php"> <img src="./foto/user.png" alt="Logo class="rounded"> </a>
   <a href="profilo.php" class="lista">Profilo</a>
   <a href="esci.php" class="lista">Esci</a>
 
</nav>

<?php
session_start();
if(session_destroy())
{
	header("location:index.php");
}
else {
	echo "Non sei loggato";
}
?>

</body>
</html>