<script>
			function confirmarSair(){
				return confirm('Tem a certeza que deseja sair?');
			}
		</script>

<div class='row'>
	<div class='large-12 columns' style="background-image: url('foundation/img/logoGPSI2.jpg'); height:210px;">
		<div style='position:relative; left:97%; top:3%; height:32px; width:32px'>
			<?php
				session_start();
				session_name('diarioFCT');
				if ($_SESSION['idTipo'] == 1)
					$pag = "inicioAdmin.php";
				else
					$pag = "inicio.php";
				echo "<a href='$pag' class='item' style='height:32px; width:32px'>	";
			?>
			  
			  <img src="foundation/img/home.png" style='height:32px; width:32px' title='Início'/>
			  </a>
			  <br/>
			  <br/>
			  <a href='perfil.php' class='item' style='height:32px; width:32px'>	
				<img src="foundation/img/settings.png" style='height:32px; width:32px' title='Perfil'/>
			  </a>
			  <br/>
			  <br/>
			  <a href='logout.php' class='item' style='height:32px; width:32px' onclick='return confirmarSair()'>	
				<img src="foundation/img/logout.png" style='height:32px; width:32px' title='Sair'/>
			  </a>
		</div>
	</div>
</div>