<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");


if(isset($_POST['curso'])){
	$curso = $_POST['curso'];
}
if(isset($_POST['unidade'])){
	$unidade = $_POST['unidade'];
	
}

$unidadeUA = '';
$tituloUA = '';
$descricaoUA = '';
$ordemUA = '';
$statusUA = '';

if(!empty($curso) && !empty($unidade)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						C.CURSO, 
						U.UNIDADE,
						U.TITULO, 
						U.DESCRICAO, 
						U.ORDEM, 
						U.STATUS
						FROM UNIDADES U
						JOIN CURSOS C ON C.CURSO = U.CURSO
						WHERE C.STATUS = 1
						AND U.STATUS = 1
						AND C.CURSO = :CURSO
						AND U.UNIDADE = :UNIDADE
						ORDER BY U.ORDEM";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$cursoUA = utf8_encode($valor['CURSO']);
				$unidadeUA = utf8_encode($valor['UNIDADE']);
				$tituloUA = utf8_encode($valor['TITULO']);
				$descricaoUA = utf8_encode($valor['DESCRICAO']);
				$ordemUA = utf8_encode($valor['ORDEM']);
				$statusUA = utf8_encode($valor['STATUS']);
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
  			<div class="panel-heading">Cadastrar / Alterar Unidades</div>
  			<div class="panel-body">
            	<div class="row espaco">
                	<div class="col-xs-8 col-sm-8 col-md-10">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Título" name="tituloU"
                             id="tituloU" value="<?=$tituloUA;?>" required="required" maxlength="255">
                             <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div> 
                    <div class="col-xs-4 col-sm-4 col-md-2">
                    	<div class="input-group">
                             <input type="number" class="form-control" placeholder="Ordem" name="ordemU" min="1" max="20" 
                            id="ordemU" value="<?=$ordemUA;?>" required="required" >
                        <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="input-group">
                            <textarea rows="4" cols="50" class="form-control" placeholder="Descrição" name="descricaoU" id="descricaoU" style="resize: none;" required="required"><?=$descricaoUA;?></textarea>
                            <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>
                <div class="row espaco" >  
                	<div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left !important;">
                       <p style="color:#F00;">* Campos de preenchimento obrigatório</p>
	   				</div>       	
                    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:right !important;">
                        <input type="text" name="cursoU" id="cursoU" value="<?=$curso;?>" hidden="hidden"/>
                        <input type="text" name="unidadeU" id="unidadeU" value="<?=$unidadeUA;?>" hidden="hidden"/>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="salvarUnidades();">SALVAR</button>
	   				</div>
                  
                </div>
			</div>        
		</div>
	</div>
     <div class="container" >
		<div class="panel panel-primary">
  			<div class="panel-heading">Unidades Cadastradas</div>
		<?php 
			if(!empty($curso)){
				
				try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
					$sqlQtd="	SELECT COUNT(U.UNIDADE) AS QTD 
								FROM UNIDADES U 
								JOIN CURSOS C ON C.CURSO = U.CURSO 
								WHERE 
									C.STATUS = 1 
									AND U.STATUS = 1 
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
					$SQLSelect = "	SELECT RowNumber, CURSO, UNIDADE, TITULO, DESCRICAO, ORDEM, STATUS 
									FROM ( 
											SELECT @rownum := @rownum + 1 RowNumber, t.* 
											FROM unidades t, 
											(SELECT @rownum := 0) s 
											WHERE 
												t.STATUS = 1 
												AND t.CURSO = :CURSO
										    ORDER BY t.ordem ) subQ WHERE subQ.RowNumber BETWEEN $Inicio AND $Fim";
									
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
												<th class="text-center">Unidade</th>
												<th class="text-center">Ordem</th>
												<th class="text-center">Descrição</th>
												<th class="text-center">Alterar</th>
												<th class="text-center">Desativar</th>
											</tr>
										</thead>
										<tbody>
								<?php
									foreach($resultados as $valor){
										
										$cursoU = utf8_encode($valor['CURSO']);
										$unidadeU = utf8_encode($valor['UNIDADE']);
										$tituloU = utf8_encode($valor['TITULO']);
										$descricaoU = utf8_encode($valor['DESCRICAO']);
										$ordemU = utf8_encode($valor['ORDEM']);
										$statusU = utf8_encode($valor['STATUS']);
						
							?>
										<tr>
											<td class="text-left"><?php echo $unidadeU.' - '.$tituloU; ?></td>
											<td ><?php echo $ordemU; ?></td>
											<td>
												<?php
													echo $descricaoU; 
												?>
											</td>
											
											<td>
                                            <button type="button" class="btn btn-sm btn-primary" title="Alterar Unidades" 
											onclick="gerirUnidades(<?=$cursoU;?>, <?=$unidadeU;?>);">
											<i class="fa fa-pencil" aria-hidden="true"></i>
											</button>
											
											</td>
											<td>
											<button type="button" class="btn btn-sm btn-primary" title="Desativar Unidade" 
											onclick="desativarUnidades(<?=$cursoU;?>, <?=$unidadeU;?>);">
											<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</button>
                                           
											</td>
										</tr>
									<?php 
									} 
								
								}else{?>
									<table class="table table-striped"> 
										<tr>
											<td>Nenhuma unidade encontrada</td>
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
                                                <li><a onclick="gerirUnidades(<?=$curso;?>, '', '0');"><div class="fa fa-step-backward"></div></a></li>
                                                <li><a onclick="gerirUnidades(<?=$curso;?>, '', <?= $PAGINA - 1; ?>);"><div class="fa fa-backward"></div></a></li>
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
                                                    <a onclick="gerirUnidades(<?=$curso;?>, '', <?= $pagina; ?>);"><?= $ix; ?></a>
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
                                                    <li><a onclick="gerirUnidades(<?=$curso;?>, '', <?=$PAGINA + 1;?>);"><div class="fa fa-forward"></div></a></li>
                                                    <li><a onclick="gerirUnidades(<?=$curso;?>, '', <?=$ULTIMA_MSG;?>);"><div class="fa fa-step-forward"></div></a></li>
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
		function salvarUnidades(){
		
			var titulo = $("#tituloU").val();
			var descricao = $("#descricaoU").val();
			var ordem = $("#ordemU").val();
			var curso = $("#cursoU").val();
			var unidade = $("#unidadeU").val();
			
			if(titulo == '' || ordem == '' || descricao == ''){		
				alert ("Preencha os campos obrigratórios e tente novamente.");		
				return false;
			}
			
			if(ordem < 1){		
				alert ("Campo ordem inválido, favor digitar um número maior que 0.");		
				return false;
			}
		
			if (confirm('Tem certeza que deseja salvar os dados da unidade?')){
			
				$.ajax({
					url: 'salvar_unidades.php',
					type: 'post',
					datatype: 'text',
					data: {titulo : titulo, descricao : descricao, ordem : ordem, unidade : unidade, curso : curso},
		
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
						
						gerirUnidades(curso);
					
					}					
				});	
			}
		}
		function desativarUnidades(curso, unidade){
	
			if (confirm('Tem certeza que deseja desativar a unidade?')){
			
				$.ajax({
					url: 'desativar_unidades.php',
					type: 'post',
					datatype: 'text',
					data: {curso : curso, unidade : unidade},
		
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
						
						gerirUnidades(curso);
					
					}					
				});	
			}
		}
    </script>
