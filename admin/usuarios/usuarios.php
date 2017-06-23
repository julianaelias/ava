<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_admin.php");
?>
<div id="tudo">
	<div class="fundoTopo2">
    	<ol class="breadcrumb">
        	<h1 class="tituloBreadcrumb">Gerir Usuários</h1>
             <li><a  href="/ava/admin/area_admin.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
             <li class="active">Usuários</li>
		</ol>
	</div>
    <div class="container" >
    	<form role="form" method="post" action="usuarios.php">
        <div class="panel panel-primary">
  			<div class="panel-heading">Pesquisar / Adicionar Usuários</div>
  			<div class="panel-body">
            	<div class="row espaco">         	
                    <div class="col-xs-6 col-sm-4 col-md-2">
                    	<input type="text" class="form-control" placeholder="Código" name="codigo" id="codigo" autofocus >
                    </div>
                    <div class="col-xs-6 col-sm-8 col-md-4">
                        <select id="curso" name="curso" class="form-control" > 
							<option value="" selected>Selecione o Curso</option>
								<?php
                                try{
                                    // instancia objeto PDO, conectando no mysql
                                    $conexao = conn_mysql();
                                    
                                    // instrução SQL básica
                                    $SQLSelect = "SELECT DISTINCT
                                                    C.CURSO,
                                                    C.TITULO
                                                    FROM CURSOS C
                                                    JOIN INSCRICOES I ON I.CURSO = C.CURSO
													JOIN USUARIOS U ON U.USUARIO = I.USUARIO
                                                    WHERE C.STATUS = 1
													AND U.STATUS = 1
                                                    ORDER BY C.CURSO";
                                                    
                                    //prepara a execução da sentença
                                    $operacao = $conexao->prepare($SQLSelect);
									
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
									echo"<option value='$curso' $selecione>$titulo</option>";
                                        }	
                                    }else{
										echo"<option value=''>Nenhum curso com inscrição</option>";
									}
                                } //try
                                catch (PDOException $e)
                                {
                                    // caso ocorra uma exceção, exibe na tela
                                    echo "Erro!: " . $e->getMessage() . "<br>";
                                    die();
                                }	
    
                            ?>
                            </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <input type="text" class="form-control" placeholder="Nome" name="nome" id="nome">
                    </div>
                </div>
                <div class="row espaco" style="text-align:right !important;">         	
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    	<button type="submit" class="btn btn-sm btn-primary">PESQUISAR</button>&nbsp;&nbsp;&nbsp;
                    	<a class="btn btn-sm btn-success" href="/ava/admin/usuarios/cadastrar_usuarios.php" role="button">ADICIONAR</a>
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
                    $codigo = '';
                    $categoria = '';
                    $titulo = '';
                    
                    // instrução SQL básica
                    $SQLSelect = "
					
					SELECT DISTINCT U.USUARIO,U. NOME, U.EMAIL, U.DEPARTAMENTO, U.TIPO
					FROM USUARIOS U
					LEFT JOIN INSCRICOES I ON I.USUARIO = U.USUARIO AND I.SITUACAO = 1
					WHERE U.STATUS = 1"; 
                                    
                   if(!empty($_POST['codigo'])){
                        $codigo = $_POST['codigo'];	
                        $SQLSelect .=' AND U.USUARIO = :USUARIO ';
                    }
                    
                    if(!empty($_POST['curso'])){
                        $curso = $_POST['curso'];
                        $SQLSelect .=' AND I.CURSO = :CURSO ';	
                    }
                    
                    if(!empty($_POST['nome'])){
                        $nome = $_POST['nome'];
						$nome = strtoupper($nome);
						$nome = "%".$nome."%";
						 
                        $SQLSelect .=' AND U.NOME LIKE :NOME ';
                    }
                    
					
                    $SQLSelect .=" ORDER BY U.NOME";
                    
					
                    //prepara a execução da sentença
                    $operacao = $conexao->prepare($SQLSelect);
                    
                    
                    if(!empty($codigo)){
                        $operacao->bindParam(':USUARIO', $codigo, PDO::PARAM_INT);
                    }
                    
                    if(!empty($curso)){
                        $operacao->bindParam(':CURSO', $curso, PDO::PARAM_INT);
                    }
                    
                    if(!empty($nome)){
                        $operacao->bindParam(':NOME', $nome, PDO::PARAM_INT);
                    }
                    
                    $pesquisar = $operacao->execute();

                    //captura TODOS os resultados obtidos
                    $resultados = $operacao->fetchAll();
                    
                    // fecha a conexão (os resultados já estão capturados)
                    $conexao = null;

                    // se há resultados, os escreve em uma tabela
                    if (count($resultados)> 0){	?>
                    
                    
                        <table class="table text-center">        
                            <thead>
                                <tr>
                                    <th class="text-center">Usuário</th>
                                    <th class="text-center">E-mail</th>
                                    <th class="text-center">Departamento</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Inscrições</th>
                                    <th class="text-center">Alterar</th>
                                </tr>
                            </thead>
					<?php
							$count = 0;
                        foreach($resultados as $valor){
							
							$count++;
							
							if($count % 2 == 0){
								$cor = "#FFFFFF";
							}else{
								$cor = "#F9F9F9";
							}
					?>
                            <tr style="background-color:<?=$cor;?>;">
                                <td class="text-left">
									<?php echo utf8_encode($valor['USUARIO']).' - '.utf8_encode($valor['NOME']) ; ?>
                                </td>
                                <td class="text-left"><?php echo utf8_encode($valor['EMAIL']); ?></td>
                                <td class="text-left"><?php echo utf8_encode($valor['DEPARTAMENTO']); ?></td>
                                <td>
									<?php
										if($valor['TIPO'] ==  1){
											$descTipo = "ADMINISTRADOR";
										}else if($valor['TIPO'] == 2){
											$descTipo = "ALUNO";
										}
										echo $descTipo; 
									?>
                                </td>
                                <td>
                                	<button type="button" class="btn btn-sm btn-primary" title="Gerir Inscrições" 
                                    onclick="gerirInscricoes(<?=$valor['USUARIO'];?>,'1');">
                                    <i class="glyphicon glyphicon-cog" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <td>
                                	<a class="btn btn-sm btn-primary" title="Alterar Usuario" 
                                    href="/ava/admin/usuarios/cadastrar_usuarios.php?usuario=<?=$valor['USUARIO'];?>" role="button">
                                    	<i class="fa fa-pencil" aria-hidden="true"></i>
                                	</a>
                                </td>
                                
                            </tr>
                            <tr style="display:none;" id="tr_<?=$valor['USUARIO'];?>">
                                <td class="text-left" colspan="7" style="display:none; width:90%;" 
                                id="td_<?=$valor['USUARIO']?>"><div id="usuario_<?=$valor['USUARIO'];?>"></div></td>
                            </tr>
                        <?php 
						} 
					
					}else{?>
                        <table class="table table-striped"> 
                            <tr>
                                <td>Nenhum usuário encontrado</td>
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
				
		?>
        
			</div>        
		</div>
		<div id="clear"></div>
 <script>
 
 
 function gerirInscricoes(usuario, controle){	
	
	
	   if(controle == 1){
		   if($("#tr_"+usuario).css('display') == 'none'){
			   $("#tr_"+usuario).show();
			   $("#td_"+usuario).show();
		   }else{
			   $("#tr_"+usuario).hide();
			   $("#td_"+usuario).hide();
		   }
	   }else{
		   
		   $("#tr_"+usuario).show();
		   $("#td_"+usuario).show();
		   
	   }
		
		$("#usuario_"+usuario).html('');
		$.ajax({
			url: 'gerir_inscricoes.php',
			type: 'post',
			datatype: 'text',
			data: {usuario : usuario, controle : controle},
		
			success: function(r){
										
				$("#usuario_"+usuario).html(r);
			}			
		});	
	}
	
	function desativarInscricao(inscricao, usuario){
	
			if (confirm('Tem certeza que deseja cancelar inscrição?')){
			
				$.ajax({
					url: 'desativar_inscricao.php',
					type: 'post',
					datatype: 'text',
					data: {inscricao : inscricao, usuario : usuario},
		
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
						
						gerirInscricoes(usuario, '2');
					
					}					
				});	
			}
		}
	
</script>	

       
        
<?php
include_once("../../includes/rodape.php");
?>