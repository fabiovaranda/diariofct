<?php
	session_start();
	session_name('diarioFCT');
	if (isset($_SESSION['idUser']) && $_SESSION['idTipo'] == 2) 
		echo "<script>window.location='inicio.php';</script>";
	if (isset($_SESSION['idUser']) && $_SESSION['idTipo'] == 1) 
		echo "<script>window.location='inicioAdmin.php';</script>";
?>

<html>
	<head>
	
		<title>GPSI | Início</title>
		<?php include_once('head.php'); ?>
	</head>
	<body>

		<?php include_once('login.php'); ?>
		<?php include_once('importarScript.php'); ?>
	</body>
</html>