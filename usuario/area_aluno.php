<?php
require_once("../acesso_restrito/authSession.php");
require_once("../conf/confBD.php");
include_once("../includes/cabecalho_aluno.php");
?>
<div id="tudo">
	<div class="container">
    	<div class="fundo">
        	<h1 class="tituloBreadcrumb ">Área do Aluno</h1>
            <span class="textoAzul" style="text-align:right !important;">
                	Olá <?php echo $_SESSION['nome']; ?>, seja bem vindo(a)!
             </span>
         </div>
	</div><!-- /.container -->
    

<?php
include_once("../includes/rodape.php");
?>