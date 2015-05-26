<?php
	session_start();
	session_name('diarioFCT');
	if (!isset($_SESSION['idUser']) || $_SESSION['idTipo'] == 2) 
		echo "<script>window.location='index.php';</script>";
	
?>
<html>
	<head>
		<title>GPSI | Início</title>
		<?php include_once('head.php'); ?>
		<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
		<script>tinymce.init({selector:'textarea'});</script>
		<script>
			function confirmar(){
				return confirm('Tem a certeza que deseja eliminar?');
			}
		</script>
	</head>
	<body>
		<?php include_once('topBar.php'); 
		if ($_SESSION['idTipo'] == 1) include_once('topBarAdmin.php');
		?>
		
		<div class='row'>
			<div class='large-12 columns'>
				<div class='row panel' style='position:relative; margin-top:2%'>
					<form action='inicioAdmin.php' method='post'>	
						<div class='large-2 columns'>
							<select name='curso'>
								<option value='3' <?php if(isset($_POST['curso']) && $_POST['curso'] == 3) echo "selected"; ?>>11ºASC</option>
								<option value='1' <?php if(isset($_POST['curso']) && $_POST['curso'] == 1) echo "selected"; ?>>11ºGPSI</option>
								<option value='2' <?php if(isset($_POST['curso']) && $_POST['curso'] == 2) echo "selected"; ?>>11ºHSTA</option>	
								<option value='4' <?php if(isset($_POST['curso']) && $_POST['curso'] == 4) echo "selected"; ?>>12ºGPSI</option>	
								<option value='5' <?php if(isset($_POST['curso']) && $_POST['curso'] == 5) echo "selected"; ?>>12ºASC</option>	
							</select>
						</div>
						<div class='large-8 columns'>
							<input type='text' name='aluno' placeholder='aluno' value='<?php if(isset($_POST['aluno'])) echo $_POST['aluno'];  ?>'/>
						</div>
						<div class='large-2 columns'>	
								<input type='submit' name='submit' class='tiny button' value='Pesquisar'/>
						</div>
					</form>
				</div>
					<?php
						include_once('DataAccess.php');
						$da = new DataAccess();
						if (isset($_POST['submit'])){
							$aluno = $_POST['aluno'];
							$curso = $_POST['curso'];
							$res = $da->PesquisarRegistos($aluno, $curso);
						}else
							$res = $da->getUltimos10Registos();
						
						if (mysql_num_rows($res)>0){
							echo "<div class='row panel' >
									  <div class='large-2 columns'>
											<b>Aluno</b>
									  </div>
									  <div class='large-2 columns'>
											<b>Data</b>
									  </div>
									  <div class='large-8 columns'>
											<b>Texto</b>
									  </div>
									  
								  </div>";
						}
						$conta = 0;
						while($row = mysql_fetch_object($res)){
							$conta++;
							if ($conta % 2 == 0)
								echo "<div class='row panel' style='background-color:#A9E6FF'>";
							else
								echo "<div class='row panel'>";
								echo "
									  <div class='large-2 columns'>
											<label>$row->nome</label>
									  </div>
									  <div class='large-2 columns'>
											<label>$row->dia</label>
									  </div>
									  <div class='large-8 columns'>						
											<label>$row->diario</label>
							          </div>";
									  
								$resFD = $da->getFicheirosDiario($row->id);
								 if(mysql_num_rows($resFD)>0){
									while($rowFD = mysql_fetch_object($resFD)){	
										
										echo "<div class='row'><div class='large-2 columns'></div>";
										echo "<div class='large-10 columns'>
												<a href='ficheiros/$rowFD->ficheiro' target='_blank'>
												<img src='foundation/img/file.png' width='24px'/>
												$rowFD->ficheiro</a>
											</div></div>";
									}										
								 }

								
								echo"</div>"; //fechar row
						}
					?>
				
				</div>
		</div>
		
		
		
				<?php include_once('footer.php');?>
			
		
		
		<?php include_once('importarScript.php'); ?>
	</body>
</html>