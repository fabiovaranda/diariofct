<?php
	if (isset($_POST['Email'])){
		//tentar efetuar login
		$email = $_POST['Email'];
		$pwd = md5($_POST['pwd']); //md5 serve para encriptar a password
		include_once('DataAccess.php');
		$da = new DataAccess();
		$res = $da->login($email, $pwd);
		//Se a variável $res tiver resultados, significa que o login foi efetuado com sucesso
		if (mysql_num_rows($res) > 0) {			
			session_start();			
			session_name('diarioFCT');
			$row = mysql_fetch_object($res);
			$_SESSION['idUser'] = $row->id;
			$_SESSION['idTipo'] = $row->idTipoUtilizador;
			if ($row->idTipoUtilizador == 1)
				echo "<script>window.location='inicioAdmin.php'</script>";
			else
				echo "<script>window.location='inicio.php'</script>";
		
		}else{
			echo "<script>alert('E-mail ou password inválidos'); window.location='index.php'</script>";
		}
	}
?>