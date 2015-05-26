<?php
	function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
	{
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;
		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
			$rand = mt_rand(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
	}


	function EnviarMail($nome, $pwd, $email){
            $subject = "Diário de FCT - Recuperar Password";

            $body = "Caro(a) $nome, \n\n A sua nova palavra-passe de acesso ao Diário de FCT é $pwd\n\n Diário de FCT \n\n http://www.nota100.pt/diariofct \n\n Não responda a este e-mail";

            $headers = 'From: Diário de FCT geral@nota100.pt' . "\r\n" .
				'Reply-To: geral@nota100.pt' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

            mail($email, $subject, $body, $headers);
    }
	
	if (isset($_POST['submit'])){
		$email = $_POST['Email'];
		include_once('DataAccess.php');
		$da = new DataAccess();
		$pwd = geraSenha(6, false, true);
		$nome = $da->updatePwdUtilizador($email, md5($pwd));
		if ($nome == -1)
			echo "<script>alert('Endereço de e-mail inexistente.'); window.location='index.php'</script>";
		else{
			EnviarMail($nome, $pwd, $email);
			echo "<script>alert('Foi-lhe enviado um e-mail com a sua nova password.'); window.location='index.php'</script>";
		}
	}
?>
<script>
	function esconderMostrar(obj){
		if (obj == 'divLogin'){
			document.getElementById('divLogin').style.display = 'block';
			document.getElementById('divForgotPwd').style.display = 'none';
		}else{
			document.getElementById('divLogin').style.display = 'none';
			document.getElementById('divForgotPwd').style.display = 'block';
		}
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
</script>
<div class='row' style='position:relative; top:5%'>
	<div class='large-4 large-centered columns'>
		<img src='foundation/img/logoGrandeEPBJC.png'/>
	</div>
</div>

<div class="row" style='position:relative; top:15%' id='divLogin'>
  <div class="large-4 columns medium-4  small-11 columns ">&nbsp;</div>
  <div class="large-4 columns medium-4 small-11 columns ">
		<form method='post' action='doLogin.php'>
		<div class='row panel'>
			<div class='large-12 columns  '>
				<input type='text' placeholder='E-mail' name='Email' id='Email' required/>
			</div>
			<div class='large-12 columns'>
				<input type='password' placeholder='Password' name='pwd' required/>
			</div>
			<div class='large-12 columns'>
				<center>
					<input type='submit' value='Entrar' class='button' onclick="return validarForm('Email')"/>
				</center>
			</div>
			<div class='large-12 columns'>
				<center>
				<a href='registar.php' class='button tiny'>Novo Utilizador</a>
				</center>
			</div>
			<div class='large-12 columns'>
				<center>
				<label><a href='#' onclick="esconderMostrar('divForgotPwd')">Esqueceu-se da password</a></label>
				</center>
			</div>
		</div>
		</form>
  </div>
  <div class="large-4 columns">&nbsp;</div>
</div>

<div class="row" style='position:relative; top:15%; display:none' id='divForgotPwd'>
  <div class="large-4 large-centered columns">
		<form method='post' action='login.php'>
		<div class='row panel'>
			<div class='large-12 columns'>
				<input type='text' placeholder='E-mail' name='Email' id='Email2' required/>
			</div>
			<div class='large-12 columns'>
				<center>
					<input type='submit' name='submit' value='Recuperar' class='button' onclick="return validarForm('Email2')"/>
					<br/>
					<label><a href='#' onclick="esconderMostrar('divLogin')">Voltar</a></label>
				</center>
			</div>
		</div>
		</form>
  </div>  
</div>

