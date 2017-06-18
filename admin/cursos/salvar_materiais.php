<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
set_time_limit(0);

$curso = htmlspecialchars($_POST['cursoM']);
$unidade = htmlspecialchars($_POST['unidadeM']);
$material = utf8_decode(htmlspecialchars($_POST['materialM']));
$arquivoAntigo = utf8_decode(htmlspecialchars($_POST['arquivoAntigo']));
$titulo = utf8_decode(htmlspecialchars($_POST['tituloM']));
$descricao = utf8_decode(htmlspecialchars($_POST['descricaoM']));
$tipo = utf8_decode(htmlspecialchars($_POST['tipoM']));
$link = utf8_decode(htmlspecialchars($_POST['linkM']));

$content = $_FILES['arquivoM']['type'];
$arquivo = $_FILES['arquivoM']['name'];
$arquivo_envia 	= $_FILES['arquivoM']['tmp_name'];
$tamanhoArquivo	= $_FILES['arquivoM']['size'];
$arquivoReverse = strrev($arquivo);
$tipoArquivo = strrev(substr($arquivoReverse,0,strpos($arquivoReverse,".")));
$tipoArquivo = strtolower($tipoArquivo); 

if(!empty($arquivo)){
	
	$arquivo = utf8_decode(htmlspecialchars($arquivo));
	$tempNome = explode(".", basename($arquivo));
	$extensao = end($tempNome);
	if($tipo == 1){
		$tipoCaminho = "arquivos/";	
	}else if($tipo == 3){
		$tipoCaminho = "podcast/";
	}

	$dirUploads = "../../uploads/".$tipoCaminho;
	if(!file_exists ( $dirUploads ))
		mkdir($dirUploads, 0500);  //permissao de leitura e execucao
	
	$caminhoUpload = $dirUploads;
	if(!file_exists ( $caminhoUpload ))
		mkdir($caminhoUpload, 0700);  //permissoes de escrita, leitura e execucao
	
	$tempNome = explode($extensao, basename($arquivo));
	$nomeArquivo=$tempNome[0];
		  
		 
	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		$sqlMat="	SELECT MAX(MATERIAL) AS MATERIAL
				 FROM materiais 
				 WHERE STATUS = 1";
					
		//prepara a execução da sentença
		$operacao = $conexao->prepare($sqlMat);
			
		$pesquisar = $operacao->execute();
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		
			foreach($resultados as $valor){
			
				$codMat = utf8_encode($valor['MATERIAL']);
			}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		die("3|Erro: " . $e->getMessage() . "|");
	}	
	  if (empty($codMat)){
		  $codMat = 1;
	  }
	  $codMat++;
	  $nomeArquivo= $nomeArquivo.'_'.$codMat;
	  $nomeArquivo= $nomeArquivo.'.'.$extensao;
	  $pathCompleto = $caminhoUpload. $nomeArquivo;
	  if(move_uploaded_file($_FILES["arquivoM"]["tmp_name"], $pathCompleto)){
		  
	  }else{
		 die('4|Erro ao gravar o arquivo.|'); 
	  }
			
}

	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($arquivo)){
			if(empty($material)){
				$SQL = "	INSERT INTO MATERIAIS (TITULO, DESCRICAO, TIPO, UNIDADE, ARQUIVO, TIPOARQUIVO, TAMANHOARQUIVO, STATUS) VALUES (:TITULO, :DESCRICAO, :TIPO, :UNIDADE, :ARQUIVO, :TIPOA, :TAMANHOA, 1)";			
				
			}else{
				$SQL = "	UPDATE MATERIAIS
							SET TITULO = :TITULO, 
							DESCRICAO = :DESCRICAO, 
							TIPO = :TIPO,
							ARQUIVO = :ARQUIVO,
							TIPOARQUIVO = :TIPOA,
							TAMANHOARQUIVO = :TAMANHOA,
							UNIDADE = :UNIDADE,
							STATUS = 1
							WHERE MATERIAL = :MATERIAL";
							
				unlink($caminhoUpload.$arquivoAntigo);
				
			}
		}else{
			if(!empty($link)){				
				$SQL = "	INSERT INTO MATERIAIS (TITULO, DESCRICAO, TIPO, UNIDADE, LINK, STATUS)
							VALUES (:TITULO, :DESCRICAO, :TIPO, :UNIDADE, :LINK, 1)";	
			}else{
				
				$SQL = "	UPDATE MATERIAIS
							SET TITULO = :TITULO, 
							DESCRICAO = :DESCRICAO, 
							TIPO = :TIPO,
							LINK = :LINK,
							STATUS = 1
							WHERE MATERIAL = :MATERIAL";
				
			}
		}
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQL);
		
		
		if(!empty($material)){			
			$operacao->bindParam(':MATERIAL', $material, PDO::PARAM_INT);
		}
		
		//pdo
		$operacao->bindParam(':TITULO', $titulo,  PDO::PARAM_STR);
		$operacao->bindParam(':DESCRICAO', $descricao,  PDO::PARAM_INT);
		$operacao->bindParam(':TIPO', $tipo,  PDO::PARAM_INT);
		$operacao->bindParam(':UNIDADE', $unidade,  PDO::PARAM_INT);
		
		if(!empty($arquivo)){
			$operacao->bindParam(':ARQUIVO', $nomeArquivo,  PDO::PARAM_LOB);
			$operacao->bindParam(':TIPOA', $content,  PDO::PARAM_STR);
			$operacao->bindParam(':TAMANHOA', $tamanhoArquivo,  PDO::PARAM_INT);			
		} else if(!empty($link)){
			$operacao->bindParam(':LINK', $link,  PDO::PARAM_STR);
		}
		
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