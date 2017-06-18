<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$curso = htmlspecialchars($_POST['curso']);
$titulo = utf8_decode(htmlspecialchars($_POST['titulo']));
$descricao = utf8_decode(htmlspecialchars($_POST['descricao']));
$categoria = htmlspecialchars($_POST['categoria']);
$professor = utf8_decode(htmlspecialchars($_POST['professor']));
$palavras = utf8_decode(htmlspecialchars($_POST['palavras']));
/*$titulo = "'".$titulo."'";
$descricao = "'".$descricao."'";
$professor = "'".$professor."'";
$palavras = "'".$palavras."'";*/
//$categoria = "'".$categoria."'";

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(empty($curso)){
			
			 //pdo
			$SQL = "INSERT INTO CURSOS (TITULO, DESCRICAO, CATEGORIA, PROFESSOR, PALAVRAS)
					VALUES (:TITULO, :DESCRICAO, :CATEGORIA, :PROFESSOR, :PALAVRAS)";			
			
			//arry			
			/*$SQL = "	INSERT INTO CURSOS (TITULO, DESCRICAO, CATEGORIA, PROFESSOR, PALAVRAS)
						VALUES (?, ?, ?, ?, ?)";	*/
		
		}else{
			$SQL = "	UPDATE CURSOS 
							SET TITULO = :TITULO, 
							DESCRICAO = :DESCRICAO, 
							CATEGORIA = :CATEGORIA, 
							PROFESSOR = :PROFESSOR, 
							PALAVRAS = :PALAVRAS, 
							STATUS = 1
							WHERE CURSO = :CURSO";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($curso)){			
			$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		}
		
		//pdo
		$operacao->bindParam(':TITULO', $titulo,  PDO::PARAM_INT);
		$operacao->bindParam(':DESCRICAO', $descricao,  PDO::PARAM_INT);
		$operacao->bindParam(':CATEGORIA', $categoria,  PDO::PARAM_INT);
		$operacao->bindParam(':PROFESSOR', $professor,  PDO::PARAM_INT);
		$operacao->bindParam(':PALAVRAS', $palavras,  PDO::PARAM_INT);
		
		$operacao->execute();
		
         // arry 
		/* $gravar = $operacao->execute(array($titulo, $descricao, $categoria, $professor, $palavras));*/
  
		
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