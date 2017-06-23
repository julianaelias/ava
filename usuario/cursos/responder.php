<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_aluno.php");

if(isset($_GET['unidade'])){
	$unidadeQ = $_GET['unidade'];
}

if(isset($_GET['atividade'])){
	$atividadeQ = $_GET['atividade'];
}
if(isset($_GET['inscricao'])){
	$inscricaoQ = $_GET['inscricao'];
}
if(isset($_GET['curso'])){
	$cursoQ = $_GET['curso'];
}



?>

<div id="tudo">
	<div class="fundoTopo2">
    	<ol class="breadcrumb">
        	<h1 class="tituloBreadcrumb">Atividade Avaliativa</h1>
             <li><a  href="/ava/usuario/area_aluno.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
             <li><a href="/ava/usuario/cursos/cursos.php">Cursos</a></li>
             <li><a href="/ava/usuario/cursos/conteudo.php?inscricao=<?=$inscricaoQ;?>&curso=<?=$cursoQ;?>">Acessar Conteúdo</a></li>
            <li class="active">Atividade Avaliativa</li>
		</ol>
	</div>
    <div class="container">
        <div class="row">
  			<div class="col-md-12">
            	<h2 class="subTitulo">Atividade Avaliativa - <?=$atividadeQ;?></h2>
            </div>
		</div>

        <div class="panel panel-primary">
  			<div class="panel-heading" style="text-align:center;">Atividade</div>
  			<div class="panel-body"> 
            <form id="formAtividade_<?=$atividadeQ;?>" name="formAtividade_<?=$atividadeQ;?>" 
            enctype="multipart/form-data" method="post" 
            action="/ava/usuario/cursos/salvar_questoes.php"  style="margin: 0px; padding: 0px;"> 
                                                     
			 <?php 
			 
		 if(!empty($cursoQ) && !empty($unidadeQ) && !empty($atividadeQ) && !empty($inscricaoQ)){
                
                	$contador = 0;
                    try{
                        // instancia objeto PDO, conectando no mysql
                        $conexao = conn_mysql();
                        
                        // instrução SQL básica
                        $SQLSelect = "SELECT 
                                        C.CURSO, 
                                        U.UNIDADE,
                                        A.ATIVIDADE,
                                        Q.QUESTAO,
                                        Q.ENUNCIADO, 
                                        Q.ALTERNATIVA_1,
                                        Q.ALTERNATIVA_2,
                                        Q.ALTERNATIVA_3,
                                        Q.ALTERNATIVA_4,
                                        Q.ALTERNATIVA_CORRETA,
                                        Q.STATUS,
										A.DESCRICAO,
										A.TITULO,
										QU.ALTERNATIVA_MARCADA,
										QU.FINALIZADO,
										QU.NOTA_ATIVIDADE,
										QU.VALOR_ATIVIDADE
                                        FROM QUESTOES Q
                                        JOIN ATIVIDADES A ON A.ATIVIDADE = Q.ATIVIDADE
                                        JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE	
                                        JOIN CURSOS C ON C.CURSO = U.CURSO
										LEFT JOIN QUESTOES_USUARIOS QU ON QU.ATIVIDADE = Q.ATIVIDADE AND QU.QUESTAO = Q.QUESTAO
                                        WHERE C.STATUS = 1
                                        AND U.STATUS = 1
                                        AND Q.STATUS = 1
                                        AND C.CURSO = :CURSO
                                        AND U.UNIDADE = :UNIDADE
                                        AND A.ATIVIDADE = :ATIVIDADE";
                                        
                        //prepara a execução da sentença
                        $operacao = $conexao->prepare($SQLSelect);
                        
                        $operacao->bindParam(':CURSO', $cursoQ, PDO::PARAM_INT);
                        $operacao->bindParam(':UNIDADE', $unidadeQ, PDO::PARAM_INT);
                        $operacao->bindParam(':ATIVIDADE', $atividadeQ, PDO::PARAM_INT);
                        
                        $pesquisar = $operacao->execute();
                    
                        //captura TODOS os resultados obtidos
                        $resultados = $operacao->fetchAll();
                        
                        // fecha a conexão (os resultados já estão capturados)
                        $conexao = null;
                    
                        // se há resultados, os escreve em uma tabela
                        if (count($resultados)> 0){
                            foreach($resultados as $valor){
								$contador++;
								$alternativaMarcadaQ='';
                                
                                $cursoQ = utf8_encode($valor['CURSO']);
                                $unidadeQ = utf8_encode($valor['UNIDADE']);
                                $atividadeQ = utf8_encode($valor['ATIVIDADE']);
                                $questaoQ = utf8_encode($valor['QUESTAO']);
                                $enunciadoQ = utf8_encode($valor['ENUNCIADO']);
                                $alternativa1Q = utf8_encode($valor['ALTERNATIVA_1']);
                                $alternativa2Q = utf8_encode($valor['ALTERNATIVA_2']);
                                $alternativa3Q = utf8_encode($valor['ALTERNATIVA_3']);
                                $alternativa4Q = utf8_encode($valor['ALTERNATIVA_4']);
                                $alternativaCorretaQ = utf8_encode($valor['ALTERNATIVA_CORRETA']);
                                $statusQ = utf8_encode($valor['STATUS']);	
								$descricaoQ = utf8_encode($valor['DESCRICAO']);	
								$tituloQ = utf8_encode($valor['TITULO']);	
								$alternativaMarcadaQ = utf8_encode($valor['ALTERNATIVA_MARCADA']);
								$finalizadoQ = utf8_encode($valor['FINALIZADO']);
								$valorAtividadeQ = $valor['VALOR_ATIVIDADE'];
								$notaAtividadeQ = $valor['NOTA_ATIVIDADE'];
								
								
								 if($contador == 1){?>                
                                    <div class="row espaco">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <span style="color:#286090; font-weight:bold;">Título: </span><?=$tituloQ;?>
                                        </div> 
                                    </div>                
                                    <div class="row espaco">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <span style="color:#286090; font-weight:bold;">Descrição: </span><?=$descricaoQ;?>
                                        </div> 
                                    </div>
                                    <div class="row espaco" >  
                                        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:left !important;">
                                           <p style="color:#F00;"><span style="font-weight:bold; color:#286090;">Observação: </span>Ao marcar uma alternativa sua resposta será salva automaticamente.</p>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                            
                              <div class="row espaco">
                                    <div class="col-xs-1 col-sm-2 col-md-2"></div>
                                    <div class="col-xs-10 col-sm-8 col-md-8">
                                		<table class="table table-striped" 
                                        style="width:100%; border:1px solid transparent; border-color: #337ab7;">
                                    		<tr>
                                            	<td colspan="2" style="text-align:center; padding: 10px 15px; color: #fff; 
                                            background-color: #337ab7; border-color: #337ab7;">Questão <?=$contador;?></td>
                                   			</tr>
                                            <tr>
                                            	<td style=" text-align:right !important; width:10%;">
                                                    <span style="color:#286090; font-weight:bold;">Enunciado: </span>
                                                </td>
                                            	<td style="text-align:left !important;">
                                                    <?=$enunciadoQ;?>
                                                </td>
                                   			</tr>
                                            <tr>
                                            	<td style=" text-align:right !important; width:10%;">
                                                    <input type="radio" name="alternativa_<?=$questaoQ;?>" 
                                                    id="alternativa1_<?=$questaoQ;?>" value="1" 
													<?php if($alternativaMarcadaQ == 1){echo'checked="checked"';}?>
                                                    onclick="salvarQuestao(<?=$questaoQ;?>, 1, <?=$inscricaoQ;?>, <?=$atividadeQ;?>,<?=$contador;?>);"/>
                                                </td>
                                            	<td style="text-align:left !important;">
                                                  
                                                  <?php echo $alternativa1Q;
												  
												   if($finalizadoQ == 1){
														  
													  if($alternativaMarcadaQ == 1 && $alternativaCorretaQ == 1){
														  echo ' <i class="fa fa-check" aria-hidden="true" style="color:#090;"></i>';
													  }else if($alternativaMarcadaQ ==1 && $alternativaCorretaQ != 1){
														  echo ' <i class="fa fa-times" aria-hidden="true" style="color:#F00;"></i>';
													  }
													  
													  if($alternativaCorretaQ == 1){
														  echo ' <span style="color:#286090; font-weight:bold;"> - GABARITO: ALTERNATIVA CORRETA. </span>';
													  }
													  
												  }?>
                                                </td>
                                   			</tr>
                                            <tr>
                                            	<td style=" text-align:right !important; width:10%;">
                                                    <input type="radio" name="alternativa_<?=$questaoQ;?>" 
                                                    id="alternativa2_<?=$questaoQ;?>" value="2"
                                                    <?php if($alternativaMarcadaQ == 2){echo'checked="checked"';}?>
                                                    onclick="salvarQuestao(<?=$questaoQ;?>, 2, <?=$inscricaoQ;?>, <?=$atividadeQ;?>,<?=$contador;?>);"/>
                                                </td>
                                            	<td style="text-align:left !important;">
                                                    <?php echo $alternativa2Q;
												  
												   if($finalizadoQ == 1){
														  
													  if($alternativaMarcadaQ == 2 && $alternativaCorretaQ == 2){
														  echo ' <i class="fa fa-check" aria-hidden="true" style="color:#090;"></i>';
													  }else if($alternativaMarcadaQ ==2 && $alternativaCorretaQ != 2){
														  echo ' <i class="fa fa-times" aria-hidden="true" style="color:#F00;"></i>';
													  }
													  
													  if($alternativaCorretaQ == 2){
														  echo ' <span style="color:#286090; font-weight:bold;"> - GABARITO: ALTERNATIVA CORRETA. </span>';
													  }
													  
												  }?>
                                                </td>
                                   			</tr>
                                            <tr>
                                            	<td style=" text-align:right !important; width:10%;">
                                                    <input type="radio" name="alternativa_<?=$questaoQ;?>" 
                                                    id="alternativa3_<?=$questaoQ;?>" value="3"
                                                    <?php if($alternativaMarcadaQ == 3){echo'checked="checked"';}?>
                                                    onclick="salvarQuestao(<?=$questaoQ;?>, 3, <?=$inscricaoQ;?>, <?=$atividadeQ;?>,<?=$contador;?>);"/>
                                                </td>
                                            	<td style="text-align:left !important;">
                                                 <?php echo $alternativa3Q;
												  
												   if($finalizadoQ == 1){
														  
													  if($alternativaMarcadaQ == 3 && $alternativaCorretaQ == 3){
														  echo ' <i class="fa fa-check" aria-hidden="true" style="color:#090;"></i>';
													  }else if($alternativaMarcadaQ == 3 && $alternativaCorretaQ != 3){
														  echo ' <i class="fa fa-times" aria-hidden="true" style="color:#F00;"></i>';
													  }
													  
													  if($alternativaCorretaQ == 3){
														  echo ' <span style="color:#286090; font-weight:bold;"> - GABARITO: ALTERNATIVA CORRETA. </span>';
													  }
													  
												  }?>
                                                </td>
                                   			</tr>
                                            <tr>
                                            	<td style=" text-align:right !important; width:10%;">
                                                    <input type="radio" name="alternativa_<?=$questaoQ;?>" 
                                                    id="alternativa4_<?=$questaoQ;?>" value="4"
                                                    <?php if($alternativaMarcadaQ == 4){echo'checked="checked"';}?>
                                                    onclick="salvarQuestao(<?=$questaoQ;?>, 4, <?=$inscricaoQ;?>, <?=$atividadeQ;?>,<?=$contador;?>);"/>
                                                </td>
                                            	<td style="text-align:left !important;">
                                                 	<?php echo $alternativa4Q;
												  
												   if($finalizadoQ == 1){
														  
													  if($alternativaMarcadaQ == 4 && $alternativaCorretaQ == 4){
														  echo ' <i class="fa fa-check" aria-hidden="true" style="color:#090;"></i>';
													  }else if($alternativaMarcadaQ ==4 && $alternativaCorretaQ != 4){
														  echo ' <i class="fa fa-times" aria-hidden="true" style="color:#F00;"></i>';
													  }
													  
													  if($alternativaCorretaQ == 4){
														  echo ' <span style="color:#286090; font-weight:bold;"> - GABARITO: ALTERNATIVA CORRETA. </span>';
													  }
													  
												  }?>
                                                </td>
                                   			</tr>
                                    	</table>
                                    </div>
                                    <div class="col-xs-1 col-sm-2 col-md-2"></div>
                                 </div>
                                
                                <?php
                            }	
                        }
                    } //try
                    catch (PDOException $e)
                    {
                        // caso ocorra uma exceção, exibe na tela
                        echo "Erro!: " . $e->getMessage() . "<br>";
                        die();
                    }	
                }else{?>
                
                	<div class="row espaco">
                        <div class="col-xs-12 col-sm-12 col-md-12">Falha ao encontrar os dados da atividade.</div>
                    </div>
				
				<?php }?>
               <div class="row espaco"> 
                    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center !important;">
                     <?php if($finalizadoQ != 1){?>
                    	<button type="button" class="btn btn-sm btn-primary" 
                        title="Clique para finalizar a atividade." 
                        onclick="finalizarAtividade();">FINALIZAR ATIVIDADE
                        </button>  
                        <?php } else{ 
                        	echo'<span style="color:#F00; font-weight:bold;">ATIVIDADE FINALIZADA</span>';
                          } ?>    
                        <input type="text" name="cursoQ" id="cursoQ" value="<?=$cursoQ;?>" hidden="hidden"/>
                        <input type="text" name="atividadeQ" id="atividadeQ" value="<?=$atividadeQ;?>" hidden="hidden"/>
                         <input type="text" name="unidadeQ" id="unidadeQ" value="<?=$unidadeQ;?>" hidden="hidden"/>
                        <input type="text" name="inscricaoQ" id="inscricaoQ" value="<?=$inscricaoQ;?>" hidden="hidden"/>
                         <input type="text" name="finalizadoQ" id="finalizadoQ" value="<?=$finalizadoQ;?>" hidden="hidden"/>
	   				</div>
                </div>
                
                
                </form>
			</div>        
		</div>
	</div>


	<div id="clear"></div>
    <script>
	
	
	
	
	function salvarQuestao(questao, alternativa, inscricao, atividade, contador){
		
		var finalizado = $("#finalizadoQ").val();
		
		if(finalizado == 1){
			
			alert("Não é possível alterar a resposta da questão pois a atividade já foi finalizada.");
			return false;
		}
		
		
		
		$.ajax({
				url: 'salvar_questao.php',
				type: 'post',
				datatype: 'text',
				data: {questao : questao, atividade : atividade, inscricao : inscricao, alternativa : alternativa, contador : contador},
	
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
	
	function finalizarAtividade(){
		
		var atividade = $("#atividadeQ").val();
		var curso = $("#cursoQ").val();
		var inscricao = $("#inscricaoQ").val();
		var unidade = $("#unidadeQ").val();
		
		if (confirm('Tem certeza que deseja finalizar essa atividade? Ao confirmar não será possível alterar as respostas.')){
		          
		
		$.ajax({
				url: 'finalizar_atividade.php',
				type: 'post',
				datatype: 'text',
				data: {atividade : atividade, inscricao : inscricao, curso : curso, unidade : unidade},
	
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
					
					location.reload();
					
				}					
			});
		}
	
	}

		
    </script>
    
            
<?php
include_once("../../includes/rodape.php");
?>
