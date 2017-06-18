<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);
$unidade = htmlspecialchars($_POST['unidade']);
$titulo = utf8_decode(htmlspecialchars($_POST['titulo']));
$descricao = utf8_decode(htmlspecialchars($_POST['descricao']));
$ordem = utf8_decode(htmlspecialchars($_POST['ordem']));

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(empty($unidade)){
			
			 //pdo
			$SQL = "INSERT INTO UNIDADES (TITULO, DESCRICAO, ORDEM, CURSO, STATUS)
					VALUES (:TITULO, :DESCRICAO, :ORDEM, :CURSO, 1)";	
		
		}else{
			$SQL = "	UPDATE UNIDADES
							SET TITULO = :TITULO, 
							DESCRICAO = :DESCRICAO, 
							ORDEM = :ORDEM,
							STATUS = 1
							WHERE CURSO = :CURSO
							AND UNIDADE = :UNIDADE";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($curso)){			
			$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		}
		if(!empty($unidade)){			
			$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		}
		
		//pdo
		$operacao->bindParam(':TITULO', $titulo,  PDO::PARAM_INT);
		$operacao->bindParam(':DESCRICAO', $descricao,  PDO::PARAM_INT);
		$operacao->bindParam(':ORDEM', $ordem,  PDO::PARAM_INT);
		
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