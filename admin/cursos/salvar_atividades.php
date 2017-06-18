<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);
$unidade = htmlspecialchars($_POST['unidade']);
$atividade = htmlspecialchars($_POST['atividade']);
$titulo = utf8_decode(htmlspecialchars($_POST['titulo']));
$descricao = utf8_decode(htmlspecialchars($_POST['descricao']));
$status = utf8_decode(htmlspecialchars($_POST['status']));

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(empty($atividade)){
			
			 //pdo
			$SQL = "INSERT INTO ATIVIDADES (TITULO, DESCRICAO, UNIDADE, STATUS)
					VALUES (:TITULO, :DESCRICAO, :UNIDADE, :STATUS)";	
					
		
		}else{
			$SQL = "	UPDATE ATIVIDADES
							SET TITULO = :TITULO, 
							DESCRICAO = :DESCRICAO, 
							UNIDADE = :UNIDADE,
							STATUS = :STATUS
							WHERE ATIVIDADE = :ATIVIDADE";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($atividade)){			
			$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
		}
				
			
		
		
		//pdo
		$operacao->bindParam(':TITULO', $titulo,  PDO::PARAM_INT);
		$operacao->bindParam(':DESCRICAO', $descricao,  PDO::PARAM_INT);
		$operacao->bindParam(':STATUS', $status,  PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Dados salvos com sucesso.|');
			
		}else{
		  die('2|Erro ao salvar os dados.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>