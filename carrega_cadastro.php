<?php
	
	header ("Content-Type: Application/json");
	
	include("conexao.php");
	
	$p = $_POST["pg"];
		
	$sql = "SELECT id_cadastro, ca.nome as nome, email, sexo, salario, ci.nome as cidade, e.uf as estado FROM cadastro ca
	INNER JOIN cidade ci ON ca.cod_cidade=ci.id_cidade	
	INNER JOIN estado e ON ci.cod_estado=e.id_estado";
	
	if(isset($_POST["nome_filtro"])){
		$nome = $_POST["nome_filtro"];
		$sql .= " WHERE nome LIKE '%$nome%'";
	}
	
	$sql .= " LIMIT $p,5";
	
	$resultado = mysqli_query($conexao,$sql) or die ("Erro." . mysqli_query($conexao));
	
	while ($linha = mysqli_fetch_assoc($resultado)){
		$matriz[] = $linha;
	}
	
	echo json_encode($matriz);
	
?>