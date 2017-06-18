<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
set_time_limit(0);

$unidade = htmlspecialchars($_GET['unidade']);
$material = utf8_decode(htmlspecialchars($_GET['material']));

#DOWNLOAD DO MATERIAL



try{
	// instancia objeto PDO, conectando no mysql
	$conexao = conn_mysql();
	$sqlQtd="	SELECT 
										
										
										NOMEARQUIVO,
										TIPOARQUIVO,
										ARQUIVO,
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
	
	
// Atribuindo o resultado a variaveis
/*$operacao->bindColumn(1, $nome, PDO::PARAM_STR);
$operacao->bindColumn(2, $mimetype, PDO::PARAM_STR);
$operacao->bindColumn(3, $handle, PDO::PARAM_LOB);
$operacao->bindColumn(4, $tamanho, PDO::PARAM_INT);
$operacao->fetch(PDO::FETCH_BOUND);*/


	
	//captura TODOS os resultados obtidos
	$resultados = $operacao->fetchAll();
	
	// fecha a conexão (os resultados já estão capturados)
	$conexao = null;
	
		foreach($resultados as $valor){
		
			//$unidadeM = utf8_encode($valor['UNIDADE']);							
			//$materialM = utf8_encode($valor['MATERIAL']);
			//$tituloM = utf8_encode($valor['TITULO']);
			//$descricaoM = utf8_encode($valor['DESCRICAO']);
			$nomeArquivoM = utf8_encode($valor['NOMEARQUIVO']);
			$tamanhoArquivoM = utf8_encode($valor['TAMANHOARQUIVO']);
			$tipoArquivoM = $valor['TIPOARQUIVO'];
			$arquivoM = $valor['ARQUIVO'];
			//$tipoM = utf8_encode($valor['TIPO']);
			//$statusM = utf8_encode($valor['STATUS']);
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	} 
 /*  header("Content-length: $tamanhoArquivoM");
         header("Content-type: $tipoArquivoM");
         header("Content-Disposition: attachment; filename=$nomeArquivoM");
         ob_clean();
         flush();
         echo $arquivoM;
         exit;*/
		 
		 
		 
$nomeArquivo = $nomeArquivoM;
/*$file = fopen($nomeArquivoM,"a+");
fwrite($file,hex2bin($arquivoM));
fclose($file);*/

//Forçando o download...
header("Content-type: application/pdf");
header("Content-Disposition: attachment; filename=" . $nomeArquivo);
header("Content-Length: " . $tamanhoArquivoM);
header("Content-Transfer-Encoding: binary");
echo $arquivoM;
//readfile($nomeArquivo);

//Apagando o arquivo
//unlink($nomeArquivo); 



/*function hex2bin($str) 
{
   $bin = "";
   $i = 0;
   do {
   	$bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
   	$i += 2;
   } while ($i < strlen($str));
   return $bin;
}*/


/* header("Content-Type: text/html; charset=utf-8"); 
 header("Content-Type: filesize($tamanhoArquivoM)");
 header("Content-Type: $tipoArquivoM");
 header("Content-Disposition: attachment; filename=$nomeArquivoM");*/
 
/*header("Content-type: application/vnd.ms-excel; name='excel'");

header("Content-Disposition: filename=".$nome.".xls");

header("Pragma: no-cache");*/

//echo $arquivoM;


// Configuramos os headers que serão enviados para o browser
/*header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$nomeArquivoM.'"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($arquivoM));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');*/
//ob_end_clean(); //essas duas linhas antes do readfile
//
// Envia o arquivo para o cliente
//readfile($arquivoM);

//header('Content-Disposition: attachment; filename='.$nomeArquivoM);
/*header('Content-type: '.$tipoArquivoM);
echo $arquivoM;*/


/*header("Content-type: ".$tipoArquivoM);

header("Content-Disposition: filename=".$nomeArquivoM);

header("Pragma: no-cache");

echo $arquivoM;*/

/*header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$nomeArquivoM.'"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($arquivoM));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');
ob_end_clean();
readfile($arquivoM);?>*/


/*header('Content-type: ' . $tipoArquivoM);

//Seta o tamanho do arquivo
header('Content-length: ' . filesize($arquivoM));

//Força o download
header('Content-Disposition: attachment; filename=' .$nomeArquivoM);

//Este header é necessário
header('Content-Transfer-Encoding: binary');

echo file_get_contents($arquivoM, FILE_BINARY);*/
/*header("Content-length: ".$tamanhoArquivoM);
header("Content-type: ".$tipoArquivoM);
header("Content-Disposition: attachment; filename=".$nomeArquivoM);
echo $arquivoM;*/
/*$fp = fopen($handle, 'rb');
header('Content-Type: '.$mimetype);
header('Content-Disposition: inline; filename="'.$nome.'"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
fpassthru($fp);
exit(0);*/
?>