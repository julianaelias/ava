<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$atividade = htmlspecialchars($_POST['atividade']);
$curso = htmlspecialchars($_POST['curso']);
$unidade = htmlspecialchars($_POST['unidade']);
$inscricao = htmlspecialchars($_POST['inscricao']);
$acertos = 0;
$pontos = 0;

// QTD DE QUESTÕES RESPONDIDAS
try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
		$sqlSelect="	SELECT COUNT(QU.QUESTAO) AS QTD_QUESTOES
						FROM questoes_usuarios QU
						JOIN atividades A ON A.ATIVIDADE = QU.ATIVIDADE
						JOIN questoes Q ON Q.ATIVIDADE = A.ATIVIDADE AND Q.QUESTAO = QU.QUESTAO
						JOIN inscricoes I ON I.USUARIO = QU.USUARIO
						WHERE  A.ATIVIDADE = :ATIVIDADE
						AND I.INSCRICAO = :INSCRICAO
						AND I.CURSO = :CURSO
						AND QU.ALTERNATIVA_MARCADA IS NOT NULL";
					
	//prepara a execução da sentença
	$operacao = $conexao->prepare($sqlSelect);
	
	$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
	$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
	$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		 
		  
	$pesquisar = $operacao->execute();
	
	//captura TODOS os resultados obtidos
	$resultados = $operacao->fetchAll();
	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	
	foreach($resultados as $valor){
	
		$qtdQuestoesMarcada = $valor['QTD_QUESTOES'];
		
	}
	
} //try
catch (PDOException $e)
{
	// caso ocorra uma exceção, exibe na tela
	echo "Erro!: " . $e->getMessage() . "<br>";
	die();
} 


// QTD DE QUESTÕES DA ATIVIDADE
try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
		$sqlSelect="	SELECT COUNT(Q.QUESTAO) AS QTD_QUESTOES_ATIVIDADE
							FROM questoes Q
							JOIN atividades A ON A.ATIVIDADE = Q.ATIVIDADE
							JOIN unidades U ON U.UNIDADE = A.UNIDADE
							JOIN cursos C ON C.CURSO = U.CURSO
							WHERE Q.STATUS = 1
							AND A.STATUS = 1
							AND U.STATUS = 1
							AND C.STATUS = 1
							AND C.CURSO = :CURSO
							AND A.ATIVIDADE = :ATIVIDADE";
					
	//prepara a execução da sentença
	$operacao = $conexao->prepare($sqlSelect);
	
	$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
	$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
		 
		  
	$pesquisar = $operacao->execute();
	
	//captura TODOS os resultados obtidos
	$resultados = $operacao->fetchAll();
	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	
	foreach($resultados as $valor){
	
		$qtdQuestoesAtividade = $valor['QTD_QUESTOES_ATIVIDADE'];
		
	}
	
} //try
catch (PDOException $e)
{
	// caso ocorra uma exceção, exibe na tela
	echo "Erro!: " . $e->getMessage() . "<br>";
	die();
} 

//NOVO

		//QUESTÕES CORRETAS DO CURSO
			try{
				// instancia objeto PDO, conectando no mysql
				$conexao = conn_mysql();
				
				// instrução SQL básica
				$SQLSelect = "	SELECT 	Q.QUESTAO,
										Q.ALTERNATIVA_CORRETA,
										Q.ATIVIDADE,
										U.UNIDADE,
										C.CURSO,
										A.DESCRICAO AS DESC_ATIVIDADE,
										U.DESCRICAO AS DESC_UNIDADE
										FROM questoes Q
										JOIN atividades A ON A.ATIVIDADE = Q.ATIVIDADE
										JOIN unidades U ON U.UNIDADE = A.UNIDADE
										JOIN cursos C ON C.CURSO = U.CURSO
										WHERE Q.STATUS = 1
										AND A.STATUS = 1
										AND U.STATUS = 1
										AND C.STATUS = 1
										AND C.CURSO = :CURSO
										AND Q.ATIVIDADE = :ATIVIDADE 
										ORDER BY U.UNIDADE, Q.ATIVIDADE, Q.QUESTAO";
								
				//prepara a execução da sentença
				$operacao = $conexao->prepare($SQLSelect);
				
				$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
				$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
				
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
											AND A.ATIVIDADE = :ATIVIDADE
											AND U.UNIDADE = :UNIDADE
											AND Q.QUESTAO = :QUESTAO
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
								
									$alternativa_aluno = $valor['ALTERNATIVA_MARCADA'];
									$atividade_aluno = $valor['ATIVIDADE'];
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
							
								$acertos++;
							
							}
							
							 $pontos++;
						
						
					}	
				}
			} //try
			catch (PDOException $e)
			{
				// caso ocorra uma exceção, exibe na tela
				echo "Erro!: " . $e->getMessage() . "<br>";
				die();
			}
			
		

//FIM NOVO

 if($qtdQuestoesAtividade == $qtdQuestoesMarcada){
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
			$sqlSelect="	SELECT QU.QUESTAO
							FROM questoes_usuarios QU
							JOIN atividades A ON A.ATIVIDADE = QU.ATIVIDADE
							JOIN questoes Q ON Q.ATIVIDADE = A.ATIVIDADE AND Q.QUESTAO = QU.QUESTAO
							JOIN inscricoes I ON I.USUARIO = QU.USUARIO
							WHERE  A.ATIVIDADE = :ATIVIDADE
							AND I.INSCRICAO = :INSCRICAO
							AND I.CURSO = :CURSO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($sqlSelect);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
			 
			  
		$pesquisar = $operacao->execute();
		
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		
	
		
		foreach($resultados as $valor){
		
			$questao = $valor['QUESTAO'];
			
			try{
				// instancia objeto PDO, conectando no mysql
				$conexao = conn_mysql();
				
				
			
				$SQL = "	UPDATE questoes_usuarios
							SET 
								FINALIZADO = 1,
								NOTA_ATIVIDADE = :NOTA,
								VALOR_ATIVIDADE = :VALOR								
							WHERE ATIVIDADE = :ATIVIDADE
							AND QUESTAO = :QUESTAO
							AND INSCRICAO = :INSCRICAO
							AND USUARIO = :USUARIO";
							
				//prepara a execução da sentença
				$operacao = $conexao->prepare($SQL);
				
				//print_r($operacao->errorInfo());
				
				//pdo
				$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);			
				$operacao->bindParam(':QUESTAO', $questao, PDO::PARAM_INT);
				$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
				$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
				$operacao->bindParam(':NOTA', $acertos, PDO::PARAM_INT);
				$operacao->bindParam(':VALOR', $qtdQuestoesAtividade, PDO::PARAM_INT);
				
			
				$operacao->execute();
				
				$gravar = $operacao->rowCount();
	

	
				// fecha a conexão (os resultados já estão capturados)
				$conexao = null;
			

	
			} //try
			catch (PDOException $e)
			{
				// caso ocorra uma exceção, exibe na tela
				die("3|Erro: " . $e->getMessage() . "|");
			}
			
		}
		
		if(!empty($gravar)){
		  die('1|Atividade Finalizada com sucesso.|');
			
		}else{
		  die('2|Erro ao finalizar a atividade.|');
		}
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	} 
 }else{
	 die('2|Não foi possível finalizar a atividade. Existem questões que não foram respondidas.|');
	 
 }
?>