<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_aluno.php");


$curso = '';

if(!empty($_GET['curso'])){
	$curso = $_GET['curso'];
}

$inscricao = '';

if(!empty($_GET['inscricao'])){
	$inscricao = $_GET['inscricao'];
}



if(!empty($curso)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						C.CURSO, 
						C.TITULO
						FROM CURSOS C
						WHERE C.STATUS = 1
						AND C.CURSO = :CURSO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		
		if(!empty($curso)){
			$operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
		}
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$curso = utf8_encode($valor['CURSO']);
				$titulo = utf8_encode($valor['TITULO']);
			}	
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}	
}
?>
<div id="tudo">
	<div class="fundoTopo2">
    	<ol class="breadcrumb">
        	<h1 class="tituloBreadcrumb">Acessar Conteúdo</h1>
             <li><a  href="/ava/usuario/area_aluno.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
            <li><a href="/ava/usuario/cursos/cursos.php">Cursos</a></li>
            <li class="active">Acessar Conteúdo</li>
		</ol>
    </div>
    <div class="container">
        <div class="row">
  			<div class="col-md-12">
            	<h2 class="subTitulo">Curso: <span class="subTituloN2"><?=$curso.' - '.$titulo;?></span></h2>
            </div>
		</div>
	</div>
    
	<div class="container" >
			<?php 
			
			    try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
					$sqlQtd="	SELECT  COUNT(U.UNIDADE) AS QTD 
								FROM UNIDADES U 
								JOIN CURSOS C ON C.CURSO = U.CURSO
								JOIN INSCRICOES I ON I.CURSO = C.CURSO
								WHERE U.STATUS = 1 
								AND C.STATUS = 1
                     		    AND I.INSCRICAO = :INSCRICAO 
								AND I.USUARIO = :USUARIO";							
						
					//prepara a execução da sentença
                    $operacao = $conexao->prepare($sqlQtd);					
									
					$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
					$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
					
					$pesquisar = $operacao->execute();

                    //captura TODOS os resultados obtidos
                    $resultados = $operacao->fetchAll();
                    
                    // fecha a conexão (os resultados já estão capturados)
                    $conexao = null;
					
					foreach($resultados as $valor){
					
						$QT = $valor['QTD'];
					}
					
					$QTDMOSTRA = 6; // QUANTIDADE FIXA DE LINHAS POR PAGINA DA GRID

					if ($QT % $QTDMOSTRA != 0) {
						$ULTIMA_MSG = ((int) ($QT / $QTDMOSTRA) + 1) - 1;
					} else {
						$ULTIMA_MSG = (int) ($QT / $QTDMOSTRA) - 1;
					}
					
					if( isset($_GET['pagina'])){
						$PAGINA = $_GET['pagina'];
					}else{
						$PAGINA = 0;
					}
					
					$Inicio = $QTDMOSTRA * $PAGINA;
					$Fim = $QTDMOSTRA + $Inicio;
					if($Inicio != 0){
						$Inicio = $Inicio+1;
					}
					
				} //try
                catch (PDOException $e)
                {
                    // caso ocorra uma exceção, exibe na tela
                    echo "Erro!: " . $e->getMessage() . "<br>";
                    die();
                } 
		
                try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
                    $codigo = '';
                    $categoria = '';
                    $titulo = '';
                    
                    // instrução SQL básica
                    $SQLSelect = "
					
					SELECT RowNumber, UNIDADE, TITULO, DESCRICAO, ORDEM, CURSO, INSCRICAO, USUARIO
					FROM 
					( SELECT @rownum := @rownum + 1 RowNumber, I.INSCRICAO, I.USUARIO, t.* 
					FROM UNIDADES t
					JOIN CURSOS C ON C.CURSO = t.CURSO
                     JOIN INSCRICOES I ON I.CURSO = C.CURSO, (SELECT @rownum := 0) s 
					WHERE t.STATUS = 1 
                     AND C.STATUS = 1
                     AND I.INSCRICAO = :INSCRICAO AND I.USUARIO = :USUARIO "; 
                  			
                    $SQLSelect .=" ORDER BY t.ORDEM ) subQ WHERE subQ.RowNumber BETWEEN $Inicio AND $Fim";
                    
					
                    //prepara a execução da sentença
                    $operacao = $conexao->prepare($SQLSelect);
                   
					$operacao->bindParam(':INSCRICAO', $inscricao, PDO::PARAM_INT);
                    $operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
                
                    
                    $pesquisar = $operacao->execute();

                    //captura TODOS os resultados obtidos
                    $resultados = $operacao->fetchAll();
                    
                    // fecha a conexão (os resultados já estão capturados)
                    $conexao = null;

                    // se há resultados, os escreve em uma tabela
                    if (count($resultados)> 0){	?>
                  
                    
					<?php
                        foreach($resultados as $valor){?>
							<div class="panel panel-primary">
  								<div class="panel-heading" style="text-align:center;">                                	
									<?php echo utf8_encode($valor['TITULO']); ?>
                                </div>
  								<div class="panel-body">
                                  <div class="row espaco">
                                     <div class="col-xs-12 col-sm-12 col-md-12">
                                        <span style="color:#286090; font-weight:bold;">Descrição: </span>
                                        <?php echo utf8_encode($valor['DESCRICAO']); ?>
                                    </div>
                                </div>
                                <div class="row espaco">
                                     <div class="col-xs-12 col-sm-6 col-md-6" style="text-align:right;" >
                                        <button type="button" class="btn btn-sm btn-primary" 
                                        title="Clique para exibir os materiais dessa unidade." 
                                        onclick="exibirMateriais(<?=$valor['CURSO'];?>,<?=$valor['UNIDADE'];?>,<?=$valor['INSCRICAO'];?>), 1";>EXIBIR MATERIAIS
                                    	</button>&nbsp;&nbsp;
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6" style="text-align:left;" >
                                        &nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary" 
                                        title="Clique para exibir as atividades dessa unidade." 
                                        onclick="exibirAtividades(<?=$valor['CURSO'];?>,<?=$valor['UNIDADE'];?>,<?=$valor['INSCRICAO'];?>), 1";>EXIBIR ATIVIDADES
                                        </button>
                                    </div>
                                 </div>
                                 <div class="row espaco" style="display:none;"
                                  id="materiaisL_<?=$valor['CURSO'];?>_<?=$valor['UNIDADE'];?>_<?=$valor['INSCRICAO'];?>">
                                     <div class="col-xs-12 col-sm-12 col-md-12">
                                      	<div style="display:none;" id="materiais_<?=$valor['CURSO'];?>_<?=$valor['UNIDADE'];?>_<?=$valor['INSCRICAO'];?>"></div>        
                                    </div>
                                </div>
                                 <div class="row espaco" style="display:none;"
                                  id="atividadesL_<?=$valor['CURSO'];?>_<?=$valor['UNIDADE'];?>_<?=$valor['INSCRICAO'];?>">
                                     <div class="col-xs-12 col-sm-12 col-md-12">
                                      	<div style="display:none;" id="atividades_<?=$valor['CURSO'];?>_<?=$valor['UNIDADE'];?>_<?=$valor['INSCRICAO'];?>"></div>        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
						}
					
					}else{?>
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row espaco" style="text-align:center;">
                                	 <div class="col-xs-12 col-sm-12 col-md-12">Você não está inscrito em nenhum curso.</div>
                                </div>
                            </div>
                        </div>
                <?php }
                } //try
                catch (PDOException $e)
                {
                    // caso ocorra uma exceção, exibe na tela
                    echo "Erro!: " . $e->getMessage() . "<br>";
                    die();
                } 
				
				if($QT > $QTDMOSTRA){ ?>
				
				
				<div id="DivPaginacao">
        <table width="100%" align="center" >            
            <tr>           
                <td valign="middle" align="center" style="text-align:center !important; cursor:pointer;">
                    <ul class="pagination pagination-sm" >
                    
                     <?php
                        if ($PAGINA > 0) {?>
                            <li><a href="/ava/usuario/cursos/cursos.php?pagina=0">
                            <div class="fa fa-step-backward"></div></a></li>
                            <li><a href="/ava/usuario/cursos/cursos.php?pagina=<?= $PAGINA - 1; ?>">
                            <div class="fa fa-backward"></div></a></li>
                      <?php } 
                        //verifica qtde de paginas
                        if ((($QT % $QTDMOSTRA) != 0) || ($QT > 1)) {
                            //qtde de paginas anterior e posterior
                            $QTANTE = $PAGINA;
                            $QTPROX = $PAGINA + 2;

                            //valida
                            if ($QTANTE <= 0){
                                $QTANTE = 1;
							}

                            if ($QTPROX > $ULTIMA_MSG + 1){
                                $QTPROX = $ULTIMA_MSG + 1;
							}

                            //monta a paginação numerica
                            for ($ix = $QTANTE; $ix <= $QTPROX; $ix++) {
                                $pagina = $ix - 1;
                                ?><li class="<?= ($ix == $PAGINA + 1) ? 'active' : ''; ?>">
                                <a href="/ava/usuario/cursos/cursos.php?pagina=<?= $pagina; ?>"><?= $ix; ?></a></li><?php
                            }
                        }else {
                            echo '<li>';
                            echo (int) ($QT / $QTDMOSTRA);
                            echo '</li>';
                        }
                            $ultima = $ULTIMA_MSG;
                            if ($QT > $Fim) {
                            ?>
                                <li><a href="/ava/usuario/cursos/cursos.php?pagina=<?= $PAGINA + 1; ?>">
                                <div class="fa fa-forward"></div></a></li>
                                <li><a href="/ava/usuario/cursos/cursos.php?pagina=<?= $ULTIMA_MSG; ?>">
                                <div class="fa fa-step-forward"></div></a></li>
                            <?php
                            }
                            ?>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
				
		<?php } ?>
        
			</div>        
	
		<div id="clear"></div>
        
