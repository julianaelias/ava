<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_admin.php");

$curso = '';

if(!empty($_GET['curso'])){
	$curso = $_GET['curso'];
}


$titulo = '';
$descricao = '';
$categoria = '';
$professor = '';
$palavras = '';
$status = '';




if(!empty($curso)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						C.CURSO, 
						C.TITULO, 
						C.DESCRICAO, 
						C.CATEGORIA, 
						C.PROFESSOR,
						C.PALAVRAS,
						C.STATUS
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
				$descricao = utf8_encode($valor['DESCRICAO']);
				$categoria = utf8_encode($valor['CATEGORIA']);
				$professor = utf8_encode($valor['PROFESSOR']);
				$palavras = utf8_encode($valor['PALAVRAS']);
				$status = utf8_encode($valor['STATUS']);
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
            <h1 class="tituloBreadcrumb">Cadastrar Cursos</h1>
             <li><a  href="/ava/admin/area_admin.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li><li><a  href="/ava/admin/cursos/cursos.php">Cursos</a></li><li class="active">Cadastrar Cursos</li>
        </ol>
    </div>
    <div class="container" >
        <div class="panel panel-primary">
  			<div class="panel-heading">Cadastrar / Alterar Cursos</div>
  			<div class="panel-body">
            	<div class="row espaco">
                	<div class="col-xs-12 col-sm-12 col-md-12">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Título" name="titulo"
                             id="titulo" value="<?=$titulo;?>" required="required" maxlength="255">
                             <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div> 
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="input-group">
                            <textarea rows="4" cols="50" class="form-control" placeholder="Descrição" name="descricao" id="descricao" style="resize: none;" required="required"><?=$descricao;?></textarea>
                            <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                    	<div class="input-group">
                            <select id="categoria" name="categoria" class="form-control" required="required">
                                <option value="" <?=($categoria == '')?'selected':''?>>Categória</option> 
                                <option value="1" <?=($categoria == '1')?'selected':''?>>Acadêmico</option>
                                <option value="2" <?=($categoria == '2')?'selected':''?>>Empresarial</option>
                                <option value="3" <?=($categoria == '3')?'selected':''?>>Informática</option>
                                <option value="4" <?=($categoria == '4')?'selected':''?>>Religiosidade</option>
                            </select>
                        <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Professor" name="professor" 
                            id="professor" value="<?=$professor;?>" required="required">
                         <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
				</div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    	 <div class="input-group">
                        	<textarea rows="4" cols="50" class="form-control" maxlength="255" placeholder="Palavras-chaves"name="palavras" id="palavras" style="resize: none;" required="required"><?=$palavras;?></textarea>
                             <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>
                <div class="row espaco" >  
                	<div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left !important;">
                       <p style="color:#F00;">* Campos de preenchimento obrigatório</p>
	   				</div>       	
                    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:right !important;">
                        <input type="text" name="curso" id="curso" value="<?=$curso;?>" hidden="hidden"/>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="salvarCursos();">SALVAR</button>
	   				</div>
                </div>
  			</div>
		</div>
	</div>
<script>

function salvarCursos(){
	
	var titulo = $("#titulo").val();
	var descricao = $("#descricao").val();
	var professor = $("#professor").val();
	var palavras = $("#palavras").val();
	var categoria = $("#categoria").val();
	var curso = $("#curso").val();
	
	if(titulo == '' || professor == '' || categoria == '' || descricao == '' || palavras == ''){		
		alert ("Preencha os campos obrigratórios e tente novamente.");		
		return false;
	}

	if (confirm('Tem certeza que deseja salvar os dados do curso?')){
	
		$.ajax({
			url: 'salvar_cursos.php',
			type: 'post',
			datatype: 'text',
			data: {titulo : titulo, descricao : descricao, professor : professor, palavras : palavras, categoria : categoria, curso : curso},

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
				
				//location.reload();
			
			}					
		});	
	}
}
</script>	

<?php
include_once("../../includes/rodape.php");
?>