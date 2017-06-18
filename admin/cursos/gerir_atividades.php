<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

if(isset($_POST['curso'])){
	$curso = $_POST['curso'];
}
if(isset($_POST['unidade'])){
	$unidade = $_POST['unidade'];
}

if(isset($_POST['atividade'])){
	$atividade = $_POST['atividade'];
}



$cursoA = $curso;
$atividadeA = '';
$unidadeA = '';
$tituloA = '';
$descricaoA = '';
$statusA = '';

if(!empty($curso) && !empty($unidade) && !empty($atividade)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						C.CURSO, 
						U.UNIDADE,
						A.ATIVIDADE,
						A.TITULO,
						A.DESCRICAO, 
						A.STATUS
						FROM ATIVIDADES A						
						JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
						JOIN CURSOS C ON C.CURSO = U.CURSO
						WHERE C.STATUS = 1
						AND U.STATUS = 1
						AND C.CURSO = :CURSO
						AND U.UNIDADE = :UNIDADE
						AND A.ATIVIDADE = :ATIVIDADE";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				
				$cursoA = utf8_encode($valor['CURSO']);
				$unidadeA = utf8_encode($valor['UNIDADE']);
				$atividadeA = utf8_encode($valor['ATIVIDADE']);
				$tituloA = utf8_encode($valor['TITULO']);
				$descricaoA  = utf8_encode($valor['DESCRICAO']);
				$statusA = utf8_encode($valor['STATUS']);
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
<div id="tudo">
    <div class="container" >
        <div class="panel panel-primary">
  			<div class="panel-heading">Cadastrar / Alterar Atividades</div>
  			<div class="panel-body">
            <form id="formAtividades" name="formAtividades" enctype="multipart/form-data" method="post" 
                action="/ava/admin/cursos/salvar_atividades.php"  style="margin: 0px; padding: 0px;">
            	<div class="row espaco">
                	<div class="col-xs-12 col-sm-12 col-md-12">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Título" name="tituloA"
                             id="tituloA" value="<?=$tituloA;?>" required="required" maxlength="255">
                             <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div> 
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="4" cols="50" class="form-control" placeholder="Descrição" name="descricaoA" 
                        id="descricaoA" style="resize: none;" required="required"><?=$descricaoA;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                	
                    <div class="col-xs-6 col-sm-6 col-md-6">                                
                    	<div class="input-group">
                        	<select id="unidadeA" name="unidadeA" class="form-control" required="required"> 
							<option value="" <?=($unidadeA == '')?'selected':''?>>Selecione a Unidade</option>
								<?php
                                try{
                                    // instancia objeto PDO, conectando no mysql
                                    $conexao = conn_mysql();
                                    
                                    // instrução SQL básica
                                    $SQLSelect = "SELECT 
                                                    U.UNIDADE,
                                                    U.TITULO
                                                    FROM UNIDADES U
                                                    JOIN CURSOS C ON C.CURSO = U.CURSO
                                                    WHERE C.STATUS = 1
                                                    AND U.STATUS = 1
                                                    AND C.CURSO = :CURSO
                                                    ORDER BY U.ORDEM";
                                                    
                                    //prepara a execução da sentença
                                    $operacao = $conexao->prepare($SQLSelect);
                                    
                                    $operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
                                    $pesquisar = $operacao->execute();
                                
                                    //captura TODOS os resultados obtidos
                                    $resultados = $operacao->fetchAll();
                                    
                                    // fecha a conexão (os resultados já estão capturados)
                                    $conexao = null;
                                
                                    // se há resultados, os escreve em uma tabela
                                    if (count($resultados)> 0){
                                        foreach($resultados as $valor){
                                            $unidadeS = utf8_encode($valor['UNIDADE']);
                                            $tituloS = utf8_encode($valor['TITULO']);
											if($unidadeA == $unidadeS ){
												$selecione = "selected";
											}else{
												$selecione = " ";
											}
									echo"<option value='$unidadeS' $selecione>$tituloS</option>";
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
                            </select>
                        	<span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                    	<div class="input-group">
                        	<select id="statusA" name="statusA" class="form-control" required="required"> 
								<option value="1" <?=($statusA == '' || $statusA == 1)?'selected':''?>>ATIVO</option>
                                <option value="2" <?=($statusA == 2)?'selected':''?>>INATIVO</option>
                                <option value="3" <?=($statusA == 3)?'selected':''?>>ANULADO</option>
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
                        <input type="text" name="cursoA" id="cursoA" value="<?=$cursoA;?>" hidden="hidden"/>
                        <input type="text" name="atividadeA" id="atividadeA" value="<?=$atividadeA;?>" hidden="hidden"/>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="salvarAtividades();">SALVAR</button>
	   				</div>
                </div>
                	
                </form>
			</div>        
		</div>
	</div>
     <div class="container" >
		<div class="panel panel-primary">
  			<div class="panel-heading">Atividades Cadastrados</div>
		<?php 
			if(!empty($curso)){
				
				try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
					$sqlQtd="	SELECT COUNT(A.ATIVIDADE) AS QTD 
								FROM ATIVIDADES A
								JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE 
								JOIN CURSOS C ON C.CURSO = U.CURSO 
								WHERE 
									C.STATUS = 1 
									AND U.STATUS = 1 
									AND A.STATUS = 1 
									AND C.CURSO = :CURSO";
									
					//prepara a execução da sentença
                    $operacao = $conexao->prepare($sqlQtd);
					
					if(!empty($curso)){
						$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
					}
					
					
					$pesquisar = $operacao->execute();

                    //captura TODOS os resultados obtidos
                    $resultados = $operacao->fetchAll();
                    
                    // fecha a conexão (os resultados já estão capturados)
                    $conexao = null;
					
					foreach($resultados as $valor){
					
						$QT = $valor['QTD'];
					}
					
					$QTDMOSTRA = 6; // QUANTIDADE FIXA DE LINHAS POR PAGINA DA GRID

					if ($QT % $QTDMOSTRA != 0) {
						$ULTIMA_MSG = ((int) ($QT / $QTDMOSTRA) + 1) - 1;
					} else {
						$ULTIMA_MSG = (int) ($QT / $QTDMOSTRA) - 1;
					}
					
					if( isset($_POST['pagina'])){
						$PAGINA = $_POST['pagina'];
					}else{
						$PAGINA = 0;
					}
					
					$Inicio = $QTDMOSTRA * $PAGINA;
					$Fim = $QTDMOSTRA + $Inicio;
					if($Inicio != 0){
						$Inicio = $Inicio+1;
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
					$SQLSelect = "	SELECT 
										RowNumber, 
										UNIDADE, 
										TITULO_UNIDADE,
										ATIVIDADE, 
										TITULO,
										DESCRICAO,
										CURSO,
										STATUS 
									FROM 
									( SELECT @rownum := @rownum + 1 RowNumber, u.titulo as TITULO_UNIDADE,
									  c.CURSO, t.* 
									 FROM ATIVIDADES t
									 JOIN unidades u on u.unidade=t.unidade and u.status=1
									 JOIN cursos c on c.curso=u.curso and c.status=1, 
									 (SELECT @rownum := 0) s 
									 WHERE t.STATUS IN(1,3) 
									 AND c.CURSO = :CURSO
									 ORDER BY t.unidade, t.atividade, t.titulo )subQ 
									 WHERE subQ.RowNumber BETWEEN $Inicio AND $Fim";
				
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
					if (count($resultados)> 0){?>
								
								
									<table class="table table-striped text-center">        
										<thead>
											<tr>
												<th class="text-center">Atividade</th>
												<th class="text-center">Título</th>												
                                                <th class="text-center">Unidade</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Gerir Questões</th>
												<th class="text-center">Alterar</th>
												<th class="text-center">Desativar</th>
											</tr>
										</thead>
										<tbody>
								<?php
									foreach($resultados as $valor){
										
										$unidadeA = utf8_encode($valor['UNIDADE']);
										$tiuloUnidadeA = utf8_encode($valor['TITULO_UNIDADE']);										
										$atividadeA = utf8_encode($valor['ATIVIDADE']);
										$tituloA = utf8_encode($valor['TITULO']);
										$descricaoA = utf8_encode($valor['DESCRICAO']);
										$statusA = utf8_encode($valor['STATUS']);
										$cursoA = utf8_encode($valor['CURSO']);
						
								?>
										<tr>
											<td class="text-left" style="width:10%;"><?php echo $atividadeA?></td>
                                            <td class="text-left" style="width:20%;"><?php echo $tituloA?></td>
                                           	<td class="text-left" style="width:20%;">
												<?php echo $unidadeA.' - '.$tiuloUnidadeA;?>
                                            </td>
                                            <td class="text-left" style="width:10%;">
												<?php 	if($statusA == 1){
															echo'ATIVO';													
														}else if($statusA == 2){
															echo'DESATIVO';
														}else if($statusA == 3){
															echo'ANULADO';
														}
												?>
                                            </td>
                                            <td style="width:10%;">
                                             	<button type="button" class="btn btn-sm btn-primary" title="Gerir Questões" 
                                                onclick="gerirQuestoes(<?=$curso;?>, <?=$unidadeA;?>, <?=$atividadeA;?>,'', '1');">
                                                <i class="glyphicon glyphicon-cog" aria-hidden="true"></i>
                                                </button>
                                			</td>
										  	<td style="width:10%;">
                                                <button type="button" class="btn btn-sm btn-primary" title="Alterar Atividade" 
                                                onclick="gerirAtividades(<?=$curso;?>, <?=$unidadeA;?>, <?=$atividadeA;?>,'');">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
											</td>
											<td style="width:10%;">
                                                <button type="button" class="btn btn-sm btn-primary" title="Desativar Atividade" 
                                                onclick="desativarAtividades(<?=$curso;?>, <?=$unidadeA;?>, <?=$atividadeA;?>);">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                </button>                                           
											</td>
										</tr>
                                        <tr style="display:none;" id="tr_<?=$atividadeA;?>">
											<td class="text-left" colspan="7" style="display:none; width:90%;" id="td_<?=$atividadeA;?>"><div id="atividade_<?=$atividadeA;?>"></div></td>
                                        </tr>
									<?php 
									} 
								
								}else{?>
									<table class="table table-striped"> 
										<tr>
											<td>Nenhuma atividade encontrada</td>
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
					
					if($QT > $QTDMOSTRA){ ?>
                        <div id="DivPaginacao">
                            <table width="100%" align="center" >            
                                <tr>           
                                    <td valign="middle" align="center" style="text-align:center !important; cursor:pointer;">
                                        <ul class="pagination pagination-sm" >
                                        
                                         <?php
                                            if ($PAGINA > 0) {?>
                                                <li><a onclick="gerirAtividades(<?=$curso;?>, '', '', '0');"><div class="fa fa-step-backward"></div></a></li>
                                                <li><a onclick="gerirAtividades(<?=$curso;?>, '', '', <?= $PAGINA - 1; ?>);"><div class="fa fa-backward"></div></a></li>
                                          <?php } 
                                            //verifica qtde de paginas
                                            if ((($QT % $QTDMOSTRA) != 0) || ($QT > 1)) {
                                                //qtde de paginas anterior e posterior
                                                $QTANTE = $PAGINA;
                                                $QTPROX = $PAGINA + 2;
                    
                                                //valida
                                                if ($QTANTE <= 0){
                                                    $QTANTE = 1;
                                                }
                    
                                                if ($QTPROX > $ULTIMA_MSG + 1){
                                                    $QTPROX = $ULTIMA_MSG + 1;
                                                }
                    
                                                //monta a paginação numerica
                                                for ($ix = $QTANTE; $ix <= $QTPROX; $ix++) {
                                                    $pagina = $ix - 1;
                                                    ?><li class="<?= ($ix == $PAGINA + 1) ? 'active' : ''; ?>">
                                                    <a onclick="gerirAtividades(<?=$curso;?>, '', '', <?= $pagina; ?>);"><?= $ix; ?></a>
                                                    </li><?php
                                                }
                                            }else {
                                                echo '<li>';
                                                echo (int) ($QT / $QTDMOSTRA);
                                                echo '</li>';
                                            }
                                                $ultima = $ULTIMA_MSG;
                                                if ($QT > $Fim) {
                                                ?>
                                                    <li><a onclick="gerirAtividades(<?=$curso;?>, '', '', <?=$PAGINA + 1;?>);"><div class="fa fa-forward"></div></a></li>
                                                    <li><a onclick="gerirAtividades(<?=$curso;?>, '', '', <?=$ULTIMA_MSG;?>);"><div class="fa fa-step-forward"></div></a></li>
                                                <?php
                                                }
                                                ?>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
			<?php } 
					}?>      
			</div> 
	</div>
    <script>
	
	
	
	function salvarAtividades(){
		
			var titulo = $("#tituloA").val();
			var descricao = $("#descricaoA").val();
			var unidade = $("#unidadeA").val();
			var atividade = $("#atividadeA").val();
			var curso = $("#cursoA").val();
			var status = $("#statusA").val();
			
			
			if(titulo == '' || unidade == '' || descricao == '' || status == ''){		
				alert ("Preencha os campos obrigratórios e tente novamente.");		
				return false;
			}
			
		
			if (confirm('Tem certeza que deseja salvar os dados da atividade?')){
			
				$.ajax({
					url: 'salvar_atividades.php',
					type: 'post',
					datatype: 'text',
					data: {titulo : titulo, descricao : descricao, atividade : atividade, unidade : unidade, curso : curso, status : status},
		
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
						
						gerirAtividades(curso,'','','');
					
					}					
				});	
			}
		}
		function desativarAtividades(curso, unidade, atividade){
	
			if (confirm('Tem certeza que deseja desativar a atividade?')){
			
				$.ajax({
					url: 'desativar_atividades.php',
					type: 'post',
					datatype: 'text',
					data: {curso : curso, unidade : unidade, atividade : atividade},
		
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
						
						gerirAtividades(curso,'','','');
					
					}					
				});	
			}
		}
	
		
    </script>
