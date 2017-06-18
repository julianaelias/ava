<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_aluno.php");

?>
<div id="tudo">
	<div class="container">
    	<ol class="breadcrumb fundo">
        	<h1 class="tituloBreadcrumb">Cursos</h1>
             <li><a  href="../usuario/area_aluno.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
             <li class="active">Cursos</li>
		</ol>
	</div>
    
	<div class="container" >
			<?php 
			
			    try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
					$sqlQtd="	SELECT  COUNT(C.CURSO) AS QTD 
								FROM CURSOS C 
								JOIN INSCRICOES I ON I.CURSO = C.CURSO AND I.USUARIO = :USUARIO AND I.SITUACAO = 1 
								WHERE C.STATUS = 1 ";
									
						
					//prepara a execução da sentença
                    $operacao = $conexao->prepare($sqlQtd);					
									
					$operacao->bindParam(':USUARIO', $_SESSION['codigo'], PDO::PARAM_INT);
					
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
					
					SELECT RowNumber, CURSO, TITULO, DESCRICAO, CATEGORIA, PROFESSOR, PALAVRAS, STATUS, INSCRICAO, DATA
					FROM 
					( SELECT @rownum := @rownum + 1 RowNumber,
					 I.INSCRICAO AS INSCRICAO, 
					 date_format(I.DATA,'%d/%m/%Y') AS DATA, t.* 
					FROM cursos t
					JOIN INSCRICOES I ON I.CURSO = t.CURSO AND I.USUARIO = :USUARIO AND I.SITUACAO = 1, (SELECT @rownum := 0) s 
					WHERE t.STATUS = 1  "; 
                  			
                    $SQLSelect .=" ORDER BY t.curso ) subQ WHERE subQ.RowNumber BETWEEN $Inicio AND $Fim";
                    
					
                    //prepara a execução da sentença
                    $operacao = $conexao->prepare($SQLSelect);
                   
					
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
  								<div class="panel-heading">                                	
									<?php echo utf8_encode($valor['CURSO']).' - '. utf8_encode($valor['TITULO']); ?>
                                </div>
  								<div class="panel-body">
                                  <div class="row espaco">
                                         <div class="col-xs-6 col-sm-6 col-md-6">
											<span style="color:#286090; font-weight:bold;">Inscrição: </span>
											<?php echo utf8_encode($valor['INSCRICAO']); ?>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6">
										<span style="color:#286090; font-weight:bold;">Data Início: </span>
											<?php echo utf8_encode($valor['DATA']); ?>
                                         </div>
									</div>
            						<div class="row espaco"> 
                                    	<div class="col-xs-12 col-sm-12 col-md-12">
                                        	<span style="color:#286090; font-weight:bold;">Descrição: </span>
											<?php echo utf8_encode($valor['DESCRICAO']); ?>
                                         </div>
                                     </div>
                                     <div class="row espaco">
                                         <div class="col-xs-6 col-sm-6 col-md-6">
											<span style="color:#286090; font-weight:bold;">Professor(es): </span>
											<?php echo utf8_encode($valor['PROFESSOR']); ?>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6">
										<span style="color:#286090; font-weight:bold;">Categoria: </span>
											<?php
                                            if($valor['CATEGORIA'] ==  1){
                                                $descCategoria = "Acadêmico";
                                            }else if($valor['CATEGORIA'] == 2){
                                                $descCategoria = "Empresarial";
                                            }else if($valor['CATEGORIA'] == 3){
                                                $descCategoria = "Informática";
                                            }else if($valor['CATEGORIA'] == 4){
                                                $descCategoria = "Religiosidade";
                                            }
                                            echo $descCategoria;  ?>
                                         </div>
									</div>
                                    <div class="row espaco">
                                         <div class="col-xs-12 col-sm-12 col-md-12">
                                         <span style="color:#286090; font-weight:bold;">Palavras Chave: </span>
										 	<?php echo utf8_encode($valor['PALAVRAS']); ?>
                                         </div>
                                     </div>
                                     <div class="row espaco" style="text-align:center;">
                                        <div class="col-xs-4 col-sm-4 col-md-4">
											<a class="btn btn-sm btn-primary" 
                                    		href="../usuario/cursos/avaliar.php?inscricao=<?=$valor['INSCRICAO'];?>&curso=<?=$valor['CURSO'];?>" 
                                            role="button" title="Avaliar Curso">
                                            &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span>&nbsp;AVALIAR CURSO&nbsp;&nbsp;&nbsp;</a>
	   									</div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
											<a class="btn btn-sm btn-primary" target="_blank"
                                    		href="../usuario/cursos/certificado.php?inscricao=<?=$valor['INSCRICAO'];?>&curso=<?=$valor['CURSO'];?>" 
                                            role="button" title="Emitir Certificado">
                                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp;EMITIR CERTIFICADO</a>
	   									</div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
											<a class="btn btn-sm btn-success" 
                                    		href="../usuario/cursos/conteudo.php?inscricao=<?=$valor['INSCRICAO'];?>&curso=<?=$valor['CURSO'];?>" 
                                            role="button" title="Acessar Conteúdo">
                                            <i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;ACESSAR CONTEÚDO</a>
	   									</div>
                           			 </div>
                                 </div>
                              </div>
                        <?php 
						}
					
					}else{?>
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row espaco">
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
                            <li><a href="../usuario/cursos/cursos.php?pagina=0">
                            <div class="fa fa-step-backward"></div></a></li>
                            <li><a href="../usuario/cursos/cursos.php?pagina=<?= $PAGINA - 1; ?>">
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
                                <a href="../usuario/cursos/cursos.php?pagina=<?= $pagina; ?>"><?= $ix; ?></a></li><?php
                            }
                        }else {
                            echo '<li>';
                            echo (int) ($QT / $QTDMOSTRA);
                            echo '</li>';
                        }
                            $ultima = $ULTIMA_MSG;
                            if ($QT > $Fim) {
                            ?>
                                <li><a href="../usuario/cursos/cursos.php?pagina=<?= $PAGINA + 1; ?>">
                                <div class="fa fa-forward"></div></a></li>
                                <li><a href="../usuario/cursos/cursos.php?pagina=<?= $ULTIMA_MSG; ?>">
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
 
        
<?php
include_once("../../includes/rodape.php");
?>