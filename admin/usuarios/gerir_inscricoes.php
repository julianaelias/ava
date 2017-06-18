<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

if(isset($_POST['usuario'])){
	$usuarioI = $_POST['usuario'];
}

?>
    
    
     <div class="container" style="width:90%; text-align:center;" >
		<div class="panel panel-primary">
  			<div class="panel-heading">Inscrições</div>
		<?php 
			if(!empty($usuarioI)){
				
				try{
					// instancia objeto PDO, conectando no mysql
					$conexao = conn_mysql();
					
					// instrução SQL básica
					$SQLSelect = "	SELECT
										I.INSCRICAO, 
										I.CURSO,
										date_format(I.DATA,'%d/%m/%Y') AS DATA,
										I.SITUACAO,
										C.TITULO
										FROM INSCRICOES I
										JOIN CURSOS C ON C.CURSO = I.CURSO
										WHERE C.STATUS = 1
										AND I.USUARIO = :USUARIO
										ORDER BY I.INSCRICAO";
				
					//prepara a execução da sentença
					$operacao = $conexao->prepare($SQLSelect);					
					
					$operacao->bindParam(':USUARIO', $usuarioI, PDO::PARAM_INT);
					
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
												<th class="text-center">Inscrição</th>
												<th class="text-center">Curso</th>
                                                <th class="text-center">Data</th>
												<th class="text-center">Situação</th>
                                                <th class="text-center">Relatório</th>
                                                <th class="text-center">Cancelar</th>
											</tr>
										</thead>
										<tbody>
								<?php
									foreach($resultados as $valor){
										
										$inscricaoI = utf8_encode($valor['INSCRICAO']);
										$cursoI = utf8_encode($valor['CURSO']);
										$dataI = utf8_encode($valor['DATA']);
										$situacaoI = utf8_encode($valor['SITUACAO']);
										$tituloI = utf8_encode($valor['TITULO']);
						
								?>
										<tr>
											<td class="text-left" style="width:10%;"><?php echo $inscricaoI?></td>
                                            <td class="text-left" style="width:30%;"><?php echo $cursoI.' - '.$tituloI?></td>
                                            <td class="text-left" style="width:10%;"><?php echo $dataI?></td>
                                            <td class="text-left" style="width:10%;">
												<?php 	if($situacaoI == 1){
															echo'ATIVO';													
														}else if($situacaoI == 2){
															echo'CANCELADO';
														}
												?>
                                            </td>
                                           
										  	<td style="width:10%;">
                                            
                                            <?php if($situacaoI == 1){?>
                                                    <a class="btn btn-sm btn-primary" title="Emitir Relatório"  target="_blank"
                                                    href="/ava/admin/usuarios/relatorio.php?inscricao=<?=$inscricaoI;?>&usuario=<?=$usuarioI;?>&curso=<?=$cursoI;?>" role="button">
                                                    <i class="fa fa-file-text" aria-hidden="true"></i>
                                                    </a>
                                    
                                    		<?php }else{
										   			echo"CANCELADO";
												  }?>
											</td>
                                            <td style="width:10%;">
                                              <?php if($situacaoI == 1){?>
                                                        <button type="button" class="btn btn-sm btn-primary" 
                                                        title="Cancelar Inscrição" 
                                                        onclick="desativarInscricao(<?=$inscricaoI;?>, <?=$usuarioI;?>);">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                        </button>
                                             <?php }else{
										   				echo"CANCELADO";
													}?>
                                           
											</td>
											
										</tr>
									<?php 
									} 
								
								}else{?>
									<table class="table table-striped"> 
										<tr>
											<td>Nenhuma incrição encontrada</td>
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
    
    