<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_admin.php");

$usuario = $_GET['usuario'];
$nome = '';
$email = '';
$departamento = '';
$senha = '';
$tipo = '';
$status = '';

if(!empty($usuario)){


	try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
		
		// instrução SQL básica
		$SQLSelect = "SELECT 
						U.USUARIO,
						U.NOME, 
						U.EMAIL, 
						U.SENHA,
						U.DEPARTAMENTO, 
						U.TIPO,
						U.STATUS
						FROM USUARIOS U
						WHERE U.USUARIO = :USUARIO";
						
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);
		
		
		if(!empty($usuario)){
			$operacao->bindParam(':USUARIO', $usuario, PDO::PARAM_INT);
		}
		
		$pesquisar = $operacao->execute();
	
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
	
		// se há resultados, os escreve em uma tabela
		if (count($resultados)> 0){
			foreach($resultados as $valor){
				$usuario = utf8_encode($valor['USUARIO']);
				$nome = utf8_encode($valor['NOME']);
				$email = utf8_encode($valor['EMAIL']);
				$senha = utf8_encode($valor['SENHA']);
				$departamento = utf8_encode($valor['DEPARTAMENTO']);
				$tipo = utf8_encode($valor['TIPO']);
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
	<div class="container">
        <ol class="breadcrumb fundo" >
            <h1 class="tituloBreadcrumb">Cadastrar Usuários</h1>
             <li><a  href="/ava/admin/area_admin.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
              <li><a  href="/ava/admin/usuarios/usuarios.php">Usuários</a></li>
             <li class="active">Cadastrar Usuários</li>
        </ol>
    </div>
    <div class="container" >
        <div class="panel panel-primary">
  			<div class="panel-heading">Cadastrar / Alterar Usuários</div>
  			<div class="panel-body">
            	<div class="row espaco">
                	<div class="col-xs-12 col-sm-12 col-md-12">
                    	<div class="input-group">
                            <input type="text" class="form-control" placeholder="Nome" name="nome"
                             id="nome" value="<?=$nome;?>" required="required" maxlength="255">
                             <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div> 
                </div>
                <div class="row espaco">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="input-group">
                           <input type="text" class="form-control" placeholder="E-mail" name="email"
                             id="email" value="<?=$email;?>" required="required" maxlength="255">
                            <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="input-group">
                           <input type="text" class="form-control" placeholder="Senha" name="senha"
                             id="senha" value="<?=$senha;?>" required="required" maxlength="255">
                            <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="input-group">
                           <input type="text" class="form-control" placeholder="Departamento" name="departamento"
                             id="departamento" value="<?=$departamento;?>" required="required" maxlength="255">
                            <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                </div>
                <div class="row espaco">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                    	<div class="input-group">
                            <select id="tipo" name="tipo" class="form-control" required="required">
                                <option value="" <?=($tipo == '')?'selected':''?>>Selecione o Tipo</option> 
                                <option value="1" <?=($tipo == '1')?'selected':''?>>ADMINISTRADOR</option>
                                <option value="2" <?=($tipo == '2')?'selected':''?>>ALUNO</option>
                            </select>
                        <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
                   <div class="col-xs-6 col-sm-6 col-md-6">
                    	<div class="input-group">
                            <select id="status" name="status" class="form-control" required="required">
                                <option value="" <?=($status == '')?'selected':''?>>Selecione o Status</option> 
                                <option value="1" <?=($status == '1')?'selected':''?>>ATIVO</option>
                                <option value="2" <?=($status == '2')?'selected':''?>>INATIVO</option>
                            </select>
                        <span class="input-group-addon" style="color:#F00;">*</span>
						</div>
                    </div>
				</div>
                <div class="row espaco" >  
                	<div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left !important;">
                       <p style="color:#F00;">* Campos de preenchimento obrigatório</p>
	   				</div>       	
                    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:right !important;">
                        <input type="text" name="usuario" id="usuario" value="<?=$usuario;?>" hidden="hidden"/>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="salvarUsuario();">SALVAR</button>
	   				</div>
                </div>
  			</div>
		</div>
	</div>
<script>

function salvarUsuario(){
		
	var usuario = $("#usuario").val();
	var nome = $("#nome").val();
	var email = $("#email").val();
	var departamento = $("#departamento").val();
	var senha = $("#senha").val();
	var tipo = $("#tipo").val();
	var status = $("#status").val();
	
	if(nome == '' || email == '' || departamento == '' || senha == '' || tipo == '' || status == ''){		
		alert ("Preencha os campos obrigratórios e tente novamente.");		
		return false;
	}

	if (confirm('Tem certeza que deseja salvar os dados do usuário?')){
	
		$.ajax({
			url: 'salvar_usuarios.php',
			type: 'post',
			datatype: 'text',
			data: {usuario : usuario, nome : nome, email : email, departamento : departamento, senha : senha, tipo : tipo, status : status},

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