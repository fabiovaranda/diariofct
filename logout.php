<?php
	session_start();
	session_name('diarioFCT');
	session_destroy();
	echo "<script>window.location='index.php'</script>";
?>