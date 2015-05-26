<?php
	session_start();
	session_name('diarioFCT');
	if (!isset($_SESSION['idUser']))
		echo "<script>window.location='index.php';</script>";
		
	if (isset($_POST['submit'])){
		$diario = $_POST['diario'];
		$idUser = $_SESSION['idUser'];
		include_once('DataAccess.php');
		$da = new DataAccess();
		$da->inserirRegistoDiario($diario, $idUser);
		echo "<script>alert('Registo diário inserido com sucesso');</script>";
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
		</script>
	</head>
	<body>
		<?php include_once('topBar.php'); ?>
		<div class='row'>
			<div class='large-12 columns'>
				<div class='row' style='position:relative; margin-top:2%'>	
					<div data-alert class="alert-box alert round">
					  <center><h1>Atenção aos erros ortográficos!!</h1></center>
					</div>
				</div>
				<div class='row' style='position:relative; margin-top:2%'>	
					<div class='large-12 panel columns'>
						<form action='inicio.php' method='post'>
							<br/>
							<label>Descreve, resumidamente, o teu trabalho no dia de hoje em FCT</label>
							<textarea name='diario' height='100px' tabindex='900'></textarea>
							<br/>
							<center>
								<input type='submit' name='submit' class='small button' value='Enviar'/>
							</center>
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