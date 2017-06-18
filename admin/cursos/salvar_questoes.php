<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);
$unidade = htmlspecialchars($_POST['unidade']);
$atividade = htmlspecialchars($_POST['atividade']);
$questao = htmlspecialchars($_POST['questao']);
$status = htmlspecialchars($_POST['status']);
$alternativaCorreta = htmlspecialchars($_POST['alternativaCorreta']);
$enunciado = utf8_decode(htmlspecialchars($_POST['enunciado']));
$alternativa1 = utf8_decode(htmlspecialchars($_POST['alternativa1']));
$alternativa2 = utf8_decode(htmlspecialchars($_POST['alternativa2']));
$alternativa3 = utf8_decode(htmlspecialchars($_POST['alternativa3']));
$alternativa4 = utf8_decode(htmlspecialchars($_POST['alternativa4']));



	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(empty($questao)){
			
			 //pdo
			$SQL = "	INSERT INTO QUESTOES 
						(	ATIVIDADE, 
							ENUNCIADO, 
							ALTERNATIVA_1, 
							ALTERNATIVA_2, 
							ALTERNATIVA_3, 
							ALTERNATIVA_4, 
							ALTERNATIVA_CORRETA, 
							STATUS
						)
						VALUES 
						(	:ATIVIDADE, 
							:ENUNCIADO, 
							:ALTERNATIVA_1, 
							:ALTERNATIVA_2, 
							:ALTERNATIVA_3, 
							:ALTERNATIVA_4, 
							:ALTERNATIVA_CORRETA, 
							:STATUS						
						)";	
					
		
		}else{
			$SQL = "	UPDATE QUESTOES
							SET 
								ENUNCIADO = :ENUNCIADO,
								ALTERNATIVA_1 = :ALTERNATIVA_1,
								ALTERNATIVA_2 = :ALTERNATIVA_2,
								ALTERNATIVA_3 = :ALTERNATIVA_3,
								ALTERNATIVA_4 = :ALTERNATIVA_4,
								ALTERNATIVA_CORRETA =  :ALTERNATIVA_CORRETA,
								STATUS = :STATUS
							WHERE ATIVIDADE = :ATIVIDADE
							AND QUESTAO = :QUESTAO";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($atividade)){			
			$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
			
		}
		
		if(!empty($questao)){			
			$operacao->bindParam(':QUESTAO', $questao, PDO::PARAM_INT);
			
		}
				
			
	
		
		//pdo
		$operacao->bindParam(':ENUNCIADO', $enunciado,  PDO::PARAM_INT);
		$operacao->bindParam(':ALTERNATIVA_1', $alternativa1,  PDO::PARAM_INT);
		$operacao->bindParam(':ALTERNATIVA_2', $alternativa2,  PDO::PARAM_INT);
		$operacao->bindParam(':ALTERNATIVA_3', $alternativa3,  PDO::PARAM_INT);
		$operacao->bindParam(':ALTERNATIVA_4', $alternativa4,  PDO::PARAM_INT);
		$operacao->bindParam(':ALTERNATIVA_CORRETA', $alternativaCorreta,  PDO::PARAM_INT);
		$operacao->bindParam(':STATUS', $status,  PDO::PARAM_INT);
		
	
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
	
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Dados da questão salvos com sucesso.|');
			
		}else{
		  die('2|Erro ao salvar os dados da questão.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>