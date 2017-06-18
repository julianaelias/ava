<?php 
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include("../../mpdf60/mpdf.php");

$curso = $_GET['curso'];
$inscricao = $_GET['inscricao'];
$qtd_acertos = 0;

	//QTD VIDEOS DO CURSO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT COUNT(M.MATERIAL) AS QTD_VIDEOS
						FROM MATERIAIS M
						JOIN UNIDADES U ON U.UNIDADE = M.UNIDADE
						JOIN CURSOS C ON C.CURSO = U.CURSO
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
				$qtdVideos = utf8_encode($valor['QTD_VIDEOS']);
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
						FROM MATERIAIS M
						JOIN UNIDADES U ON U.UNIDADE = M.UNIDADE
						JOIN CURSOS C ON C.CURSO = U.CURSO
						JOIN MATERIAIS_USUARIOS MU ON MU.MATERIAL = M.MATERIAL
						JOIN INSCRICOES I ON I.INSCRICAO = MU.INSCRICAO AND I.USUARIO = MU.USUARIO
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
				$qtdVideosAluno = utf8_encode($valor['QTD_VIDEOS_ALUNO']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	
	//QTD QUESTÕES DO CURSO
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "	SELECT COUNT(Q.QUESTAO) AS QTD_QUESTOES
						FROM QUESTOES Q
						JOIN ATIVIDADES A ON A.ATIVIDADE = Q.ATIVIDADE
						JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
						JOIN CURSOS C ON C.CURSO = U.CURSO
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
				$qtdQuestoes = utf8_encode($valor['QTD_QUESTOES']);
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
							FROM QUESTOES_USUARIOS QU
							JOIN QUESTOES Q ON Q.QUESTAO = QU.QUESTAO
							JOIN ATIVIDADES A ON A.ATIVIDADE = QU.ATIVIDADE
							JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
							JOIN CURSOS C ON C.CURSO = U.CURSO
							JOIN INSCRICOES I ON I.INSCRICAO = QU.INSCRICAO AND I.USUARIO = QU.USUARIO
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
		$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$qtdQuestoesAluno = utf8_encode($valor['QTD_QUESTOES_ALUNO']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
	
	
	
			
			//QUESTÕES CORRETAS DO CURSO
			try{
				// instancia objeto PDO, conectando no mysql
				$conexao = conn_mysql();
				
				// instrução SQL básica
				$SQLSelect = "	SELECT 	Q.QUESTAO,
										Q.ALTERNATIVA_CORRETA,
										Q.ATIVIDADE,
										U.UNIDADE,
										C.CURSO
										FROM QUESTOES Q
										JOIN ATIVIDADES A ON A.ATIVIDADE = Q.ATIVIDADE
										JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
										JOIN CURSOS C ON C.CURSO = U.CURSO
										WHERE Q.STATUS = 1
										AND A.STATUS = 1
										AND U.STATUS = 1
										AND C.STATUS = 1
										AND C.CURSO = :CURSO
										ORDER BY U.UNIDADE, Q.ATIVIDADE, Q.QUESTAO";
								
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
						$questao = $valor['QUESTAO'];
						$alternativa_correta= utf8_encode($valor['ALTERNATIVA_CORRETA']);
						$atividade = $valor['ATIVIDADE'];
						$unidade = $valor['UNIDADE'];
						$curso = $valor['CURSO'];
						
						
						//QUESTÃO RESPONDIDA PELO ALUNO
						try{
							// instancia objeto PDO, conectando no mysql
							$conexao = conn_mysql();
							
							// instrução SQL básica
							$SQLSelect = "	SELECT 	QU.QUESTAO,
													QU.ALTERNATIVA_MARCADA,
													QU.ATIVIDADE,
													QU.INSCRICAO,
													QU.USUARIO,
													U.UNIDADE,
													C.CURSO
											FROM QUESTOES_USUARIOS QU
											JOIN QUESTOES Q ON Q.QUESTAO = QU.QUESTAO
											JOIN ATIVIDADES A ON A.ATIVIDADE = QU.ATIVIDADE
											JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
											JOIN CURSOS C ON C.CURSO = U.CURSO
											JOIN INSCRICOES I ON I.INSCRICAO = QU.INSCRICAO AND I.USUARIO = QU.USUARIO
											WHERE Q.STATUS = 1
											AND A.STATUS = 1
											AND U.STATUS = 1
											AND C.STATUS = 1
											AND I.SITUACAO = 1
											AND C.CURSO = :CURSO									
											AND I.INSCRICAO = :INSCRICAO
											AND I.USUARIO = :USUARIO
											AND A.ATIVIDADE = :ATIVIDADE
											AND U.UNIDADE = :UNIDADE
											AND Q.QUESTAO = :QUESTAO
											AND QU.FINALIZADO = 1
											ORDER BY U.UNIDADE, QU.ATIVIDADE, QU.QUESTAO";
					
											
							//prepara a execução da sentença
							$operacao = $conexao->prepare($SQLSelect);
							
							$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
							$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
							$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
							$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
							$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
							$operacao->bindParam(':QUESTAO', $questao, PDO::PARAM_INT);
							
							$pesquisar = $operacao->execute();
						
							//captura TODOS os resultados obtidos
							$resultados = $operacao->fetchAll();
							
							// fecha a conexão (os resultados já estão capturados)
							$conexao = null;
						
							// se há resultados, os escreve em uma tabela
							if (count($resultados)> 0){
								foreach($resultados as $valor){
									$alternativa_aluno = utf8_encode($valor['ALTERNATIVA_MARCADA']);
								}	
							}
						} //try
						catch (PDOException $e)
						{
							// caso ocorra uma exceção, exibe na tela
							echo "Erro!: " . $e->getMessage() . "<br>";
							die();
						}
						
						
			
			
						if($alternativa_correta == $alternativa_aluno){
							
							$qtd_acertos++;
							
						}
						
						
					}	
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
				
				
				//DADOS CERTIFICADO
				try{
					// instancia objeto PDO, conectando no mysql
					$conexao = conn_mysql();
					
					// instrução SQL básica
					$SQLSelect = "	SELECT 	C.CURSO,
											C.TITULO,
											U.USUARIO,
											U.NOME
									FROM USUARIOS U
									JOIN INSCRICOES I ON I.USUARIO = U.USUARIO
									JOIN CURSOS C ON C.CURSO = I.CURSO
									WHERE U.USUARIO = :USUARIO
										AND C.CURSO = :CURSO
										AND I.INSCRICAO = :INSCRICAO";
									
					//prepara a execução da sentença
					$operacao = $conexao->prepare($SQLSelect);
					
					$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
					$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
					$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
					
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
				
				setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
				date_default_timezone_set('America/Sao_Paulo');
				$data = strftime('%d de %B de %Y', strtotime('today'));
				
				$html = "
					   <table class='tabela'>
							<tr>
								<td class='trCertificado'>CERTIFICADO</td>
							</tr>
							<tr>
								<td class='trNormal'>Certificamos que <strong>".$nomeC."</strong>, concluiu o curso ".$cursoC." - <strong>".$tituloCursoC."</strong>, ofertado pelo AVA - Ambiente Virtual de Aprendizagem.</td>
							</tr>
							<tr>
								<td class='trData'>Uberaba - MG, ".$data."</td>
							</tr>
						</table>
						";
				
				
			}else{
				
				$html = "<h1>Você não está apto a receber o certificado do curso. Falta atingir pelo menos 70% de acertos nas atividades.</h1>";
			}
			
			
			
			
		}else{
			$html = "<h1>Você não está apto a receber o certificado do curso. Existem questões que devem ser respondidas e ou atividades a serem finalizadas.</h1>";
		}
		
		
	}else{
		$html = "<h1>Você não está apto a receber o certificado do curso. Existem vídeos que ainda não foram assistidos.</h1>";
	}
	
	 
	$mpdf=new mPDF('utf-8', 'A4-L'); 
	$mpdf->SetDisplayMode('fullpage');
	$css = file_get_contents("../../css/certificado.css");
	$mpdf->WriteHTML($css,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output();

	exit;