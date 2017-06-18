<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$atividade = htmlspecialchars($_POST['atividade']);
$curso = htmlspecialchars($_POST['curso']);
$unidade = htmlspecialchars($_POST['unidade']);
$inscricao = htmlspecialchars($_POST['inscricao']);

// QTD DE QUESTÕES RESPONDIDAS
try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
		$sqlSelect="	SELECT COUNT(QU.QUESTAO) AS QTD_QUESTOES
						FROM QUESTOES_USUARIOS QU
						JOIN ATIVIDADES A ON A.ATIVIDADE = QU.ATIVIDADE
						JOIN QUESTOES Q ON Q.ATIVIDADE = A.ATIVIDADE AND Q.QUESTAO = QU.QUESTAO
						JOIN INSCRICOES I ON I.USUARIO = QU.USUARIO
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
							FROM QUESTOES Q
							JOIN ATIVIDADES A ON A.ATIVIDADE = Q.ATIVIDADE
							JOIN UNIDADES U ON U.UNIDADE = A.UNIDADE
							JOIN CURSOS C ON C.CURSO = U.CURSO
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

 if($qtdQuestoesAtividade == $qtdQuestoesMarcada){
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
			$sqlSelect="	SELECT QU.QUESTAO
							FROM QUESTOES_USUARIOS QU
							JOIN ATIVIDADES A ON A.ATIVIDADE = QU.ATIVIDADE
							JOIN QUESTOES Q ON Q.ATIVIDADE = A.ATIVIDADE AND Q.QUESTAO = QU.QUESTAO
							JOIN INSCRICOES I ON I.USUARIO = QU.USUARIO
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
			
				$SQL = "	UPDATE QUESTOES_USUARIOS
							SET 
								FINALIZADO = 1
							WHERE ATIVIDADE = :ATIVIDADE
							AND QUESTAO = :QUESTAO
							AND INSCRICAO = :INSCRICAO
							AND USUARIO = :USUARIO";
							
				//prepara a execução da sentença
				$operacao = $conexao->prepare($SQL);
				
				//pdo
				$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);			
				$operacao->bindParam(':QUESTAO', $questao, PDO::PARAM_INT);
				$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
				$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
				
			
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