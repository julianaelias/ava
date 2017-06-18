<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_admin.php");
?>
<div id="tudo">
	<div class="container">
    	<ol class="breadcrumb fundo">
        	<h1 class="tituloBreadcrumb">Gerir Cursos</h1>
             <li><a  href="../admin/area_admin.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
             <li class="active">Cursos</li>
		</ol>
	</div>
    <div class="container" >
    	<form role="form" method="post" action="cursos.php">
        <div class="panel panel-primary">
  			<div class="panel-heading">Pesquisar / Adicionar Cursos</div>
  			<div class="panel-body">
            	<div class="row espaco">         	
                    <div class="col-xs-6 col-sm-4 col-md-2">
                    	<input type="text" class="form-control" placeholder="Código" name="codigo" id="codigo" autofocus >
                    </div>
                    <div class="col-xs-6 col-sm-8 col-md-4">
                        <select id="categoria" name="categoria" class="form-control"> 
                            <option value="" selected>Categória</option> 
                            <option value="1">Acadêmico</option>
                            <option value="2">Empresarial</option>
                            <option value="3">Iinformática</option>
                            <option value="4">Religiosidade</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <input type="text" class="form-control" placeholder="Título" name="titulo" id="titulo">
                    </div>
                </div>
                <div class="row espaco" style="text-align:right !important;">         	
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    	<button type="submit" class="btn btn-sm btn-primary">PESQUISAR</button>&nbsp;&nbsp;&nbsp;
                    	<a class="btn btn-sm btn-success" href="../admin/cursos/cadastrar_cursos.php" role="button">ADICIONAR</a>
	   				</div>
                </div>
  			</div>
		</div>
        </form>
	</div>
	<div class="container" >
			<div class="panel panel-primary">
			<?php 
			
			    try{
                    // instancia objeto PDO, conectando no mysql
                    $conexao = conn_mysql();
					$sqlQtd="SELECT COUNT(C.CURSO) AS QTD
                                    FROM CURSOS C
                                    WHERE C.STATUS = 1 ";
									
					 if(!empty($_POST['codigo'])){
                        $codigo = $_POST['codigo'];	
                        $sqlQtd .=' AND C.CURSO = :CURSO ';
                    }
                    
                    if(!empty($_POST['categoria'])){
                        $categoria = $_POST['categoria'];
                        $sqlQtd .=' AND C.CATEGORIA = :CATEGORIA ';	
                    }
                    
                    if(!empty($_POST['titulo'])){
                        $titulo = $_POST['titulo'];
						$titulo = strtoupper($titulo);
						$titulo = "%".$titulo."%";
						 
                        $sqlQtd .=' AND C.TITULO LIKE :TITULO ';
                    }			
					//prepara a execução da sentença
                    $operacao = $conexao->prepare($sqlQtd);
					
					 if(!empty($codigo)){
                        $operacao->bindParam(':CURSO', $codigo, PDO::PARAM_INT);
                    }
                    
                    if(!empty($categoria)){
                        $operacao->bindParam(':CATEGORIA', $categoria, PDO::PARAM_INT);
                    }
                    
                    if(!empty($titulo)){
                        $operacao->bindParam(':TITULO', $titulo, PDO::PARAM_INT);
                    }
					
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
					
					SELECT RowNumber, CURSO, TITULO, DESCRICAO, CATEGORIA, PROFESSOR, PALAVRAS, STATUS FROM ( SELECT @rownum := @rownum + 1 RowNumber, t.* FROM cursos t, (SELECT @rownum := 0) s WHERE t.STATUS = 1 "; 
                                    
                   if(!empty($_POST['codigo'])){
                        $codigo = $_POST['codigo'];	
                        $SQLSelect .=' AND t.CURSO = :CURSO ';
                    }
                    
                    if(!empty($_POST['categoria'])){
                        $categoria = $_POST['categoria'];
                        $SQLSelect .=' AND t.CATEGORIA = :CATEGORIA ';	
                    }
                    
                    if(!empty($_POST['titulo'])){
                        $titulo = $_POST['titulo'];
						$titulo = strtoupper($titulo);
						$titulo = "%".$titulo."%";
						 
                        $SQLSelect .=' AND t.TITULO LIKE :TITULO ';
                    }
                    
					
                    $SQLSelect .=" ORDER BY t.curso ) subQ WHERE subQ.RowNumber BETWEEN $Inicio AND $Fim";
                    
					
                    //prepara a execução da sentença
                    $operacao = $conexao->prepare($SQLSelect);
                    
                    
                    if(!empty($codigo)){
                        $operacao->bindParam(':CURSO', $codigo, PDO::PARAM_INT);
                    }
                    
                    if(!empty($categoria)){
                        $operacao->bindParam(':CATEGORIA', $categoria, PDO::PARAM_INT);
                    }
                    
                    if(!empty($titulo)){
                        $operacao->bindParam(':TITULO', $titulo, PDO::PARAM_INT);
                    }
                    
                    $pesquisar = $operacao->execute();

                    //captura TODOS os resultados obtidos
                    $resultados = $operacao->fetchAll();
                    
                    // fecha a conexão (os resultados já estão capturados)
                    $conexao = null;

                    // se há resultados, os escreve em uma tabela
                    if (count($resultados)> 0){	?>
                    
                    
                        <table class="table table-striped text-center">        
                            <thead>
                                <tr>
                                    <th class="text-center">Curso</th>
                                    <th class="text-center">Título</th>
                                    <th class="text-center">Categória</th>
                                    <th class="text-center">Gerir Conteúdo</th>
                                    <th class="text-center">Alterar</th>
                                    <th class="text-center">Desativar</th>
                                    <th class="text-center">Avaliações</th>
                                </tr>
                            </thead>
					<?php
                        foreach($resultados as $valor){?>
                            <tr>
                                <td><?php echo utf8_encode($valor['CURSO']); ?></td>
                                <td class="text-left"><?php echo utf8_encode($valor['TITULO']); ?></td>
                                <td>
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
										echo $descCategoria; 
									?>
                                </td>
                                <td>
                                	<a class="btn btn-sm btn-primary" 
                                    href="../admin/cursos/gerir_cursos.php?curso=<?=$valor['CURSO'];?>" role="button"
                                   title="Gerir Conteúdo do Curso">
                                    	<span class="glyphicon glyphicon-cog" aria-hidden="true"></span
                                	></a>
                                </td>
                                <td>
                                	<a class="btn btn-sm btn-primary" title="Alterar Curso" 
                                    href="../admin/cursos/cadastrar_cursos.php?curso=<?=$valor['CURSO'];?>" role="button">
                                    	<i class="fa fa-pencil" aria-hidden="true"></i>
                                	</a>
                                </td>
                                <td>
                    			<button type="button" class="btn btn-sm btn-primary" title="Desativar Curso" 
                                onclick="desativarCurso(<?=$valor['CURSO'];?>);">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                                </td>
                                <td>
                    			<button type="button" class="btn btn-sm btn-primary" title="Exibir Avaliações" 
                                onclick="exibirAvaliacoes(<?=$valor['CURSO'];?>);">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                </button>
                                </td>
                            </tr>
                            <tr style="display:none;" id="tr_<?=$valor['CURSO'];?>">
                                <td class="text-left" colspan="7" style="display:none; width:100%;" 
                                id="td_<?=$valor['CURSO'];?>"><div id="avaliacao_<?=$valor['CURSO'];?>"></div></td>
                            </tr>
                        <?php 
						} 
					
					}else{?>
                        <table class="table table-striped"> 
                            <tr>
                                <td>Nenhum curso encontrado</td>
                            </tr>
                <?php }?>
                
						</table>
				<?php	
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
                            <li><a href="../admin/cursos/cursos.php?pagina=0">
                            <div class="fa fa-step-backward"></div></a></li>
                            <li><a href="../admin/cursos/cursos.php?pagina=<?= $PAGINA - 1; ?>">
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
                                <a href="../admin/cursos/cursos.php?pagina=<?= $pagina; ?>"><?= $ix; ?></a></li><?php
                            }
                        }else {
                            echo '<li>';
                            echo (int) ($QT / $QTDMOSTRA);
                            echo '</li>';
                        }
                            $ultima = $ULTIMA_MSG;
                            if ($QT > $Fim) {
                            ?>
                                <li><a href="../admin/cursos/cursos.php?pagina=<?= $PAGINA + 1; ?>">
                                <div class="fa fa-forward"></div></a></li>
                                <li><a href="../admin/cursos/cursos.php?pagina=<?= $ULTIMA_MSG; ?>">
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
		</div>
		<div id="clear"></div>
 <script>

function desativarCurso(curso){
	
	if (confirm('Tem certeza que deseja desativar o curso?')){
	
		$.ajax({
			url: 'desativar_cursos.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso},

			success: function(r)
			{
				rSplit = r.split('|');
				if (rSplit[0] == 1)
				{
					alert(rSplit[1]);
				}
				else if (rSplit[0] == 2)
				{
					alert(rSplit[1]);
				}
				else
				{
					alert(r);
				}
				
				location.reload();
			
			}					
		});	
	}
}

 function exibirAvaliacoes(curso){	
	
	  
		   if($("#tr_"+curso).css('display') == 'none'){
			   $("#tr_"+curso).show();
			   $("#td_"+curso).show();
		   }else{
			   $("#tr_"+curso).hide();
			   $("#td_"+curso).hide();
		   }
	   
		
		$("#avaliacao_"+curso).html('');
		$.ajax({
			url: 'exibir_avaliacoes.php',
			type: 'post',
			datatype: 'text',
			data: {curso : curso},
		
			success: function(r){
										
				$("#avaliacao_"+curso).html(r);
			}			
		});	
	}
</script>	
       
        
<?php
include_once("../../includes/rodape.php");
?>