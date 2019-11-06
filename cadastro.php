<!DOCTYPE html>
<html lang = "pt-BR">
	<head>
		<title>Cadastro</title>
		<meta charset = "UTF-8" />
		<script src= "jquery-3.4.1.min.js"></script>
		<script>
			
			var id = null;
			var filtro = null;
			
			$(function(){
				
				paginacao(0);
				// PAGINAÇÃO
				function paginacao(p) {
					$.ajax ({
						url: "carrega_cadastro.php",
						type: "post",
						data: {pg: p, nome_filtro: filtro},
						success: function(matriz){
							$("#identificador").html("");
							for(i=0;i<matriz.length;i++){
								linha = "<tr>";
								linha += "<td class = 'nome'>" + matriz[i].nome + "</td>";
								linha += "<td class = 'email'>" + matriz[i].email + "</td>";
								linha += "<td class = 'sexo'>" + matriz[i].sexo + "</td>";
								linha += "<td class = 'cod_cidade'>" + matriz[i].cod_cidade + "</td>";
								linha += "<td class = 'salario'>" + matriz[i].salario + "</td>";
								linha += "<td><button type = 'button' id = 'nome_alterar' class = 'alterar' value ='" + matriz[i].id_cadastro + "'>Alterar</button> | <button type = 'button' class ='remover' value ='" + matriz[i].id_cadastro + "'>Remover</button></td>";
								linha += "</tr>";
								$("#identificador").append(linha);
							}
						}
					});
				}
				
				// FILTRAR
				$("#filtrar").click(function(){
					$.ajax({
						url:"paginacao_cadastro.php",
						type:"post",
						data:{
								nome_filtro: $("input[name='nome_filtro']").val()
						},
						success: function(d){
							console.log(d);
							filtro = $("input[name='nome_filtro']").val()
							paginacao(0);
							
						}
					});
				});
				
				
				
				$(document).on("click",".alterar",function(){
					id = $(this).attr("value");
					$.ajax({
						url: "carrega_cadastro_alterar.php",
						type: "post",
						data: {id: id},
						success: function(vetor){
							$("input[name='nome']").val(vetor.nome);
							$("input[name='email']").val(vetor.email);
							if(vetor.sexo=='F'){
								$("input[name='sexo'][value='M']").attr("checked",false);
								$("input[name='sexo'][value='F']").attr("checked",true);
							}else {
								$("input[name='sexo'][value='F']").attr("checked",false);
								$("input[name='sexo'][value='M']").attr("checked",true);
							}
							$("input[name='salario']").val(vetor.salario);
							$("select[name='cod_cidade']").val(vetor.cod_cidade);
							$(".cadastrar").attr("class","alteracao");
							$(".alteracao").val("Alterar Cadastro");
						}
					});
				});
				
				$(document).on("click",".pg", function(){
					p = $(this).val();
					p = (p-1)*5;
					paginacao(p);
				});
				
				// INSERIR
				$(document).on("click",".cadastrar",function(){
					alert($("select[name='cod_cidade']").val());
					$.ajax({ 
						url: "insere_usuario.php",
						type: "post",
						data: {
								nome:$("input[name='nome']").val(), 
								email:$("input[name='email']").val(), 
								sexo:$("input[name='sexo']:checked").val(),
								salario:$("input[name='salario']").val(),
								cidade:$("select[name='cod_cidade']").val()
						},
						success: function(data){
							if(data=='1'){
								$("#resultado").html("Cadastro efetuado!");
							}else {
								console.log(data);
							}
						}
					});
				});
				
				// ALTERAR
				$(document).on("click",".alteracao",function(){
					$.ajax({ 
						url: "altera_cadastro.php",
						type: "post",
						data: {
								id: id, 
								nome:$("input[name='nome']").val(), 
								email:$("input[name='email']").val(), 
								sexo:$("input[name='sexo']:checked").val(),	
								salario:$("input[name='salario']").val(),
								cidade:$("select[name='cod_cidade']").val()
						},
						success: function(data){
							if(data==1){
								$("#resultado").html("Alteração efetuada!");
								paginacao(0);
								$("input[name='nome']").val("");
								$("input[name='email']").val("");
								$("input[name='sexo'][value='M']").attr("checked",false)
								$("input[name='sexo'][value='F']").attr("checked",false)
								$("input[name='salario']").val("");
								$("select[name='cod_cidade']").val("");
								$(".alteracao").attr("class","cadastrar");
								$(".cadastrar").val("Cadastrar");
							}else {
								console.log(data);
							}
						}
					});
				});
				////////////////////////////////////////////////////////////////////
				
				///////////////////////////INLINE NOME /////////////////////////////
				$(document).on("click",".nome",function(){
					td = $(this);
					nome = td.html();
					td.html("<input type = 'text' id = 'nome_alterar' value = '" + nome + "' />");
					td.attr("class","nome_alterar");
					$("#nome").focus();
				});
							
				$(document).on("blur",".nome_alterar",function(){
					console.log("teste");
					console.log("teste");
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {
								tabela: 'cadastro', 
								coluna: 'nome',
								valor: $("#nome").val(),
								id: id_linha},
						success: function(data){
							console.log(data);
							nome = $("#nome").val();
							td.html(nome);
							td.attr("class","nome");
						}
					});
				});
				
				////////////////////////////////////////////////////////////////////
				
				///////////////////////////INLINE SEXO /////////////////////////////
				$(document).on("click",".sexo",function(){
					td = $(this);
					sexo = td.html();
					sexo_input = "<input type = 'radio' class = 'alterar_sexo' name='sexo' value = 'M' /> M";
					sexo_input += "<input type = 'radio' class = 'alterar_sexo' name='sexo' value = 'F' /> F";
					td.html(sexo_input);
					if(sexo=="M"){
						$(".alterar_sexo[value='M']").prop("checked",true);
						$(".alterar_sexo[value='F']").prop("checked",false);
					}
					else{
						$(".alterar_sexo[value='M']").prop("checked",true);
						$(".alterar_sexo[value='F']").prop("checked",false);
					}
					td.attr("class","sexo_alterar");
				});
				
				$(document).on("blur",".sexo_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {
								tabela: 'cadastro', 
								coluna: 'sexo',
								valor: $(".alterar_sexo:checked").val(),
								id: id_linha
						},
						success: function(data){
							sexo = $(".alterar_sexo:checked").val();
							td.html(sexo);
							td.attr("class","sexo");
						}
					});
				});
				//////////////////////////////////////////////////////////////////////
				
				///////////////////////////INLINE CIDADE /////////////////////////////
				$(document).on("click",".cidade",function(){
					td = $(this);
					cidade = td.html();
					
					select = "<select id='cidade_alterar'>";
					select += $("select[name='cod_cidade']").html();
					select += "</select>";
					
					td.html(select);
					valor = $("option:contains('"+cidade+"')").val();
					$("#cod_cidade").val(valor);
					$("#cod_cidade").focus();
					
					td.attr("class","cidade_alterar");
				});
				
				$(document).on("blur",".cidade_alterar",function(){
					td = $(this);
					id_linha = $(this).closest("tr").find("button").val();
					$.ajax({
						url: "altera_inline.php",
						type: "post",
						data: {
								tabela: 'cadastro', 
								coluna: 'cod_cidade',
								valor: $("#cidade_alterar").val(),
								id: id_linha
						},
						success: function(r){
							console.log(r);
							cod_cidade = $("#cidade_alterar").val();
							cidade_estado = $("#cidade_alterar").find("option[value='" + cod_cidade + "']").html();
							cidade_estado = cidade_estado.split("/");
							cidade = cidade_estado[0];
							estado = cidade_estado[1];
							td.closest("tr").find(".estado").html(estado);
							td.html(cidade);
							td.attr("class","cidade");
						}
					});
				});
			});
		
		</script>
	</head>
	
	<body>
	<?php
		include("conexao.php");
		
		$consulta_cidade = "SELECT * FROM cidade";
		$resultado_cidade = mysqli_query($conexao,$consulta_cidade) or die ("ERRO");
	?>	
		<h3>Cadastro de Pessoas</h3>
		<?php
		include("menu.html");
		?>
		<br />
		<br />
		<fieldset>
		<form>
			
			<input type = "text" name = "nome" placeholder = "Nome..." /> <br /><br />
			<input type = "email" name = "email" placeholder = "E-mail..." /><br /><br />
			Sexo: 
			M <input type = "radio" name = "sexo" value = "M" />
			F <input type = "radio" name = "sexo" value = "F" />
			<br /><br />
			<input type ="number" name="salario" placeholder="Salário..." min="0" step="0.01"/>
			<br /><br />
			Cidade: <select name = 'cod_cidade'>
					<option value="">::selecione a cidade</option>
						<?php
						include("conexao.php");
		
						$consulta_cidade = "SELECT * FROM cidade";
						$resultado_cidade = mysqli_query($conexao,$consulta_cidade) or die ("ERRO");
					
						while($linha=mysqli_fetch_assoc($resultado_cidade)){
								echo '<option value = "'. $linha["id_cidade"] .'">'.$linha["nome"] .'</option>';
							}
						?>
					</select>
			<br /><br />
			<input type = "button" class = "cadastrar" value = "Cadastrar" />
			
		</form>
		</fieldset>
		<br />
		
		<div id = "resultado"></div>
		
		<br />
		
		<h3>Cadastros</h3>
		
		<form name='filtro'>
			<input type="text" name="nome_filtro" placeholder="filtrar por nome..." />
			
			<button type = "button" id="filtrar">Filtrar</button>
		</form>
		<br />
		
		<table border = '1'>
						
			<thead>
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Sexo</th>
					<th>Salario</th>
					<th>Cidade</th>
					<th>Estado</th>
					<th>Ação</th>
				</tr>
			 </thead>
		
			<tbody id="identificador"></tbody>
					
		</table>
		<br /><br />
		
		<div id="paginacao">
		<?php
			
			include("paginacao_cadastro.php");
		?>
		</div>
		
	</body>
	
</html>