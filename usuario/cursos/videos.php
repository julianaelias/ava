<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");
include_once("../../includes/cabecalho_aluno.php");


$url = '';

if(!empty($_GET['url'])){
	$url = $_GET['url'];
}

if(!empty($_GET['curso'])){
	$curso = $_GET['curso'];
}

$inscricao = '';

if(!empty($_GET['inscricao'])){
	$inscricao = $_GET['inscricao'];
}

	
?>
<div id="tudo">
	<div class="fundoTopo2">
    	<ol class="breadcrumb">
        	<h1 class="tituloBreadcrumb">Player de Vídeos</h1>
             <li><a  href="/ava/usuario/area_aluno.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a></li>
             <li><a href="/ava/usuario/cursos/cursos.php">Cursos</a></li>
             <li><a href="/ava/usuario/cursos/conteudo.php?inscricao=<?php echo $inscricao;?>&curso=<?php echo $curso;?>">Acessar Conteúdo</a></li>
            <li class="active">Player de Vídeos</li>
		</ol>
    </div>    
<?php if($url != ''){ ?>
    <div class="container">
        <div class="row">
			<div class="col-md-12">
				<div class="embed-responsive embed-responsive-16by9">
              		<iframe class="embed-responsive-item" src="<?php echo $url;?>"></iframe>
            	</div>
            </div>
		</div>
        <div class="row">
			<div class="col-md-12" style="text-align:center; font-weight:bold; color:#337AB7; font-size:16px;">
            	Para ver o vídeo, clique sobre o botão <i class="fa fa-youtube-play" aria-hidden="true"></i> acima.
            </div>
		</div>        
	</div>
<?php }else{ ?> 

    <div class="container">
        <div class="row">
  			<div class="col-md-12">
            	<h2 class="subTitulo">Falha ao encontrar a URL do vídeo.</h2>
            </div>
		</div>
	</div>

<?php } ?> 	
</br>
	<div id="clear"></div>

<?php
include_once("../../includes/rodape.php");
?>