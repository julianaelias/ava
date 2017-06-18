<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

if(isset($_POST['curso'])){
	$cursoQ = $_POST['curso'];
}
if(isset($_POST['unidade'])){
	$unidadeQ = $_POST['unidade'];
}

if(isset($_POST['atividade'])){
	$atividadeQ = $_POST['atividade'];
}
if(isset($_POST['questao'])){
	$questaoQ = $_POST['questao'];
}




$enunciadoQ = '';
$alternativa1Q = '';
$alternativa2Q = '';
$alternativa3Q = '';
$alternativa4Q = '';
$alternativaCorretaQ = '';
$statusQ = '';

if(!empty($cursoQ) && !empty($unidadeQ) && !empty($atividadeQ) && !empty($questaoQ)){


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
						Q.STATUS
						FROM QUESTOES Q
						JOIN ATIVIDADES A ON A.ATIVIDADE = Q.ATIVIDADE
						JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE	
						JOIN CURSOS C ON C.CURSO = U.CURSO
						WHERE C.STATUS = 1
						AND U.STATUS = 1
						AND C.CURSO = :CURSO
						AND U.UNIDADE = :UNIDADE
						AND A.ATIVIDADE = :ATIVIDADE
						AND Q.QUESTAO = :QUESTAO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $cursoQ, PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidadeQ, PDO::PARAM_INT);
		$operacao->bindParam(':ATIVIDADE', $atividadeQ, PDO::PARAM_INT);
		$operacao->bindParam(':QUESTAO', $questaoQ, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				
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

?>

    <div class="container" style="width:90%; text-align:center;" >
        <div class="panel panel-primary">
  			<div class="panel-heading">Cadastrar / Alterar Questão</div>
  			<div class="panel-body">
            <form id="formQuestoes_<?=$atividadeQ;?>" name="formQuestoes_<?=$atividadeQ;?>" enctype="multipart/form-data" method="post" 
                action="../admin/cursos/salvar_questoes.php"  style="margin: 0px; padding: 0px;">                
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12" style="font-weight:bold;">QUESTÃO <?=$questaoQ;?></div>
                </div>            	
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="4" cols="25" class="form-control" placeholder="Enunciado" name="enunciadoQ_<?=$atividadeQ;?>" 
                        id="enunciadoQ_<?=$atividadeQ;?>" style="resize: none;" required="required"><?=$enunciadoQ;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="2" cols="50" class="form-control" placeholder="Alternativa 1" name="alternativa1Q_<?=$atividadeQ;?>" 
                        id="alternativa1Q_<?=$atividadeQ;?>" style="resize: none;" required="required"><?=$alternativa1Q;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="2" cols="50" class="form-control" placeholder="Alternativa 2" name="alternativa2Q_<?=$atividadeQ;?>" 
                        id="alternativa2Q_<?=$atividadeQ;?>" style="resize: none;" required="required"><?=$alternativa2Q;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="2" cols="50" class="form-control" placeholder="Alternativa 3" name="alternativa3Q_<?=$atividadeQ;?>" 
                        id="alternativa3Q_<?=$atividadeQ;?>" style="resize: none;" required="required"><?=$alternativa3Q;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="2" cols="50" class="form-control" placeholder="Alternativa 4" name="alternativa4Q_<?=$atividadeQ;?>" 
                        id="alternativa4Q_<?=$atividadeQ;?>" style="resize: none;" required="required"><?=$alternativa4Q;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                	<div class="col-xs-6 col-sm-6 col-md-6">
                    	<div class="input-group">
                        	<select id="alternativa_corretaQ_<?=$atividadeQ;?>" name="alternativa_corretaQ_<?=$atividadeQ;?>" class="form-control" required="required"> 
								<option value="1" <?=($alternativaCorretaQ == 1)?'selected':''?>>ALTERNATIVA 1 (A)</option>
                                <option value="2" <?=($alternativaCorretaQ == 2)?'selected':''?>>ALTERNATIVA 2 (B)</option>
                                <option value="3" <?=($alternativaCorretaQ == 3)?'selected':''?>>ALTERNATIVA 3 (C)</option>
                                <option value="3" <?=($alternativaCorretaQ == 4)?'selected':''?>>ALTERNATIVA 4 (D)</option>
                            </select>
                        	<span class="input-group-addon" style="color:#F00;">*</span>
						</div>                    
                    </div>
                	<div class="col-xs-6 col-sm-6 col-md-6">
                    	<div class="input-group">
                        	<select id="statusQ_<?=$atividadeQ;?>" name="statusQ_<?=$atividadeQ;?>" class="form-control" required="required"> 
								<option value="1" <?=($statusQ == '' || $statusQ == 1)?'selected':''?>>ATIVO</option>
                                <option value="2" <?=($statusQ == 2)?'selected':''?>>INATIVO</option>
                                <option value="3" <?=($statusQ == 3)?'selected':''?>>ANULADO</option>
                            </select>
                        	<span class="input-group-addon" style="color:#F00;">*</span>
						</div>                    
                    </div>
                 </div>
               
                <div class="row espaco">  
                	<div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left !important;">
                       <p style="color:#F00;">* Campos de preenchimento obrigatório</p>
	   				</div>       	
                    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:right !important;">
                        <input type="text" name="cursoQ_<?=$atividadeQ;?>" id="cursoQ_<?=$atividadeQ;?>" value="<?=$cursoQ;?>" hidden="hidden"/>
                        <input type="text" name="atividadeQ_<?=$atividadeQ;?>" id="atividadeQ_<?=$atividadeQ;?>" value="<?=$atividadeQ;?>" hidden="hidden"/>
                         <input type="text" name="unidadeQ_<?=$unidadeQ;?>" id="unidadeQ_<?=$atividadeQ;?>" value="<?=$unidadeQ;?>" hidden="hidden"/>
                        <input type="text" name="questaoQ_<?=$atividadeQ;?>" id="questaoQ_<?=$atividadeQ;?>" value="<?=$questaoQ;?>" hidden="hidden"/>
                        <button type="button" class="btn btn-sm btn-primary" onclick="salvarQuestoes('<?=$atividadeQ;?>');">SALVAR</button>
	   				</div>
                </div>
                	
                </form>
			</div>        
		</div>
	</div>
    
    
     <div class="container" style="width:90%; text-align:center;" >
		<div class="panel panel-primary">
  			<div class="panel-heading">Questões Cadastrados</div>
		<?php 
			if(!empty($cursoQ)){
				
				try{
					// instancia objeto PDO, conectando no mysql
					$conexao = conn_mysql();
					
					// instrução SQL básica
					$SQLSelect = "	SELECT 
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
										Q.STATUS
										FROM QUESTOES Q
										JOIN ATIVIDADES A ON A.ATIVIDADE = Q.ATIVIDADE
										JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
										JOIN CURSOS C ON C.CURSO = U.CURSO
										WHERE C.STATUS = 1
										AND U.STATUS = 1
										AND Q.STATUS NOT IN(2)
										AND C.CURSO = :CURSO
										AND U.UNIDADE = :UNIDADE
										AND A.ATIVIDADE = :ATIVIDADE
										ORDER BY QUESTAO";
				
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
					if (count($resultados)> 0){?>
								
								
									<table class="table table-striped text-center">        
										<thead>
											<tr>
												<th class="text-center">Questao</th>
												<th class="text-center">Enunciado</th>
                                                <th class="text-center">Status</th>
												<th class="text-center">Alterar</th>
											</tr>
										</thead>
										<tbody>
								<?php
									foreach($resultados as $valor){
										
										$cursoQ = utf8_encode($valor['CURSO']);
										$unidadeQ = utf8_encode($valor['UNIDADE']);
										$atividadeQ = utf8_encode($valor['ATIVIDADE']);
										$questaoQ = utf8_encode($valor['QUESTAO']);
										$enunciadoQ = utf8_encode($valor['ENUNCIADO']);
										$statusQ = utf8_encode($valor['STATUS']);
						
								?>
										<tr>
											<td class="text-left" style="width:10%;"><?php echo $questaoQ?></td>
                                            <td class="text-left" style="width:70%;"><?php echo $enunciadoQ?></td>
                                            <td class="text-left" style="width:10%;">
												<?php 	if($statusQ == 1){
															echo'ATIVO';													
														}else if($statusQ == 2){
															echo'DESATIVO';
														}else if($statusQ == 3){
															echo'ANULADO';
														}
												?>
                                            </td>
										  	<td style="width:10%;">
                                                <button type="button" class="btn btn-sm btn-primary" title="Alterar Questão" 
                                                onclick="gerirQuestoes('<?=$cursoQ;?>', '<?=$unidadeQ;?>', '<?=$atividadeQ;?>', '<?=$questaoQ;?>', '2');">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
											</td>
											
										</tr>
									<?php 
									} 
								
								}else{?>
									<table class="table table-striped"> 
										<tr>
											<td>Nenhuma questão encontrada</td>
										</tr>
							<?php }?>
							
									</table>
					<?php	
					} //try
					catch (PDOException $e)
					{
						// caso ocorra uma exceção, exibe na tela
						echo "Erro!: " . $e->getMessage() . "<br>";
						die();
					}
				}?>      
			</div> 
	</div>
    
    <script>
	
	
	
	function salvarQuestoes(atividade){
		
		  // var dados = $("#formQuestoes_"+atividade).serialize();
		
			var enunciado = $("#enunciadoQ_"+atividade).val();
			var alternativa1 = $("#alternativa1Q_"+atividade).val();
			var alternativa2 = $("#alternativa2Q_"+atividade).val();
			var alternativa3 = $("#alternativa3Q_"+atividade).val();
			var alternativa4 = $("#alternativa4Q_"+atividade).val();
			var alternativaCorreta = $("#alternativa_corretaQ_"+atividade).val();
			var unidade = $("#unidadeQ_"+atividade).val();
			var curso = $("#cursoQ_"+atividade).val();
			var status = $("#statusQ_"+atividade).val();
			var questao = $("#questaoQ_"+atividade).val();
			
			
			if(enunciado == '' || atividade == '' || alternativa1 == '' || alternativa2 == '' || alternativaCorreta ==''){		
				alert ("Preencha os campos obrigratórios e tente novamente.");		
				return false;
			}
			
		
			if (confirm('Tem certeza que deseja salvar os dados da questão?')){
			
				$.ajax({
					url: 'salvar_questoes.php',
					type: 'post',
					datatype: 'text',
					data: {questao : questao, atividade : atividade, unidade : unidade, curso : curso, status : status, enunciado : enunciado, alternativa1 : alternativa1, alternativa2 : alternativa2, alternativa3 : alternativa3, alternativa4 : alternativa4, alternativaCorreta : alternativaCorreta},
		
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
						
						gerirQuestoes(curso,unidade, atividade, questao,'');
					
					}					
				});	
			}
		}
		
	
		
    </script>
