<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

if(isset($_POST['curso'])){
	$cursoM = $_POST['curso'];
}
if(isset($_POST['unidade'])){
	$unidadeM = $_POST['unidade'];
}
if(isset($_POST['inscricao'])){
	$inscricaoM = $_POST['inscricao'];
}

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT
							M.MATERIAL, 
							M.TITULO,
							M.DESCRICAO,
							M.TAMANHOARQUIVO, 
							M.TIPOARQUIVO,
							M.ARQUIVO, 
							M.LINK, 
							M.TIPO, 
							M.STATUS 
						 FROM materiais M
						 JOIN unidades u on u.unidade=M.unidade and u.status=1
						 JOIN cursos c on c.curso=u.curso and c.status=1
						 WHERE M.STATUS = 1 
						 AND c.CURSO = :CURSO
						 AND U.UNIDADE = :UNIDADE
						 ORDER BY M.tipo, M.TITULO ";
	
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);					
		
		
		$operacao->bindParam(':CURSO', $cursoM, PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidadeM, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultadosM = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultadosM)> 0){?>															
					
			<table class="table table-striped text-center">        
				<thead>
                	<tr>
						<th colspan="3" style="text-align:center; padding: 10px 15px; color: #fff; background-color: #337ab7;
                        border-color: #337ab7;">MATERIAIS</th>
					</tr>
					<tr>
						<th class="text-center">Tipo</th>
						<th class="text-center">Arquivo/Link</th>
						<th class="text-center">Descrição</th>
						
					</tr>
				</thead>
				<tbody>
		<?php
			foreach($resultadosM as $valorM){
													
				$materialM = utf8_encode($valorM['MATERIAL']);
				$tituloM = utf8_encode($valorM['TITULO']);
				$descricaoM = utf8_encode($valorM['DESCRICAO']);
				$tamanhoArquivoM = utf8_encode($valorM['TAMANHOARQUIVO']);
				$tipoArquivoM = utf8_encode($valorM['TIPOARQUIVO']);
				$arquivoM = utf8_encode($valorM['ARQUIVO']);
				$linkM = utf8_encode($valorM['LINK']);
				$tipoM = utf8_encode($valorM['TIPO']);
				$statusM = utf8_encode($valorM['STATUS']);
	
	?>
				<tr>
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
					
					<td style="width:40%;">
					<?php 
						if($tipoM == 2){//Video
						echo'<a href="'.$linkM.'" target="_blank" title="Clique para visualizar o vídeo." onclick="registar('.$materialM.','.$unidadeM,','.$cursoM.','.$inscricaoM.');"><i class="fa fa-youtube" aria-hidden="true"></i> '.$tituloM.'</a>';
						}else if($tipoM == 1 || $tipoM == 3){//Arquivo
						echo'<a href="downloadMaterial.php?material='.$materialM.'&unidade='.$unidadeM.'&curso='.$cursoM.'&inscricao='.$inscricaoM.'" target="_blank" title="Clique para fazer download do arquivo."><i class="fa fa-download" aria-hidden="true"></i> '.$tituloM.'</a>';
			
						}?>
					</td>
					
				   <td class="text-left" style="width:40%;"><?php echo $materialM.' - '.$descricaoM;?></td>
				</tr>
			<?php 
			} 
		
		}else{?>
			<table class="table table-striped"> 
				<tr>
					<td>Nenhum material encontrado.</td>
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