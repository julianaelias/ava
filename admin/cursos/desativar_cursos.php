<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($curso)){
			$SQL = "	UPDATE CURSOS 
							SET STATUS = 2
							WHERE CURSO = :CURSO";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($curso)){			
			$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		}
		
		
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Curso desativado com sucesso.|');
			
		}else{
		  die('2|Erro ao desativar o curso.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>