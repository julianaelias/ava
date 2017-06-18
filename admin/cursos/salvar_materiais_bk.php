<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
set_time_limit(0);

$curso = htmlspecialchars($_POST['cursoM']);
$unidade = htmlspecialchars($_POST['unidadeM']);
$material = utf8_decode(htmlspecialchars($_POST['materialM']));
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
			


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica	
		if(!empty($arquivo)){
			if(empty($material)){
				//lemos o  conteudo do arquivo usando afunção do PHP  file_get_contents
				$binario = file_get_contents($arquivo_envia);
				// evitamos erro de sintaxe do MySQL
				$binario = mysql_real_escape_string($binario);

				/*//SOLUÇÃO 1				
				$fp      = fopen($arquivo_envia, 'rb');
				$binario = fread($fp, filesize($arquivo_envia));
				$binario = addslashes($binario);
				fclose($fp);
				
				//SOLUÇÃO2
				$fp = fopen($arquivo_envia,"rb");
				$dados_documento = fread($fp,filesize($arquivo_envia));
				fclose($fp); 			
				$binario = bin2hex($dados_documento);*/	
 
 

				$SQL = "	INSERT INTO MATERIAIS (TITULO, DESCRICAO, TIPO, UNIDADE, ARQUIVO, NOMEARQUIVO, TIPOARQUIVO, TAMANHOARQUIVO, STATUS) VALUES (:TITULO, :DESCRICAO, :TIPO, :UNIDADE, :ARQUIVO, :NOMEA, :TIPOA, :TAMANHOA, 1)";			
				
			}else{
				$SQL = "	UPDATE MATERIAIS
							SET TITULO = :TITULO, 
							DESCRICAO = :DESCRICAO, 
							TIPO = :TIPO,
							ARQUIVO = :ARQUIVO,
							NOMEARQUIVO = :NOMEA,
							TIPOARQUIVO = :TIPOA,
							TAMANHOARQUIVO = :TAMANHOA,
							UNIDADE = :UNIDADE,
							STATUS = 1
							WHERE MATERIAL = :MATERIAL";
				
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
			$operacao->bindParam(':ARQUIVO', $binario,  PDO::PARAM_LOB);
			$operacao->bindParam(':NOMEA', $arquivo,  PDO::PARAM_STR);
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