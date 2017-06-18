<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

$usuario = $_POST['usuario'];
$nome = utf8_decode(htmlspecialchars($_POST['nome']));
$email = utf8_decode(htmlspecialchars($_POST['email']));
$departamento = utf8_decode(htmlspecialchars($_POST['departamento']));
$senha = utf8_decode(htmlspecialchars($_POST['senha']));
$tipo = $_POST['tipo'];
$status = $_POST['status'];


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(empty($usuario)){
			
			 //pdo
			$SQL = "INSERT INTO USUARIOS (NOME, EMAIL, SENHA, DEPARTAMENTO, TIPO, STATUS)
					VALUES (:NOME, :EMAIL, :SENHA, :DEPARTAMENTO, :TIPO, :STATUS)";		
		
		     $status = 1;
		}else{
			$SQL = "	UPDATE USUARIOS 
							SET NOME = :NOME, 
							EMAIL = :EMAIL, 
							SENHA = :SENHA, 
							DEPARTAMENTO = :DEPARTAMENTO, 
							TIPO = :TIPO, 
							STATUS = :STATUS
							WHERE  USUARIO = :USUARIO";
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		if(!empty($usuario)){			
			$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
		}
		
		//pdo
		$operacao->bindParam(':NOME', $nome,  PDO::PARAM_INT);
		$operacao->bindParam(':EMAIL', $email,  PDO::PARAM_INT);
		$operacao->bindParam(':SENHA', $senha,  PDO::PARAM_INT);
		$operacao->bindParam(':DEPARTAMENTO', $departamento,  PDO::PARAM_INT);
		$operacao->bindParam(':TIPO', $tipo,  PDO::PARAM_INT);
		$operacao->bindParam(':STATUS', $status,  PDO::PARAM_INT);
		
		$operacao->execute();
		
         // arry 
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