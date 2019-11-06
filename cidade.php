<!DOCTYPE html>

<html lang = "pt-BR">
	
	<head>
		
		<title>Cadastro</title>
		<meta charset = "UTF-8" />
		<script src= "jquery-3.4.1.min.js"></script>
		<script>
			
			var id = null;
			
			$(function(){
				
				paginacao(0);
				
				$(document).on("click",".alterar",function(){
					id = $(this).attr("value");
					$.ajax({
						url: "carrega_cidade_alterar.php",
						type: "post",
						data: {id: id},
						success: function(vetor){
							$("input[name='nome']").val(vetor.nome);
							$("select[name='cod_estado']").val(vetor.cod_estado);
							$(".cadastrar").attr("class","alteracao");
							$(".alteracao").val("Alterar Cidade");
						}
					});
				});
				
				function paginacao(p) {
					$.ajax ({
						url: "carrega_cidade.php",
						type: "post",
						data: {pg: p},
						success: function(matriz){
							$("#identificador").html("");
							for(i=0;i<matriz.length;i++){
								linha = "<tr>";
								linha += "<td class = 'nome'>" + matriz[i].nome + "</td>";
								linha += "<td class = 'cod_estado'>" + matriz[i].cod_estado + "</td>";
								linha += "<td><button type = 'button' id = 'nome_alterar' class = 'alterar' value ='" + matriz[i].id_cidade + "'>Alterar</button> | <button type = 'button' class ='remover' value ='" + matriz[i].
								id_cidade + "'>Remover</button></td>";
								linha += "</tr>";
								$("#identificador").append(linha);
							}
						}
					});
				}
				
				$(".pg").click(function(){
					p = $(this).val();
					p = (p-1)*5;
					paginacao(p);
				});
				
				$(document).on("click",".cadastrar",function(){
					$.ajax({ 
						url: "insere_cidade.php",
						type: "post",
						data: {nome:$("input[name='nome']").val(), email:$("input[name='email']").val(), sexo:$("input[name='sexo']:checked").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Cadastro efetuado!");
							}else {
								console.log(data);
							}
						}
					});
				});
				$(document).on("click",".alteracao",function(){
					$.ajax({ 
						url: "altera.php",
						type: "post",
						data: {id: id, nome:$("input[name='nome']").val(), email:$("input[name='email']").val(), sexo:$("input[name='sexo']:checked").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Alteração efetuada!");
								paginacao(0);
								$("input[name='nome']").val("");
								$("input[name='email']").val("");
								$("input[name='sexo'][value='M']").attr("checked",false)
								$("input[name='sexo'][value='F']").attr("checked",false)
								$(".alteracao").attr("class","cadastrar");
								$(".cadastrar").val("Cadastrar");
							}else {
								console.log(data);
							}
						}
					});
				});
				
				$(document).on("click",".nome",function(){
					td = $(this);
					nome = td.html();
					td.html("<input type = 'text' name = 'nome' value = '" + nome + "' />");
					td.attr("class","nome_alterar");
				});
				
				$(document).on("blur",".nome_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {coluna: 'nome', valor: $("#nome_alterar").val(), id: id_linha},
						success: function(){
							nome = $("#nome_alterar").val();
							td.html(nome);
							td.attr("class","nome");
						}
					});
				});
				
			});
		
		</script>
		
	</head>
	
	<body>
	<?php
		include("conexao.php");
	
		$consulta_estado = "SELECT * FROM estado";
		$resultado_estado = mysqli_query($conexao,$consulta_estado) or die ("ERRO");
	?>	
		<h3>Cadastro de Cidades</h3>
		
		<form>
			
			Nome: <input type = "text" name = "nome" placeholder = "Nome..." /> <br /><br />
			Estado: <select name = 'cod_estado'>
						<?php
							while($linha=mysqli_fetch_assoc($resultado_estado)){
								echo '<option value = "'. $linha["id_estado"] .'">'.$linha["nome"] .'</option>';
							}
						?>
						</select>
			
			<input type = "button" class = "cadastrar" value = "Cadastrar" />
			
		</form>
		
		<br />
		
		<div id = "resultado"></div>
		
		<br />
		
		<h3>Cidades</h3>
		
		<form name='filtro'>
			<input type="text" name="nome_filtro" placeholder="filtrar por nome..." />
			
			<button type = "button" id="filtrar">Filtrar</button>
		</form>
		<br />
		
		<table border = '1'>
						
			<thead>
				<tr>
					<th>Nome</th>
					<th>Estado</th>
					<th>Ação</th>
				</tr>
			 </thead>
		
			<tbody id="identificador"></tbody>
					
		</table>
		<br /><br />
		
		<div id="paginacao">
		<?php
			include("paginacao_cidade.php");
		?>
		</div>
		
	</body>
	
</html>