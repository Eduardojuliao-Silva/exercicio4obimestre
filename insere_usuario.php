<?php
	
	include("conexao.php");
	
	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$sexo = $_POST["sexo"];
	$cidade = $_POST["cod_cidade"];
	$estado = $_POST["cod_estado"];
	$salario = $_POST["salario"];
	
	$insercao = "INSERT INTO cadastro VALUES('NULL', '$nome', '$email', '$sexo', '$salario', '$cidade', '$estado')";

	mysqli_query($conexao,$insercao)
		or die("0");
	
	echo "1";
	
?>