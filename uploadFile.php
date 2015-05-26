<?php
class uploadFile{
	function upload($ficheiro,$idUser){
		$target_dir = "ficheiros/";
		$target_file = $target_dir . $idUser."_".basename($ficheiro["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// Check if image file is a actual image or fake image
		/*
		$check = getimagesize($ficheiro["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "<script>alert('File is not an image.')</script>";
			$uploadOk = 0;
		}*/
		
		// Check file size
		if ($ficheiro["size"] > 8388608) {
			echo "<script>alert('Máximo de 8Mb por ficheiro')</script>";
			$uploadOk = 0;
		}
		
		// Allow certain file formats
		$imageFileType = strtolower($imageFileType);
		//echo "<script>alert('$imageFileType')</script>";
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"  && 
		$imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf" && $imageFileType != "rar" && $imageFileType != "zip" && $imageFileType != "tif") {
			echo "<script>alert('Tipo de ficheiro inválido')</script>";
			$uploadOk = 0;
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			//echo "Sorry, your file was not uploaded.";
			return -1;
		} else {
			if (move_uploaded_file($ficheiro["tmp_name"], $target_file)) {
				return 1;
				//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				//echo "Sorry, there was an error uploading your file.";
				return -1;
			}
		}
		/*fim de upload*/
	}
}
?>