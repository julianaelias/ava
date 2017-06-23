<?php
//require_once("acesso_restrito/authSession.php");
require_once("conf/confBD.php");
include_once("includes/cabecalho.php");
?>
<div id="tudo">
	<div class="fundoTopoIndex">
         <ol class="breadcrumb">
        	<h1 class="tituloBreadcrumb">Bem Vindo ao AVA</h1>
		</ol>
     </div>
        <div class="container caixa">
        <form class="form-signin" role="form" method="post" action="acesso_restrito/verificar_login.php">
            <div class="panel panel-primary">
            	<div class="panel-heading">Faça seu login para acessar o AVA</div>
                <div class="panel-body">
                    <div class="row espaco">         	
                        <div class="col-xs-0 col-sm-2 col-md-2"></div>
                        <div class="col-xs-12 col-sm-8 col-md-8">
                            <input type="text" class="form-control" placeholder="Usuário" name="login" required autofocus >
                        </div>
                        <div class="col-xs-0 col-sm-2 col-md-2"></div>
                    </div>
                    <div class="row espaco">
                        <div class="col-xs-0 col-sm-2 col-md-2"></div>
                        <div class="col-xs-12 col-sm-8 col-md-8">
                            <input type="password" class="form-control" placeholder="Senha" name="senha" required>
                        </div>
                        <div class="col-xs-0 col-sm-2 col-md-2"></div>
                    </div>
                    <!--<div class="row espaco">
                        <div class="col-xs-0 col-sm-2 col-md-3"></div>
                        <div class="col-xs-12 col-sm-8 col-md-6">
                            <label><input type="checkbox"  name="lembrarLogin" value="loginAutomatico">&nbsp;Permanecer Conectado</label>
                        </div>
                        <div class="col-xs-0 col-sm-2 col-md-3"></div>
                    </div>-->
                     <div class="row espaco">
                        <div class="col-xs-0 col-sm-2 col-md-2"></div>
                        <div class="col-xs-12 col-sm-8 col-md-8">
                             <button class="btn btn-lg btn-primary btn-block" type="submit">ENTRAR</button>
                        </div>
                        <div class="col-xs-0 col-sm-2 col-md-2"></div>
                    </div>
              </div>
			</div>
			
            </form>
		</div>
<div id="clear"></div>
<?php
include_once("includes/rodape.php");?>