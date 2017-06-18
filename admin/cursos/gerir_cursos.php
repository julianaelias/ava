<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_admin.php");

$curso = $_GET['curso'];
$titulo = '';
$descricao = '';
$categoria = '';
$professor = '';
$palavras = '';
$status = '';

if(!empty($curso)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						C.CURSO, 
						C.TITULO, 
						C.DESCRICAO, 
						C.CATEGORIA, 
						C.PROFESSOR,
						C.PALAVRAS,
						C.STATUS
						FROM CURSOS C
						WHERE C.STATUS = 1
						AND C.CURSO = :CURSO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		
		if(!empty($curso)){
			$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		}
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$curso = utf8_encode($valor['CURSO']);
				$titulo = utf8_encode($valor['TITULO']);
				$descricao = utf8_encode($valor['DESCRICAO']);
				$categoria = utf8_encode($valor['CATEGORIA']);
				$professor = utf8_encode($valor['PROFESSOR']);
				$palavras = utf8_encode($valor['PALAVRAS']);
				$status = utf8_encode($valor['STATUS']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}	
}
	if($categoria ==  1){
		$descCategoria = "ACADÊMICO";
	}else if($categoria == 2){
		$descCategoria = "EMPRESARIAL";
	}else if($categoria == 3){
		$descCategoria = "INFORMÁTICA";
	}else if($categoria == 4){	
		$descCategoria = "RELIGIOSIADE";
	}
?>
<div id="tudo">
	<div class="container">
        <ol class="breadcrumb fundo">
        	<h1 class="tituloBreadcrumb">Gerir Conteúdo</h1>
            <li><a  href="../admin/area_admin.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
            <li><a href="../admin/cursos/cursos.php">Cursos</a></li>
            <li class="active">Gerir Conteúdo</li>
		</ol>
         <div class="row">
  			<div class="col-md-12">
            	<h2 class="subTitulo">Gerir Curso: <span class="subTituloN2"><?=$curso.' - '.$titulo;?></span></h2>
            </div>
		</div>
        <div style="text-align:center; vertical-align:central;">
            <div class="btn-group btn-group-lg center" role="group" aria-label="..." >
              <button type="button" class="btn btn-primary" onClick="gerirUnidades(<?=$curso;?>);">Unidades</button>
              <button type="button" class="btn btn-primary" onClick="gerirMateriais(<?=$curso;?>);">Materiais</button>
              <button type="button" class="btn btn-primary" onClick="gerirAtividades(<?=$curso;?>);">Atividades</button>
              
            </div>
        </div>
	</div>
    <br/>
    <div id="conteudo"></div>
<script>

	function gerirUnidades(curso, unidade, pagina){	    
		
		$("#conteudo").html('');
		$.ajax({
			url: 'gerir_unidades.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso, unidade : unidade, pagina : pagina},
		
			success: function(r){			
				$("#conteudo").html(r);
			}			
		});	
	}
	
	function gerirMateriais(curso, unidade, material, pagina){	    
		
		$("#conteudo").html('');
		$.ajax({
			url: 'gerir_materiais.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso, unidade : unidade, material : material, pagina : pagina},
		
			success: function(r){			
				$("#conteudo").html(r);
			}			
		});	
	}
	
	function gerirAtividades(curso, unidade, atividade, pagina){	    
		
		$("#conteudo").html('');
		$.ajax({
			url: 'gerir_atividades.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso, unidade : unidade, atividade : atividade, pagina : pagina},
		
			success: function(r){			
				$("#conteudo").html(r);
			}			
		});	
	}
	
	function gerirQuestoes(curso, unidade, atividade, questao, controle){	
	
	
	   if(controle == 1){
		   if($("#tr_"+atividade).css('display') == 'none'){
			   $("#tr_"+atividade).show();
			   $("#td_"+atividade).show();
		   }else{
			   $("#tr_"+atividade).hide();
			   $("#td_"+atividade).hide();
		   }
	   }else{
		   
		   $("#tr_"+atividade).show();
		   $("#td_"+atividade).show();
		   
	   }
		
		$("#atividade_"+atividade).html('');
		$.ajax({
			url: 'gerir_questoes.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso, unidade : unidade, atividade : atividade, questao :questao, controle : controle},
		
			success: function(r){
										
				$("#atividade_"+atividade).html(r);
			}			
		});	
	}
	
</script>	

<?php
include_once("../../includes/rodape.php");
?>