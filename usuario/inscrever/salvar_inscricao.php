<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);
$usuario = htmlspecialchars($_POST['usuario']);

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($curso) && !empty($usuario)){
			$SQL = "	INSERT INTO INSCRICOES (CURSO, USUARIO, SITUACAO, DATA)
					VALUES (:CURSO, :USUARIO, 1, now())";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		
		$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
		
		
		$operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Inscrição realizada com sucesso.|');
			
		}else{
		  die('2|Erro ao ao realizar a inscrição.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>