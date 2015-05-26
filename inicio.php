<?php
	session_start();
	session_name('diarioFCT');
	if (!isset($_SESSION['idUser']))
		echo "<script>window.location='index.php';</script>";
		
	if (isset($_POST['submit'])){
		$diario = $_POST['diario'];
		$idUser = $_SESSION['idUser'];
		
		include_once('DataAccess.php');
		include_once('uploadFile.php');
		$da = new DataAccess();
		$uf = new uploadFile();
		$idDiario = $da->inserirRegistoDiario($diario, $idUser);
		//echo "idDiario>".$idDiario;
		$erro = false;
		//echo "<script>alert('".$_FILES['file1']."')</script>";
		if ($_FILES['file1']['name'] != ""){
			$ok = $uf->upload($_FILES['file1'],$idUser);
			if ($ok == -1)
				$erro = true;
			else
				$da->inserirFicheiro($idUser."_".$_FILES['file1']['name'], $idDiario);
		}
		if ($_FILES['file2']['name'] != ""){
			$ok = $uf->upload($_FILES['file2'],$idUser);
			if ($ok == -1)
				$erro = true;
			else
				$da->inserirFicheiro($idUser."_".$_FILES['file2']['name'], $idDiario);
		}
		if (!$erro)
			echo "<script>alert('Registo diário inserido com sucesso');</script>";
		else
			echo "<script>alert('Registo diário inserido com sucesso, no entanto houve um erro na importação dos ficheiros');</script>";
			
	}else{
		if(isset($_POST['submitEditar'])){
			$idDiario =$_POST['idDiario'];
			$texto = $_POST['texto'];
			include_once('DataAccess.php');
			$da = new DataAccess();
			$da->updateRegistoDiario($idDiario, $texto);
			echo "<script>alert('Registo editado com sucesso');</script>";
		}else
			if(isset($_GET['el'])){
				$id = $_GET['el'];
				include_once('DataAccess.php');
				$da = new DataAccess();
				$da->eliminarRegisto($id);
				echo "<script>alert('Registo diário eliminado com sucesso');</script>";
			}
	}
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
			function validarForm(){
				if (tinyMCE.get('diario').getContent() == ""){
					alert('Registo diário em branco!');
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
				<div class='row' style='position:relative; margin-top:2%'>	
					<div data-alert class="alert-box alert round">
					  <center><h1>Atenção aos erros ortográficos!!</h1></center>
					</div>
				</div>
				<div class='row' style='position:relative; margin-top:2%'>	
					<div class='large-12 panel columns'>
						<form action='inicio.php' method='post' enctype="multipart/form-data">
							<div class='row'>
								<div class='large-12 columns'>
									<label>Descreve, resumidamente, o teu trabalho no dia de hoje em FCT</label>
									<textarea name='diario' id='diario' height='100px' tabindex='900'></textarea>
								</div>
								<div class='large-6 columns' >
									<label>Importar relatório<input type='file' name='file1' class='button tiny' style='width:100%' /></label>
								</div>
								<div class='large-6 columns' >
									<label>Importar outro ficheiro<input type='file' name='file2' class='button tiny' style='width:100%' /></label>
								</div>
							</div>
							<div class='row'>
								<div class='large-12 columns'>							
									<center>
										<input type='submit' name='submit' class='small button' value='Enviar' onclick='return validarForm()'/>
									</center>
								</div>
							</div>
						</form>
					</div>
				</div>
					<?php
						include_once('DataAccess.php');
						$da = new DataAccess();
						$idUser = $_SESSION['idUser'];
						$res = $da->getRegistos($idUser);
						if (mysql_num_rows($res)>0){
							echo "<div class='row panel' >
									  <div class='large-2 columns'>
											<b>Data</b>
									  </div>
									  <div class='large-8 columns'>
											<b>Texto</b>
									  </div>
									  
								  </div>";
						}
						while($row = mysql_fetch_object($res)){
							if (isset($_GET['e']) && $_GET['e'] == $row->id)
								echo "<div class='row panel' id='div_".$row->id."' autofocus tabindex='0'>";
							else
								echo "<div class='row panel' id='div_".$row->id."'>";
							
								if (isset($_GET['e']) && $_GET['e'] == $row->id)
									echo "<form method='post' action='inicio.php'>";							
								
								echo "
									  <div class='large-2 columns'>
											<label>$row->dia</label>
									  </div>
									  <div class='large-8 columns'>";
									  if (isset($_GET['e']) && $_GET['e'] == $row->id)
									  {
										echo "<input type='hidden' name='idDiario' value='".$_GET['e']."'/>
											  <textarea name='texto' id='txt_".$row->id."'>$row->diario</textarea>";
									  }else
											echo "<label>$row->diario</label>";
							    echo "</div>";
								if (isset($_GET['e']) && $_GET['e'] == $row->id)
									echo "  <div class='large-2 columns'>
												<input type='submit' name='submitEditar' id='btEditar_$row->id' class='button' value='Editar' />
									        </div>";			
								else
									echo "
										  <div class='large-1 columns'>
												<a href='inicio.php?e=$row->id'>
													<img src='foundation/img/Edit.png' style='width:32px' title='Editar'>
												</a>
										  </div>
										  <div class='large-1 columns'>
												<a href='inicio.php?el=$row->id'  onclick='return confirmar()'>
													<img src='foundation/img/Remove.png' style='width:32px' title='Eliminar'>
												</a>
										  </div>";
									  
									  if (isset($_GET['e']) && $_GET['e'] == $row->id)
										echo "</form>";	
										
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
							echo " </div>"; //fechar row
						}
						
						if (isset($_GET['e'])){
							echo "<script>
								document.getElementById('div_".$_GET['e']."').scrollIntoView();
								document.getElementById('div_".$_GET['e']."').focus();
								
							</script>";
						}
							
					?>
				</div>
		</div>
		<?php include_once('footer.php');?>
		<?php include_once('importarScript.php'); ?>
	</body>
</html>