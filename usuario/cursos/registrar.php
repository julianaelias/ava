<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
set_time_limit(0);

$unidade = htmlspecialchars($_POST['unidade']);
$material = htmlspecialchars($_POST['material']);
$inscricao= htmlspecialchars($_POST['inscricao']);
$curso= htmlspecialchars($_POST['curso']);



try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
		$sqlQtd="	SELECT COUNT(MU.MATERIAL) AS QTD 
					 FROM  MATERIAIS_USUARIOS MU
                     JOIN MATERIAIS M ON M.MATERIAL = MU.MATERIAL
                     JOIN UNIDADES U ON U.UNIDADE = M.UNIDADE
                     JOIN INSCRICOES I ON I.CURSO = U.CURSO AND I.USUARIO = MU.USUARIO
					 WHERE MU.MATERIAL = :MATERIAL 
					 AND M.UNIDADE = :UNIDADE
                     AND I.INSCRICAO = :INSCRICAO
					 AND U.CURSO = :CURSO";
					
	//prepara a execução da sentença
	$operacao = $conexao->prepare($sqlQtd);
	
	if(!empty($material)){
		$operacao->bindParam(':MATERIAL', $material, PDO::PARAM_INT);
	}	
	if(!empty($unidade)){
		$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		
	}
	if(!empty($inscricao)){
		$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
		
	}
	if(!empty($curso)){
		$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		
	}

	 
		  
	$pesquisar = $operacao->execute();
	
	//captura TODOS os resultados obtidos
	$resultados = $operacao->fetchAll();
	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	
	foreach($resultados as $valor){
	
		$QT = $valor['QTD'];
	}
	
				
	if($QT < 1){
			
		
		  
		  try{
				// instancia objeto PDO, conectando no mysql
				$conexao = conn_mysql();	
				
				$SQL = "INSERT INTO MATERIAIS_USUARIOS (USUARIO, INSCRICAO, MATERIAL, DATA)
				VALUES (:USUARIO, :INSCRICAO, :MATERIAL, now())";		
					
					
				//prepara a execução da sentença
				$operacao = $conexao->prepare($SQL);
				
			    $operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
				$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);	
				$operacao->bindParam(':MATERIAL', $material, PDO::PARAM_INT);	
				
				$operacao->execute();
				
				$gravar = $operacao->rowCount();
				
				
				// fecha a conexão (os resultados já estão capturados)
				$conexao = null;	
					
			} //try
			catch (PDOException $e)
			{
				// caso ocorra uma exceção, exibe na tela
				die("3|Erro: " . $e->getMessage() . "|");
			}
	  }
		
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	} 
	