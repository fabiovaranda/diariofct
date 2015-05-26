<?php		
	if (isset($_POST['Email'])){
		$email = $_POST['Email'];
		$nome = $_POST['Nome'];
		$turma = $_POST['turma'];
		$idTipo = 2; //1 - prof || 2 - aluno
		$pwd = md5($_POST['pwd']); //md5 serve para encriptar a password
		include_once('DataAccess.php');
		$da = new DataAccess();
		$da->inserirUtilizador($nome, $email, $pwd, $idTipo, $turma);
		echo "<script>alert('Utilizador registado com sucesso');window.location='index.php';</script>";
	}
?>
<html>
	<head>
		<title>GPSI | Registar</title>
		<?php include_once('head.php'); ?>
	</head>
	<body>
		<div class='row' style='position:relative; top:5%'>
			<div class='large-4 large-centered columns'>
				<img src='foundation/img/logoGrandeEPBJC.png'/>
			</div>
		</div>
		<div class="row" style='position:relative; top:15%'>			
		  <div class="large-4 large-centered columns">
				<form method='post' action='registar.php'>
					<div class='row panel'>
						<div class='large-12 columns'>
							<input type='text' placeholder='Nome' name='Nome' required/>
						</div>
						<div class='large-12 columns'>
							<input type='text' placeholder='E-mail' name='Email' required/>
						</div>
						<div class='large-12 columns'>
							<input type='password' placeholder='Password' name='pwd' required/>
						</div>
						<div class='large-12 columns'>
							<select name='turma'>
								<option value='3'>11ºASC</option>
								<option value='1'>11ºGPSI</option>
								<option value='2'>11ºHSTA</option>								
							</select>
						</div>
						<div class='large-4 large-centered columns'>
							<input type='submit' value='Registar' class='button tiny'/>
						</div>
					</div>
				</form>
		  </div>
		</div>
		
		<?php include_once('importarScript.php'); ?>
	</body>
</html>