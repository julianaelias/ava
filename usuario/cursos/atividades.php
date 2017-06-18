<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

if(isset($_POST['curso'])){
	$cursoA = $_POST['curso'];
}
if(isset($_POST['unidade'])){
	$unidadeA = $_POST['unidade'];
}
if(isset($_POST['inscricao'])){
	$inscricaoA = $_POST['inscricao'];
}



	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT
							A.ATIVIDADE, 
							A.TITULO,
							A.DESCRICAO,
							A.STATUS, 
							A.UNIDADE
						 FROM ATIVIDADES A
						 JOIN UNIDADES U ON U.UNIDADE=A.UNIDADE AND U.STATUS=1
						 JOIN CURSOS C on C.CURSO=U.CURSO AND C.STATUS= 1						 
                         WHERE A.STATUS = 1                          
						 AND c.CURSO = :CURSO
						 AND U.UNIDADE = :UNIDADE
						 ORDER BY A.ATIVIDADE, A.TITULO ";
	
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);					
		
		
		$operacao->bindParam(':CURSO', $cursoA, PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidadeA, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultadosA = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultadosA)> 0){?>															
					
			<table class="table table-striped text-center">        
				<thead>
                	<tr>
						<th colspan="3" style="text-align:center; padding: 10px 15px; color: #fff; background-color: #337ab7;
                        border-color: #337ab7;">ATIVIDADES</th>
					</tr>
					<tr>
						<th class="text-center">Atividade</th>
						<th class="text-center">Descrição</th>
						<th class="text-center">Responder</th>
						
					</tr>
				</thead>
				<tbody>
		<?php
			foreach($resultadosA as $valorA){
													
				$atividadeA = utf8_encode($valorA['ATIVIDADE']);
				$tituloA = utf8_encode($valorA['TITULO']);
				$descricaoA = utf8_encode($valorA['DESCRICAO']);
				$statusA = utf8_encode($valorA['STATUS']);
				$unidadeA = utf8_encode($valorA['UNIDADE']);
	
	?>
				<tr>
                	<td class="text-left" style="width:40%;"><?php echo $atividadeA.' - '.$tituloA?></td>
					<td style="width:50%;"><?php echo $descricaoA?></td>
					<td style="width:10%;">
                        <!--<button type="button" class="btn btn-sm btn-success" title="Responder" 
                        onclick="responder(<?=$cursoA;?>, <?=$unidadeA;?>, <?=$atividadeA;?>, <?=$inscricaoA;?>, 1);">
                        <i class="fa fa-check-square-o" aria-hidden="true"></i> RESPONDER
                        </button>-->
                        <a class="btn btn-sm btn-success" title="Responder" 
                        href="../usuario/cursos/responder.php?curso=<?=$cursoA;?>&unidade=<?=$unidadeA;?>&atividade=<?=$atividadeA;?>&inscricao=<?=$inscricaoA;?>" role="button">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i> RESPONDER
                        </a>
                    </td>
				</tr>
				<!--<tr style="display:none;" id="tr_<?=$atividadeA;?>">
					<td class="text-left" colspan="3" style="display:none; width:100%;" id="td_<?=$atividadeA;?>"><div id="atividade_<?=$atividadeA;?>"></div></td>
				</tr>-->
			<?php 
			} 
		
		}else{?>
			<table class="table table-striped"> 
				<tr>
					<td>Nenhuma atividade encontrada.</td>
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
	
	?>                            