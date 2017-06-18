<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$comentario = '';
$curso = htmlspecialchars($_POST['curso']);
$nota = htmlspecialchars($_POST['nota']);
$inscricao = htmlspecialchars($_POST['inscricao']);
$usuario = htmlspecialchars($_POST['usuario']);
$avaliacao = htmlspecialchars($_POST['avaliacao']);
$comentario = utf8_decode(htmlspecialchars($_POST['comentario']));

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(empty($avaliacao)){
			
			
			 //pdo
			$SQL = "INSERT INTO AVALIACOES (NOTA, CURSO, INSCRICAO, USUARIO, COMENTARIO, DATA)
					VALUES (:NOTA, :CURSO, :INSCRICAO, :USUARIO, :COMENTARIO, now())";			
		
		}else{
			$SQL = "	UPDATE AVALIACOES 
							SET NOTA= :NOTA, 
							COMENTARIO = :COMENTARIO,
							DATA = now()
							WHERE AVALIACAO = :AVALIACAO
							AND CURSO = :CURSO
							AND INSCRICAO = :INSCRICAO
							AND USUARIO = :USUARIO";
							
			
							
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		//pdo	
		
		if(!empty($avaliacao)){	
			$operacao->bindParam(':AVALIACAO', $avaliacao, PDO::PARAM_INT);
		}
		$operacao->bindParam(':NOTA', $nota, PDO::PARAM_INT);
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
		$operacao->bindParam(':COMENTARIO', $comentario, PDO::PARAM_INT);
	
	    $operacao->execute();
		
		$gravar = $operacao->rowCount();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		 
		if(!empty($gravar)){
		  die('1|Avaliação salva com sucesso.|');
			
		}else{
		  die('2|Erro ao salvar a avaliação.|');
		}
	
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}
?>