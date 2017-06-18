<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
set_time_limit(0);
	
#DOWNLOAD DO MATERIAL

try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
		$sqlQtd="	SELECT 
						ARQUIVO,
						TIPO,
						TIPOARQUIVO,
						TAMANHOARQUIVO
					 FROM materiais 
					 WHERE MATERIAL = :MATERIAL 
					 AND UNIDADE = :UNIDADE
					 AND STATUS = 1";
					
	//prepara a execução da sentença
	$operacao = $conexao->prepare($sqlQtd);
	
	if(!empty($material)){
		$operacao->bindParam(':MATERIAL', $material, PDO::PARAM_INT);
	}	
	if(!empty($unidade)){
		$operacao->bindParam(':UNIDADE', $unidade, PDO::PARAM_INT);
		
	}


	
	$pesquisar = $operacao->execute();
	//captura TODOS os resultados obtidos
	$resultados = $operacao->fetchAll();
	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	
		foreach($resultados as $valor){
					
			$nomeArquivoM = utf8_encode($valor['ARQUIVO']);
			$tamanhoArquivoM = utf8_encode($valor['TAMANHOARQUIVO']);
			$tipoArquivoM = $valor['TIPOARQUIVO'];
			$tipoM = utf8_encode($valor['TIPO']);
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	} 

		

$nomeArquivo = $nomeArquivoM;
$file = fopen($nomeArquivo,"a+");

if($tipoM == 1){
		$tipoCaminho = "arquivos/";	
	}else if($tipoM == 3){
		$tipoCaminho = "podcast/";
	}

	$dirUploads = "../../uploads/".$tipoCaminho;
	

fwrite($file, hex2bin($dirUploads));
fclose($file);

//Forçando o download...
header("Content-type: application/pdf");
header("Content-Disposition: attachment; filename=" . $nomeArquivo);
header("Content-Length: " . $tamanhoArquivoM);
header("Content-Transfer-Encoding: binary");
readfile($dirUploads.$nomeArquivo);

//Apagando o arquivo
unlink($nomeArquivo); 