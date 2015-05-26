<?php
	session_start();
	session_name('diarioFCT');
	if (!isset($_SESSION['idUser']) || $_SESSION['idTipo'] == 2) 
		echo "<script>window.location='index.php';</script>";
	
	
	
	
	
	
?>
<html>
	<head>
		<title>GPSI | Turma</title>
		<?php include_once('head.php'); ?>
		<script>
			function aparecerPainel(id,numeroColunas){
				for(var i = 1; numeroColunas > i; i++){
					idI = 'd_'+i;
					if(idI == id){
						document.getElementById(idI).style.display = 'block';						
					}else{
						var nova = document.getElementById(idI);
						if (nova != null){
						document.getElementById(idI).style.display = 'none';
						}
					}
				}
				
				
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
					<form action='turmas.php' method='post'>	
						<div class='large-2 columns'>
							<select name='curso'>
								<option value='3' <?php if(isset($_POST['curso']) && $_POST['curso'] == 3) echo "selected"; ?>>11ºASC</option>
								<option value='1' <?php if(isset($_POST['curso']) && $_POST['curso'] == 1) echo "selected"; ?>>11ºGPSI</option>
								<option value='2' <?php if(isset($_POST['curso']) && $_POST['curso'] == 2) echo "selected"; ?>>11ºHSTA</option>		
								<option value='2' <?php if(isset($_POST['curso']) && $_POST['curso'] == 4) echo "selected"; ?>>12ºGPSI</option>	
								<option value='2' <?php if(isset($_POST['curso']) && $_POST['curso'] == 5) echo "selected"; ?>>12ºASC</option>	
							</select>
						</div>
						<div class='large-2 columns'>	
								<input type='submit' name='submit' class='tiny button' value='Pesquisar'/>
						</div>
					</form>
				</div>
				
				
			
<br/>
					<?php
						if (isset($_POST['curso'])){
							include_once('DataAccess.php');
							$da = new DataAccess();
							
							
							
							
							$res = $da->getStats($_POST['curso']);
							$numeroAlunos = $da->getUtilizadores();
							$resA = mysql_fetch_object($numeroAlunos);
							
						
									
							 //mudar valor para onumero de colunas na tabela utilizadoresepor no ONCLICK
							echo "<div class='row'>";
								echo "<div class='large-2 columns'>";
									echo "<b>Nome</b>";
								echo "</div>";
								echo "<div class='large-8 columns'>";
										echo "&nbsp";
									echo "</div>";
							echo "</div>";
							while ($row = mysql_fetch_object($res)){
								echo "<div class='row'>";
									echo "<div class='large-2 columns'>";
										echo "<a "; ?> onclick="aparecerPainel('d_<?php echo $row->id; ?>', <?php echo $resA->m + 1; ?>)"> <?php 
										echo "$row->nome";
										echo "</a>";
									echo "</div>";
									
									echo "<div class='large-8 columns' style='display: none' id='d_$row->id' name='form' >
											<div class='row' style='position:relative; z-index: +1; padding-top:3%' >
												<center> Editar Aluno: $row->nome </center>
													<div class='large-4 columns'>&nbsp;</div>
													<div class='large-4 columns'>
														<form method='post' action='turmas.php' >
														<div class='row panel'>
															<div class='large-12 columns'>
																<input type='text' placeholder='Nome' name='Nome' title='Insira um nome.' required/>
															</div>
															<div class='large-12 columns'>
																<input type='email' name='email' id='email' placeholder='E-mail' required/>
															</div>
															<div class='large-12 columns'>
																<input type='password' placeholder='Password' name='Password'  required/>
															</div>
															
															<div class='large-12 columns'>
															<select name='curso'>
																<option value='3'"; if(isset($_POST['curso']) && $_POST['curso'] == 3) echo 'selected'; echo">11ºASC</option>
																<option value='1'"; if(isset($_POST['curso']) && $_POST['curso'] == 1) echo 'selected'; echo">11ºGPSI</option>
																<option value='2'"; if(isset($_POST['curso']) && $_POST['curso'] == 2) echo 'selected'; echo">11ºHSTA</option>		
																<option value='4'"; if(isset($_POST['curso']) && $_POST['curso'] == 4) echo 'selected'; echo">12ºGPSI</option>	
																<option value='5'"; if(isset($_POST['curso']) && $_POST['curso'] == 5) echo 'selected'; echo">12ºASC</option>	
															</select>
															</div>
																<div class='large-4 large-centered columns'>
																	<input type='submit' value='Editar' class='button tiny' />
														</div>
														</div>
														</form>
												  </div>
												<div class='large-4 columns'>&nbsp;</div>
												</div>
										  </div>	";			
									
									
									
									echo "<div class='large-2 columns'>";
										echo "&nbsp";
									echo "</div>";
								echo "</div>";
								
							}
							
							
							
							

					if (isset($_POST['email'])){

					$id = $row->id;
					$nome = $_POST['Nome'];
					$email = $_POST['email'];
					$pwd = md5($_POST['Password']); 
					$turma = $_POST['curso'];
					include_once('DataAccess.php');
					$da = new DataAccess();
					$da->editarAluno($id, $nome, $email, $password, $turma);
					echo "<script>alert('Alterações efetuadas com sucesso!');window.location='turmas.php';</script>";
		
						
					}	
							
						}
						
						
				
					
					
					
					
					
					
						/*				
						if (isset($_POST['Nome'])){
						$id = $row->id;
						$nome = $_POST['Nome'];
						$email = $_POST['email'];
						$password = $_POST['Password'];
						$turma = $_POST['curso'];
						$da->editarAluno($id, $nome, $email, md5($password), $turma);
						echo "<script>alert('Alterações efetuadas com sucesso!');</script>";
				}
						
					*/	
						
		
	
	
						
				?>
				</div>
		</div>
				<?php include_once('footer.php');?>
			
		<br/>
		
		
		
		<?php include_once('importarScript.php'); ?>
	</body>
</html>