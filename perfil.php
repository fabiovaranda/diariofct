<?php
	session_start();
	session_name('diarioFCT');
	if (!isset($_SESSION['idUser'])) 
		echo "<script>window.location='index.php';</script>";
		
	if (isset($_POST['nome'])){
		$nome = $_POST['nome'];
		$email = $_POST['email'];
		$id = $_SESSION['idUser'];
		$turma = $_POST['curso'];
		include_once('DataAccess.php');
		$da = new DataAccess();
		$da->editarUtilizador($id, $nome, $email, $turma);
		echo "<script>alert('Alterações efetuadas com sucesso')</script>";
	}else{
		if (isset($_POST['pwd1'])){
			$pwd = $_POST['pwd1'];
			$pwdOld = $_POST['pwdOld'];
			$id = $_SESSION['idUser'];
			include_once('DataAccess.php');
			$da = new DataAccess();
			$res = $da->editarPassUtilizador($id, md5($pwd), md5($pwdOld));
			if ($res == 1)
				echo "<script>alert('Password alterada com sucesso')</script>";
			else
				echo "<script>alert('Password antiga incorreta')</script>";
		}
	}
	
?>
<html>
	<head>
		<title>GPSI | Início</title>
		<?php include_once('head.php'); ?>
		<script>
			function confirmar(){
				return confirm('Tem a certeza?');
			}
			
			function validarForm(obj){
				var email = document.getElementById(obj).value;
				var re = /^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)/; 
				if (!re.test(email)){
					alert('Endereço de e-mail inválido');
					return false;
				}
				return true;
			}
			
			function validarFormPwd(){
				var pwd1 = document.getElementById('pwd1').value;
				var pwd2 = document.getElementById('pwd2').value;
				if (pwd1 != pwd2){
					alert('As novas passwords não são iguais');
					return false;
				}
				return true;
			}
		</script>
	</head>
	<body>
		<?php include_once('topBar.php'); if ($_SESSION['idTipo'] == 1) include_once('topBarAdmin.php');?>
		<div class='row'>
			<div class='large-12 columns'>
				<div class='row panel' style='position:relative; margin-top:2%'>
					<form action='perfil.php' method='post'>	
						<?php
							include_once('DataAccess.php');
							$da = new DataAccess();
							$id = $_SESSION['idUser'];
							$res = $da->getUser($id);
							$row = mysql_fetch_object($res);
							echo "
								<div class='large-4 columns'>
									<input type='text' name='nome' value='$row->nome' required/>
								</div>
								<div class='large-4 columns'>
									<input type='text' name='email' value='$row->email' id='email' required/>
								</div>
							";
								$resT = $da->getTurmas();
	
							echo "
							<div class='large-4 columns'>
							<select name ='curso'>
							
							";
							
						 while($rowT = mysql_fetch_object($resT)){
							if ($row->idTurma == $rowT->id)
							echo "
								<option value='$rowT->id' selected>$rowT->turma</option>
								";
							else
							echo "
								<option value='$rowT->id'>$rowT->turma</option>
								";
						}
						echo "
							</select>
							</div>
							
							";
						
						?>
						<div class='large-12 centered columns'>	
						<center>
								<input type='submit' name='submit' class='tiny button' onclick="return validarForm('email')" value='Editar Nome e E-mail'/>
						</center>
					</div>
					</form>
					
				</div>
			</div>
		</div>
		<br/>
		
		<div class='row panel'>
			<form action='perfil.php' method='post'>
				<?php
					echo "
						<div class='large-3 columns'>
							<input type='password' name='pwdOld' placeholder='Password Antiga' required/>
						</div>
						<div class='large-3 columns'>
							<input type='password' name='pwd1' id='pwd1' placeholder='Nova Password' required/>
						</div>
						<div class='large-3 columns'>
							<input type='password' name='pwd2' id='pwd2' placeholder='Repita a Password' required/>
						</div>
					";
				?>
				<div class='large-3 columns'>	
						<input type='submit' name='submit' class='tiny button' onclick='return validarFormPwd()' value='Editar Password'/>
				</div>
			</form>
		</div>
		
		
		
		
		<?php include_once('footer.php');?>
		<?php include_once('importarScript.php'); ?>
	</body>
</html>