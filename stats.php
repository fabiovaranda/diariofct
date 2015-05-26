<?php
	session_start();
	session_name('diarioFCT');
	if (!isset($_SESSION['idUser']) || $_SESSION['idTipo'] == 2) 
		echo "<script>window.location='index.php';</script>";
	
?>
<html>
	<head>
		<title>GPSI | Estatísticas</title>
		<?php include_once('head.php'); ?>
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
					<form action='stats.php' method='post'>	
						<div class='large-2 columns'>
							<select name='curso'>
								<option value='3' <?php if(isset($_POST['curso']) && $_POST['curso'] == 3) echo "selected"; ?>>11ºASC</option>
								<option value='1' <?php if(isset($_POST['curso']) && $_POST['curso'] == 1) echo "selected"; ?>>11ºGPSI</option>
								<option value='2' <?php if(isset($_POST['curso']) && $_POST['curso'] == 2) echo "selected"; ?>>11ºHSTA</option>								
							</select>
						</div>
						<div class='large-2 columns'>	
								<input type='submit' name='submit' class='tiny button' value='Pesquisar'/>
						</div>
					</form>
				</div>
					<?php
						if (isset($_POST['curso'])){
							include_once('DataAccess.php');
							$da = new DataAccess();
							$res = $da->getStats($_POST['curso']);
							
							$contaRegistos = 0;
							
							echo "<div class='row'>";
								echo "<div class='large-2 columns'>";
									echo "<b>Nome</b>";
								echo "</div>";
								echo "<div class='large-2 columns'><center>";
									echo "<b>N.º Registos</b>";
								echo "</center></div>";
								echo "<div class='large-8 columns'>";
										echo "&nbsp";
									echo "</div>";
							echo "</div>";
							while ($row = mysql_fetch_object($res)){
								echo "<div class='row'>";
									echo "<div class='large-2 columns'>";
										echo "$row->nome";
									echo "</div>";
									echo "<div class='large-2 columns'><center>";
										echo "$row->conta";
									echo "</center></div>";
									echo "<div class='large-8 columns'>";
										echo "&nbsp";
									echo "</div>";
								echo "</div>";
								$contaRegistos += $row->conta;
							}
							
							echo "<div style='position:absolute; left:50%; top:50px'>";
								
									echo "<b>".mysql_num_rows($res)." alunos | $contaRegistos registos diários</b>";
								
							echo "</div>";	
						}
				?>
				</div>
		</div>
				<?php include_once('footer.php');?>
			
		
		
		<?php include_once('importarScript.php'); ?>
	</body>
</html>