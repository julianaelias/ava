<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_aluno.php");

$avaliacao = '';
$comentario = '';
$curso = '';
$titulo = '';
$data = '';
$inscricao = '';
$nota = '';

$inscricao = $_GET['inscricao'];
$curso = $_GET['curso'];


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						A.AVALIACAO, 
						A.COMENTARIO, 
						A.CURSO, 
						A.DATA, 
						A.INSCRICAO,
						A.NOTA,
						A.USUARIO,
						C.TITULO
						FROM AVALIACOES A
                        JOIN INSCRICOES I ON I.INSCRICAO = A.INSCRICAO 
						JOIN CURSOS C ON C.CURSO = I.CURSO
                        AND I.CURSO = A.CURSO 
                        AND I.USUARIO = A.USUARIO
						WHERE I.INSCRICAO = :INSCRICAO
						AND I.CURSO = :CURSO
						AND I.USUARIO = :USUARIO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
		
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$avaliacao = utf8_encode($valor['AVALIACAO']);
				$comentario= utf8_encode($valor['COMENTARIO']);
				$curso = utf8_encode($valor['CURSO']);
				$titulo = utf8_encode($valor['TITULO']);
				$inscricao = utf8_encode($valor['INSCRICAO']);
				$nota = utf8_encode($valor['NOTA']);
				$usuario = utf8_encode($valor['USUARIO']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	
		try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelectC = "SELECT
						C.CURSO,
						C.TITULO
						FROM CURSOS C
						WHERE C.CURSO = :CURSO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelectC);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultadosC = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultadosC)> 0){
			foreach($resultadosC as $valor){
				$cursoC = utf8_encode($valor['CURSO']);
				$tituloC = utf8_encode($valor['TITULO']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	
?>
<div id="tudo">
	<div class="container">
        <ol class="breadcrumb fundo" >
            <h1 class="tituloBreadcrumb">Avaliação do Curso</h1>
             <li><a  href="../usuario/area_aluno.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
              <li><a  href="../usuario/cursos/cursos.php">Cursos</a></li>
             <li class="active">Avaliação do Curso</li>
        </ol>
    </div>
   
    <div class="container" >
        <div class="panel panel-primary">
  			<div class="panel-heading">Avaliar Curso</div>
  			<div class="panel-body">
            
            	<div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <span style="color:#286090; font-weight:bold;">Curso em Avaliação: </span><?=$cursoC.' - '.$tituloC;?>
                    </div> 
                </div>  
            
            	<div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    
                    <input type="radio" name="nota" id="nota_1" value="1" <?php if($nota == 1){echo'checked="checked"';}?>/>
                    <label>Nota 1 <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span></label>
                    &nbsp;&nbsp;
                    <input type="radio" name="nota" id="nota_2" value="2" <?php if($nota == 2){echo'checked="checked"';}?>/>
                    <label>Nota 2 
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    </label>
                    &nbsp;&nbsp;
                    <input type="radio" name="nota" id="nota_3" value="3" <?php if($nota == 3){echo'checked="checked"';}?>/>
                    <label>Nota 3 
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    </label>
                    &nbsp;&nbsp;
                    <input type="radio" name="nota" id="nota_4" value="4" <?php if($nota == 4){echo'checked="checked"';}?>/>
                    <label>Nota 4 
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    </label>
                     &nbsp;&nbsp;
                    <input type="radio" name="nota" id="nota_5" value="5" <?php if($nota == 5){echo'checked="checked"';}?>/>
                    <label>Nota 5
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color:#FF0;"></span>
                    </label>        
                    </div>
                </div>
            	
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                            <textarea rows="4" cols="50" class="form-control" placeholder="Digite um comentário" 
                            name="comentario" id="comentario" style="resize: none;" ><?=$comentario;?></textarea>
                    </div>
                </div>
                <div class="row espaco" > 
                    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center !important;">
                        <input type="text" name="curso" id="curso" value="<?=$curso;?>" hidden="hidden"/>
                        <input type="text" name="inscricao" id="inscricao" value="<?=$inscricao;?>" hidden="hidden"/>
                        <input type="text" name="avaliacao" id="avaliacao" value="<?=$avaliacao;?>" hidden="hidden"/>
                        <input type="text" name="usuario" id="usuario" value="<?=$_SESSION['codigo'];?>" hidden="hidden"/>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="avaliar();">SALVAR</button>
	   				</div>
                </div>
  			</div>
		</div>
	</div>
<script>

	function avaliar(){
		
		if (! $("input[type='radio'][name='nota']").is(':checked') ){
		  alert("Por favor, selecione uma nota.");
		  return false;
		}
		
		if (confirm('Tem certeza que deseja avaliar o curso?')){
			
			if($("#nota_1").is(':checked')){
				var nota = $("#nota_1").val();
			}else if($("#nota_2").is(':checked')){
				var nota = $("#nota_2").val();
			}else if($("#nota_3").is(':checked')){
				var nota = $("#nota_3").val();
			}else if($("#nota_4").is(':checked')){
				var nota = $("#nota_4").val();
			}else if($("#nota_5").is(':checked')){
				var nota = $("#nota_5").val();
			}
		
		}
		
		var comentario = $("#comentario").val();
		var curso = $("#curso").val();
		var inscricao = $("#inscricao").val();
		var usuario = $("#usuario").val();
		var avaliacao = $("#avaliacao").val();
		
		
			$.ajax({
				url: 'salvar_avaliacao.php',
				type: 'post',
				datatype: 'text',
				data: {nota : nota, comentario : comentario, inscricao : inscricao, usuario : usuario, curso : curso, avaliacao : avaliacao},
	
				success: function(r)
				{
					rSplit = r.split('|');
					if (rSplit[0] == 1)
					{
						alert(rSplit[1]);
					}
					else if (rSplit[0] == 2)
					{
						alert(rSplit[1]);
					}
					else
					{
						alert(r);
					}
				
				}					
			});	
	}
</script>	

<?php
include_once("../../includes/rodape.php");
?>