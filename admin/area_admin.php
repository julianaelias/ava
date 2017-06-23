<?php
require_once("../acesso_restrito/authSession.php");
require_once("../conf/confBD.php");
include_once("../includes/cabecalho_admin.php");
?>
<div id="tudo">
	<div class="fundoTopo">
        	<h1 class="tituloBreadcrumb ">Área do Administrador</h1>
            <span class="textoAzul" style="text-align:right !important;">
                	Olá <?php echo $_SESSION['nome']; ?>, seja bem vindo(a)!
             </span>
	</div><!-- /.container -->
       

<?php
include_once("../includes/rodape.php");
?>