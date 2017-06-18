<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);
$unidade = htmlspecialchars($_POST['unidade']);
$atividade = htmlspecialchars($_POST['atividade']);

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($atividade) && !empty($unidade)){
			$SQL = "	UPDATE ATIVIDADES 
							SET STATUS = 2
							WHERE ATIVIDADE = :ATIVIDADE
							AND UNIDADE = :UNIDADE";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($curso)){			
			$operacao->bindParam(':ATIVIDADE', $atividade, PDO::PARAM_INT);
		}
		if(!empty($unidade)){			
			$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		}
		
		
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Atividade desativada com sucesso.|');
			
		}else{
		  die('2|Erro ao desativar a unidade.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>