<script>
	function exibirMateriais(curso, unidade, inscricao, controle){	
	
		   if($("#materiais_"+curso+"_"+unidade+"_"+inscricao).css('display') == 'none'){
			   $("#materiaisL_"+curso+"_"+unidade+"_"+inscricao).show();
			   $("#materiais_"+curso+"_"+unidade+"_"+inscricao).show();
		   }else{
			   $("#materiaisL_"+curso+"_"+unidade+"_"+inscricao).hide();
			   $("#materiais_"+curso+"_"+unidade+"_"+inscricao).hide();
		   }
		
			$("#materiais_"+curso+"_"+unidade+"_"+inscricao).html('');
			$.ajax({
				url: 'materiais.php',
				type: 'post',
				datatype: 'text',
				data: {curso : curso, unidade : unidade, inscricao : inscricao, controle : controle},
			
				success: function(r){
											
					$("#materiais_"+curso+"_"+unidade+"_"+inscricao).html(r);
			}			
		});	
	}
	
	function exibirAtividades(curso, unidade, inscricao, controle){	
	
		   if($("#atividades_"+curso+"_"+unidade+"_"+inscricao).css('display') == 'none'){
			   $("#atividadesL_"+curso+"_"+unidade+"_"+inscricao).show();
			   $("#atividades_"+curso+"_"+unidade+"_"+inscricao).show();
		   }else{
			   $("#atividadesL_"+curso+"_"+unidade+"_"+inscricao).hide();
			   $("#atividades_"+curso+"_"+unidade+"_"+inscricao).hide();
		   }
		
			$("#atividades_"+curso+"_"+unidade+"_"+inscricao).html('');
			$.ajax({
				url: 'atividades.php',
				type: 'post',
				datatype: 'text',
				data: {curso : curso, unidade : unidade, inscricao : inscricao, controle : controle},
			
				success: function(r){
											
					$("#atividades_"+curso+"_"+unidade+"_"+inscricao).html(r);
			}			
		});	
	}
	
	
	
	function registar(material, unidade, curso, inscricao){	
	
			$.ajax({
				url: 'registrar.php',
				type: 'post',
				datatype: 'text',
				data: {material : material, unidade : unidade, curso : curso, inscricao : inscricao},
			
				success: function(r){
			}			
		});	
	}
	
	/*function responder(curso, unidade, atividade, inscricao, controle){	
	
	   if(controle == 1){
		   if($("#tr_"+atividade).css('display') == 'none'){
			   $("#tr_"+atividade).show();
			   $("#td_"+atividade).show();
		   }else{
			   $("#tr_"+atividade).hide();
			   $("#td_"+atividade).hide();
		   }
	   }else{
		   
		   $("#tr_"+atividade).show();
		   $("#td_"+atividade).show();
		   
	   }
		
		$("#atividade_"+atividade).html('');
		$.ajax({
			url: 'responder.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso, unidade : unidade, atividade : atividade, inscricao :inscricao, controle : controle},
		
			success: function(r){
										
				$("#atividade_"+atividade).html(r);
			}			
		});	
	}*/
	
	
	
	
	
	
</script>
 
        
<?php
include_once("../../includes/rodape.php");
?>