<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

if(isset($_POST['curso'])){
	$curso = $_POST['curso'];
}
if(isset($_POST['unidade'])){
	$unidade = $_POST['unidade'];
}

if(isset($_POST['material'])){
	$material = $_POST['material'];
}

$cursoMA = $curso;
$materialMA = '';
$unidadeMA = '';
$tituloMA = '';
$descricaoMA = '';
$nomeArquivoMA = '';
$tamanhoArquivoMA = '';
$tipoArquivoMA = '';
$arquivoMA = '';
$linkMA = '';
$tipoMA = '';
$statusMA = '';

if(!empty($curso) && !empty($unidade) && !empty($material)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						C.CURSO, 
						U.UNIDADE,
						M.MATERIAL,
						M.TITULO,
						M.DESCRICAO, 
						M.ARQUIVO, 
						M.TAMANHOARQUIVO, 
						M.TIPOARQUIVO,
						M.LINK, 
						M.TIPO, 
						M.STATUS
						FROM MATERIAIS M						
						JOIN UNIDADES U ON U.UNIDADE = M.UNIDADE
						JOIN CURSOS C ON C.CURSO = U.CURSO
						WHERE C.STATUS = 1
						AND U.STATUS = 1
						AND C.CURSO = :CURSO
						AND U.UNIDADE = :UNIDADE
						AND M.MATERIAL = :MATERIAL";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		$operacao->bindParam(':MATERIAL', $material, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				
				$cursoMA = utf8_encode($valor['CURSO']);
				$unidadeMA = utf8_encode($valor['UNIDADE']);
				$materialMA = utf8_encode($valor['MATERIAL']);
				$tituloMA = utf8_encode($valor['TITULO']);
				$descricaoMA  = utf8_encode($valor['DESCRICAO']);
				$tamanhoArquivoMA = utf8_encode($valor['TAMANHOARQUIVO']);
				$tipoArquivoMA = utf8_encode($valor['TIPOARQUIVO']);
				$arquivoMA = utf8_encode($valor['ARQUIVO']);
				$linkMA = utf8_encode($valor['LINK']);
				$tipoMA = utf8_encode($valor['TIPO']);
				$statusMA = utf8_encode($valor['STATUS']);
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
  			<div class="panel-heading">Cadastrar / Alterar Materiais</div>
  			<div class="panel-body">
            <form id="formMateriais" name="formMateriais" enctype="multipart/form-data" method="post" 
                action="/ava/admin/cursos/salvar_materiais.php" target="anexo" style="margin: 0px; padding: 0px;">
            	<div class="row espaco">
                	<div class="col-xs-12 col-sm-12 col-md-12">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Título" name="tituloM"
                             id="tituloM" value="<?=$tituloMA;?>" required="required" maxlength="255">
                             <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div> 
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <textarea rows="4" cols="50" class="form-control" placeholder="Descrição" name="descricaoM" 
                        id="descricaoM" style="resize: none;" required="required"><?=$descricaoMA;?></textarea>
                    </div>
                </div>
                <div class="row espaco">
                	<div class="col-xs-6 col-sm-6 col-md-6">
                    	<div class="input-group">
                             <select id="tipoM" name="tipoM" class="form-control" required="required" onchange="exibeArquivo();">
                                <option value="" <?=($tipoMA == '')?'selected':''?>>Selecione o Tipo</option> 
                                <option value="1" <?=($tipoMA == '1')?'selected':''?>>Arquivo</option>
                                <option value="2" <?=($tipoMA == '2')?'selected':''?>>Vídeo</option>
                                <option value="3" <?=($tipoMA == '3')?'selected':''?>>Podcast</option>
                            </select>
                        	<span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">                                
                    	<div class="input-group">
                        	<select id="unidadeM" name="unidadeM" class="form-control" required="required"> 
							<option value="" <?=($unidadeMA == '')?'selected':''?>>Selecione a Unidade</option>
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
											if($unidadeMA == $unidadeS ){
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
                 </div>
                <div class="row espaco" id="divArquivo" style="display:none;">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    	<div class="input-group">
                            <input type="file" class="form-control" placeholder="Arquivo" name="arquivoM"
                             id="arquivoM" value="<?=$arquivoMA;?>" >
                        	<span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                 </div>
                 <div class="row espaco" id="divLink" style="display:none;">
                     <div class="col-xs-12 col-sm-12 col-md-12">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Link" name="linkM"
                         	id="linkM" value="<?=$linkMA;?>" required="required" maxlength="255" >
                         	<span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>                
                
                <div class="row espaco">  
                	<div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left !important;">
                       <p style="color:#F00;">* Campos de preenchimento obrigatório</p>
	   				</div>       	
                    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:right !important;">
                        <input type="text" name="cursoM" id="cursoM" value="<?=$curso;?>" hidden="hidden"/>
                        <input type="text" name="materialM" id="materialM" value="<?=$materialMA;?>" hidden="hidden"/>
                        <input type="text" name="arquivoAntigo" id="arquivoAntigo" value="<?=$arquivoMA;?>" hidden="hidden"/>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="salvarMateriais();">SALVAR</button>
	   				</div>
                </div>
                	<iframe style="visibility:hidden; width:1px; height:1px" name="anexo" id="anexo" ></iframe>
                </form>
			</div>        
		</div>
	</div>
     <div class="container" >
		<div class="panel panel-primary">
  			<div class="panel-heading">Materiais Cadastrados</div>
		<?php 
			if(!empty($curso)){
				
				try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
					$sqlQtd="	SELECT COUNT(M.MATERIAL) AS QTD 
								FROM MATERIAIS M
								JOIN UNIDADES U ON U.UNIDADE = M.UNIDADE 
								JOIN CURSOS C ON C.CURSO = U.CURSO 
								WHERE 
									C.STATUS = 1 
									AND U.STATUS = 1 
									AND M.STATUS = 1 
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
										MATERIAL, 
										TITULO,
										DESCRICAO,
										TAMANHOARQUIVO, 
										TIPOARQUIVO,
										ARQUIVO, 
										LINK, 
										TIPO, 
										STATUS 
									FROM 
									( SELECT @rownum := @rownum + 1 RowNumber, u.titulo as TITULO_UNIDADE, t.* 
									 FROM materiais t
									 JOIN unidades u on u.unidade=t.unidade and u.status=1
									 JOIN cursos c on c.curso=u.curso and c.status=1, 
									 (SELECT @rownum := 0) s 
									 WHERE t.STATUS = 1 
									 AND c.CURSO = :CURSO
									 ORDER BY t.unidade, t.tipo, t.descricao )subQ 
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
												<th class="text-center">Material</th>
												<th class="text-center">Arquivo/Link</th>
												<th class="text-center">Tipo</th>
                                                <th class="text-center">Unidade</th>
											<!--	<th class="text-center">Alterar</th>-->
												<th class="text-center">Desativar</th>
											</tr>
										</thead>
										<tbody>
								<?php
									foreach($resultados as $valor){
										
										$unidadeM = utf8_encode($valor['UNIDADE']);
										$tiuloUnidadeM = utf8_encode($valor['TITULO_UNIDADE']);										
										$materialM = utf8_encode($valor['MATERIAL']);
										$tituloM = utf8_encode($valor['TITULO']);
										$descricaoM = utf8_encode($valor['DESCRICAO']);
										$tamanhoArquivoM = utf8_encode($valor['TAMANHOARQUIVO']);
										$tipoArquivoM = utf8_encode($valor['TIPOARQUIVO']);
										$arquivoM = utf8_encode($valor['ARQUIVO']);
										$linkM = utf8_encode($valor['LINK']);
										$tipoM = utf8_encode($valor['TIPO']);
										$statusM = utf8_encode($valor['STATUS']);
						
							?>
										<tr>
											<td class="text-left" style="width:10%;"><?php echo $materialM ?></td>
											<td style="width:40%;">
											<?php 
                                                if($tipoM == 2){//Video
												/*echo'<a href="'.$linkM.'" target="_blank" title="Clique para visualizar o vídeo."><i class="fa fa-youtube" aria-hidden="true"></i> '.$tituloM.'</a>';*/
												
												echo'<a href="videos.php?url='.$linkM.'&curso='.$cursoMA.'" target="_blank" title="Clique para visualizar o vídeo."><i class="fa fa-youtube-play" aria-hidden="true"></i> '.$tituloM.'</a>';
                                                }else if($tipoM == 1 || $tipoM == 3){//Arquivo
												echo'<a href="downloadMaterial.php?material='.$materialM.'&unidade='.$unidadeM.'" target="_blank" title="Clique para fazer download do arquivo."><i class="fa fa-download" aria-hidden="true"></i> '.$tituloM.'</a>';
									
												}?>
                                            </td>
											<td style="width:10%;">
												<?php 
                                                if($tipoM == 2){//Video
                                                    echo 'Vídeo'; 
                                                }else if($tipoM == 1){//Arquivo
                                                    echo 'Arquivo'; 
                                                }else if($tipoM == 3){//Podcast
                                                    echo 'Podcast'; 
                                                }?>
											</td>
                                           <td class="text-left" style="width:20%;"><?php echo $unidadeM.' - '.$tiuloUnidadeM;?></td>
										<!--	<td style="width:10%;">
                                            <button type="button" class="btn btn-sm btn-primary" title="Alterar Materiais" 
											onclick="gerirMateriais(<?=$curso;?>, <?=$unidadeM;?>, <?=$materialM;?>,'');">
											<i class="fa fa-pencil" aria-hidden="true"></i>
											</button>
											
											</td>-->
											<td style="width:10%;">
											<button type="button" class="btn btn-sm btn-primary" title="Desativar Material" 
											onclick="desativarMateriais(<?=$curso;?>, <?=$unidadeM;?>, <?=$materialM;?>);">
											<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</button>
                                           
											</td>
										</tr>
									<?php 
									} 
								
								}else{?>
									<table class="table table-striped"> 
										<tr>
											<td>Nenhum material encontrado</td>
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
                                                <li><a onclick="gerirMateriais(<?=$curso;?>, '', '', '0');"><div class="fa fa-step-backward"></div></a></li>
                                                <li><a onclick="gerirMateriais(<?=$curso;?>, '', '', <?= $PAGINA - 1; ?>);"><div class="fa fa-backward"></div></a></li>
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
                                                    <a onclick="gerirMateriais(<?=$curso;?>, '', '', <?= $pagina; ?>);"><?= $ix; ?></a>
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
                                                    <li><a onclick="gerirMateriais(<?=$curso;?>, '', '', <?=$PAGINA + 1;?>);"><div class="fa fa-forward"></div></a></li>
                                                    <li><a onclick="gerirMateriais(<?=$curso;?>, '', '', <?=$ULTIMA_MSG;?>);"><div class="fa fa-step-forward"></div></a></li>
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
		function salvarMateriais(){
		
			var titulo = $("#tituloM").val();
			var linkM = $("#linkM").val();
			var arquivo = $("#arquivoM").val();
			var tipo = $("#tipoM").val();
			var curso = $("#cursoM").val();
			var unidade = $("#unidadeM").val();
			
			
			if(titulo == '' || tipo == '' || unidade == '' || (linkM == '' && arquivo == '')){		
				alert ("Preencha os campos obrigratórios e tente novamente.");		
				return false;
			}
		
			if (confirm('Tem certeza que deseja salvar o material?')){
			
				$("#anexo").load(function(e){
					gerirMateriais(curso,'','','');
				});
				$('#formMateriais').submit();				
				
			}
		}
		
		function desativarMateriais(curso, unidade, material){
	
			if (confirm('Tem certeza que deseja desativar o material?')){
			
				$.ajax({
					url: 'desativar_material.php',
					type: 'post',
					datatype: 'text',
					data: {curso : curso, unidade : unidade, material : material},
		
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
						
						gerirMateriais(curso,'','','');
					
					}					
				});	
			}
		}
		
		function exibeArquivo(){
			
			$("#arquivoM").val('');
			$("#linkM").val('');
			$("#divArquivo").hide();
			$("#divLink").hide();	
			
			var tipo = $("#tipoM").val();
			
			if(tipo == 2){
				$("#divLink").show();
				$("#divArquivo").hide();
				
			}else{
				$("#divArquivo").show();
				$("#divLink").hide();
			}
			
		}
		
		
    </script>
