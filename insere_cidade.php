<?php
	
	include("conexao.php");
	
	$nome = $_POST["nome"];
	$cod_estado = $_POST["cod_estado"];
	
	$insercao = "INSERT INTO cidade VALUES('NULL', '$nome', '$cod_estado')";

	mysqli_query($conexao,$insercao)
		or die("0");
	
	echo "1";
	
?>