<?php
setcookie("loginTurismo", '', time()-42000); 
setcookie("loginTurismoAuto", '', time()-42000); 

include_once("modelos/cabecalho_index.html");

?>

    <div class="container">

      <div>
        <h1>Acesso Negado</h1>
		<p class="lead"><a href="index.php">Clique aqui e faço o login novamente.</a></p>
        
	 </div>

	  
	  
    </div><!-- /.container -->

<?php
include_once("modelos/rodape.html");
?>