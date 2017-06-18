<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$atividade = htmlspecialchars($_POST['atividade']);
$questao = htmlspecialchars($_POST['questao']);
$alternativa = htmlspecialchars($_POST['alternativa']);
$inscricao = htmlspecialchars($_POST['inscricao']);
$contador = htmlspecialchars($_POST['contador']);


try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
		$sqlQtd="	SELECT COUNT(QU.ALTERNATIVA_MARCADA) AS QTD 
					 FROM  QUESTOES_USUARIOS QU
                     JOIN ATIVIDADES A ON A.ATIVIDADE = QU.ATIVIDADE
                     JOIN QUESTOES Q ON Q.ATIVIDADE = A.ATIVIDADE AND Q.QUESTAO = QU.QUESTAO
                     JOIN INSCRICOES I ON I.USUARIO = QU.USUARIO
					 WHERE Q.QUESTAO = :QUESTAO
					 AND A.ATIVIDADE = :ATIVIDADE
                     AND I.INSCRICAO = :INSCRICAO";
					
	//prepara a execução da sentença
	$operacao = $conexao->prepare($sqlQtd);
	
	$operacao->bindParam(':QUESTAO', $questao, PDO::PARAM_INT);
	$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
	$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		 
		  
	$pesquisar = $operacao->execute();
	
	//captura TODOS os resultados obtidos
	$resultados = $operacao->fetchAll();
	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	
	foreach($resultados as $valor){
	
		$QT = $valor['QTD'];
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
	if($QT < 1){
		
		 //pdo
		$SQL = "	INSERT QUESTOES_USUARIOS
					(	
						ALTERNATIVA_MARCADA, 
						ATIVIDADE, 
						DATA,
						INSCRICAO, 
						QUESTAO, 
						USUARIO
					)
					VALUES 
					(	:ALTERNATIVA_MARCADA, 
						:ATIVIDADE, 
						now(),
						:INSCRICAO, 
						:QUESTAO, 
						:USUARIO					
					)";	
				
	
	}else{
		$SQL = "	UPDATE QUESTOES_USUARIOS
						SET 
							ALTERNATIVA_MARCADA = :ALTERNATIVA_MARCADA,
							DATA = now()
						WHERE ATIVIDADE = :ATIVIDADE
						AND QUESTAO = :QUESTAO
						AND INSCRICAO = :INSCRICAO
						AND USUARIO = :USUARIO";
	}
					
	//prepara a execução da sentença
	$operacao = $conexao->prepare($SQL);
	
	//pdo
	$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);			
	$operacao->bindParam(':QUESTAO', $questao, PDO::PARAM_INT);
	$operacao->bindParam(':ALTERNATIVA_MARCADA', $alternativa, PDO::PARAM_INT);
	$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
	$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
	

	$operacao->execute();
	
	$gravar = $operacao->rowCount();
	

	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	 
	if(!empty($gravar)){
	  die('1|Resposta da questão '.$contador.' salva com sucesso.|');
		
	}else{
	  die('2|Erro ao salvar a resposta da questão '.$contador.'.|');
	}

	
} //try
catch (PDOException $e)
{
	// caso ocorra uma exceção, exibe na tela
	die("3|Erro: " . $e->getMessage() . "|");
}
?>