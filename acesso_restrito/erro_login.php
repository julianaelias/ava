<?php
/*setcookie("loginAVA", '', time()-42000); 
setcookie("loginSenhaAVA", '', time()-42000); */

require_once("../conf/confBD.php");
include_once("../includes/cabecalho.php");
?>
<div id="tudo">
	<div class="fundoTopo2">            
    	<div class="breadcrumb">
        	<h1 class="tituloBreadcrumb ">Erro no Acesso Restrito</h1>
         </div>
     </div>
     <div class="container">
        <div class="row espaco">         	
  			<div class="col-xs-0 col-sm-2 col-md-3"></div>
            <div class="col-xs-12 col-sm-8 col-md-6">
            	<h3 class="tituloPrincipal">Não foi possível acessar o sistema. Clique no botão abaixo e tente novamente.</h3>
            </div>
            <div class="col-xs-0 col-sm-2 col-md-3"></div>
		</div>
        <div class="row espaco">
  			<div class="col-xs-0 col-sm-2 col-md-3"></div>
            <div class="col-xs-12 col-sm-8 col-md-6">
                 <a class="btn btn-lg btn-primary btn-block" href="/ava/index.php" role="button">NOVO ACESSO</a>
            </div>
            <div class="col-xs-0 col-sm-2 col-md-3"></div>
		</div>
    </div><!-- /.container -->

<?php
include_once("../includes/rodape.php");
?>