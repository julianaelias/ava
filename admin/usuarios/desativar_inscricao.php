<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$inscricao = htmlspecialchars($_POST['inscricao']);
$usuario = htmlspecialchars($_POST['usuario']);

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($inscricao) && !empty($usuario)){
			$SQL = "	UPDATE INSCRICOES 
							SET SITUACAO = 2
							WHERE INSCRICAO = :INSCRICAO
							AND USUARIO = :USUARIO";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
			
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
			
		$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
	
		
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Inscrição cancelada com sucesso.|');
			
		}else{
		  die('2|Erro ao cancelar a inscrição.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>