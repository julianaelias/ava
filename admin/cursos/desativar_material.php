<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$material = htmlspecialchars($_POST['material']);
$unidade = htmlspecialchars($_POST['unidade']);
$curso = htmlspecialchars($_POST['curso']);

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($material) && !empty($unidade)){
			$SQL = "	UPDATE MATERIAIS 
							SET STATUS = 2
							WHERE 	MATERIAL = :MATERIAL
									AND UNIDADE = :UNIDADE";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($material)){			
			$operacao->bindParam(':MATERIAL', $material, PDO::PARAM_INT);
		}
		if(!empty($unidade)){			
			$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		}
		
		
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Material desativado com sucesso.|');
			
		}else{
		  die('2|Erro ao desativar o material.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>