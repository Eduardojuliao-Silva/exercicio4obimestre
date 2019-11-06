<?php
	
	include("conexao.php");
	
	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$sexo = $_POST["sexo"];
	$cidade = $_POST["cod_cidade"];
	$salario = $_POST["salario"];
	
	$insercao = "INSERT INTO cadastro VALUES('NULL', '$nome', '$email', '$sexo', '$cidade', '$salario')";

	mysqli_query($conexao,$insercao)
		or die("0");
	
	echo "1";
	
?>