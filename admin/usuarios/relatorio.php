<?php 
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include("../../mpdf60/mpdf.php");

$curso = $_GET['curso'];
$inscricao = $_GET['inscricao'];
$usuario = $_GET['usuario'];
$qtd_acertos = 0;
$aprovado = "CURSANDO / REPROVADO";
$contador = 0;
$pontos = 0;
$acertos = 0;


	//DADOS RELATÓRIO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT 	C.CURSO,
								C.TITULO,
								U.USUARIO,
								U.NOME
						FROM usuarios U
						JOIN inscricoes I ON I.USUARIO = U.USUARIO
						JOIN cursos C ON C.CURSO = I.CURSO
						WHERE U.USUARIO = :USUARIO
							AND C.CURSO = :CURSO
							AND I.INSCRICAO = :INSCRICAO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$cursoC = utf8_encode($valor['CURSO']);
				$tituloCursoC = utf8_encode($valor['TITULO']);
				$usuarioC = utf8_encode($valor['USUARIO']);
				$nomeC = utf8_encode($valor['NOME']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	$html = "
			   <table class='tabela'>
					<tr>
						<td class='trTitulo' colspan='5'>RELATÓRIO</td>
					</tr>
					<tr>
						<td class='trSubTitulo1'><strong>Aluno(a): </strong></td>
						<td class='trSubTitulo2' colspan='4'>".$nomeC."</td>
					</tr>
					<tr>
						<td class='trSubTitulo3'><strong>Curso: </strong></td>
						<td class='trSubTitulo4' colspan='4'>".$cursoC." - ".$tituloCursoC."</td>
					</tr>";
				
				
				

	//QTD VIDEOS DO CURSO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT COUNT(M.MATERIAL) AS QTD_VIDEOS
						FROM materiais M
						JOIN unidades U ON U.UNIDADE = M.UNIDADE
						JOIN cursos C ON C.CURSO = U.CURSO
						WHERE M.STATUS = 1
							AND U.STATUS = 1
							AND M.TIPO = 2
							AND C.CURSO = :CURSO";
						
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
				$qtdVideos = $valor['QTD_VIDEOS'];
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	//QTD VIDEOS ASSISTIDOS PELO ALUNO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT COUNT(M.MATERIAL) AS QTD_VIDEOS_ALUNO
						FROM materiais M
						JOIN unidades U ON U.UNIDADE = M.UNIDADE
						JOIN cursos C ON C.CURSO = U.CURSO
						JOIN materiais_usuarios MU ON MU.MATERIAL = M.MATERIAL
						JOIN inscricoes I ON I.INSCRICAO = MU.INSCRICAO AND I.USUARIO = MU.USUARIO
						WHERE M.STATUS = 1
							AND U.STATUS = 1
							AND M.TIPO = 2
							AND C.CURSO = :CURSO
							AND MU.INSCRICAO = :INSCRICAO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$qtdVideosAluno = $valor['QTD_VIDEOS_ALUNO'];
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	$porcentagemVideos = round((($qtdVideosAluno * 100) / $qtdVideos ));
	
	$html.= "
				
				<tr>
					<td class='trSubTitulo3'><strong>Vídeos: </strong></td>
					<td class='trSubTitulo4' colspan='4'>".$porcentagemVideos."% dos vídeos foram assistidos</td>
				</tr>
				<tr>
					<td class='trSubTitulo' colspan='5'><strong>Relação de Atividades </strong></td>
				</tr>
				<tr>
				    <td class='trSubTitulo' style='width:30%;' colspan='2'><strong>Unidade </strong></td>
					<td class='trSubTitulo' style='width:35%;'><strong>Atividade</strong></td>
					<td class='trSubTitulo' style='width:15%;'><strong>Valor</strong></td>
					<td class='trSubTitulo' style='width:15%;'><strong>Nota</strong></td>
				</tr>";
	
	
	//QTD QUESTÕES DO CURSO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT COUNT(Q.QUESTAO) AS QTD_QUESTOES
						FROM questoes Q
						JOIN atividades A ON A.ATIVIDADE = Q.ATIVIDADE
						JOIN unidades U ON U.UNIDADE = A.UNIDADE
						JOIN cursos C ON C.CURSO = U.CURSO
						WHERE Q.STATUS = 1
							AND A.STATUS = 1
							AND U.STATUS = 1
							AND C.STATUS = 1
							AND C.CURSO = :CURSO";
						
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
				$qtdQuestoes = $valor['QTD_QUESTOES'];
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	
	//QTD QUESTÕES RESPONDIDAS PELO ALUNO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT 	COUNT(QU.QUESTAO) AS QTD_QUESTOES_ALUNO
							FROM questoes_usuarios QU
							JOIN questoes Q ON Q.QUESTAO = QU.QUESTAO
							JOIN atividades A ON A.ATIVIDADE = QU.ATIVIDADE
							JOIN unidades U ON U.UNIDADE = A.UNIDADE
							JOIN cursos C ON C.CURSO = U.CURSO
							JOIN inscricoes I ON I.INSCRICAO = QU.INSCRICAO AND I.USUARIO = QU.USUARIO
							WHERE Q.STATUS = 1
							AND A.STATUS = 1
							AND U.STATUS = 1
							AND C.STATUS = 1
							AND I.SITUACAO = 1
							AND C.CURSO = :CURSO
							AND I.INSCRICAO = :INSCRICAO
							AND I.USUARIO = :USUARIO
							AND QU.FINALIZADO = 1";
							
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$qtdQuestoesAluno = $valor['QTD_QUESTOES_ALUNO'];
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
			
			//LISTA ATIVIDADES
			try{
				// instancia objeto PDO, conectando no mysql
				$conexao = conn_mysql();
				
				// instrução SQL básica
				$SQLSelect = "	SELECT DISTINCT	Q.ATIVIDADE,
										U.UNIDADE,
										C.CURSO,
										A.DESCRICAO AS DESC_ATIVIDADE,
										U.DESCRICAO AS DESC_UNIDADE,
										QU.NOTA_ATIVIDADE,
										QU.VALOR_ATIVIDADE
										FROM questoes Q
										JOIN atividades A ON A.ATIVIDADE = Q.ATIVIDADE
										JOIN unidades U ON U.UNIDADE = A.UNIDADE
										JOIN cursos C ON C.CURSO = U.CURSO
										JOIN questoes_usuarios QU ON QU.QUESTAO = Q.QUESTAO
										JOIN inscricoes I ON I.INSCRICAO = QU.INSCRICAO AND I.USUARIO = QU.USUARIO
										WHERE Q.STATUS = 1
										AND A.STATUS = 1
										AND U.STATUS = 1
										AND C.STATUS = 1
										AND C.CURSO = :CURSO
										AND I.INSCRICAO = :INSCRICAO
										ORDER BY U.ORDEM, Q.ATIVIDADE";
								
				//prepara a execução da sentença
				$operacao = $conexao->prepare($SQLSelect);
				
				$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
				$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
				
				$pesquisar = $operacao->execute();
			
				//captura TODOS os resultados obtidos
				$resultados = $operacao->fetchAll();
				
				// fecha a conexão (os resultados já estão capturados)
				$conexao = null;
			
				// se há resultados, os escreve em uma tabela
				if (count($resultados)> 0){
					foreach($resultados as $valor){
						$questao = $valor['QUESTAO'];
						$alternativa_correta= $valor['ALTERNATIVA_CORRETA'];
						$atividade = $valor['ATIVIDADE'];
						$unidade = $valor['UNIDADE'];
						$curso = $valor['CURSO'];
						$descricao_atividade = utf8_encode($valor['DESC_ATIVIDADE']);
						$descricao_unidade = utf8_encode($valor['DESC_UNIDADE']);
						$nota = $valor['NOTA_ATIVIDADE'];
						$valorAtividade = $valor['VALOR_ATIVIDADE'];
						$qtd_acertos = $qtd_acertos + $nota;
						
						
							$html.= "
									
									<tr>
										<td class='Normal' colspan='2'>".$descricao_unidade."</td>
										<td class='Normal'>".$descricao_atividade."</td>
										<td class='Normal'>".$valorAtividade."</td>
										<td class='Normal'>".$nota."</td>
									</tr>";
						
						
					}	
				}else{		
					$html.= "
						
						<tr>
							<td class='Normal' colspan='5'>Nenhum registro encontrado</td>
						</tr>";
				}
			} //try
			catch (PDOException $e)
			{
				// caso ocorra uma exceção, exibe na tela
				echo "Erro!: " . $e->getMessage() . "<br>";
				die();
			}


	

	if($qtdVideos == $qtdVideosAluno){
		
		$porcentagemQuestoesRespAluno = round((($qtdQuestoesAluno * 100) / $qtdQuestoes ));
	
		if($porcentagemQuestoesRespAluno  >= 70){
			
			$porcentagemAcertosAluno = round((($qtd_acertos * 100) / $qtdQuestoes ));
			
			if($porcentagemAcertosAluno  >= 70){
				
				$aprovado = "APROVADO";
			}
		}
		
	}		
				setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
				date_default_timezone_set('America/Sao_Paulo');
				$data = strftime('%d de %B de %Y', strtotime('today'));
				
				$html.= "
				
					<tr>
						<td class='trSubTituloAP1'><strong>Indicador: </strong></td>
						<td class='trSubTituloAP2' colspan='4'><strong>".$aprovado."</strong></td>
					</tr>
					<tr>
						<td class='trData' colspan='5'>Uberaba - MG, ".$data."</td>
					</tr>
				</table>";
				
				
			
	
	 
	$mpdf=new mPDF('utf-8', 'A4-P'); 
	$mpdf->SetDisplayMode('fullpage');
	$css = file_get_contents("../../css/relatorio.css");
	$mpdf->WriteHTML($css,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output();

	exit;