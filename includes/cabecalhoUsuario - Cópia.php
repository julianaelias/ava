<!DOCTYPE html>
<html lang="pt-br">
	<head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Language" content="pt-br">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="../assets/ico/favicon.ico">
        
        <title>AVA</title>
                
        <!-- Bootstrap -->
        <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font-awesome -->
        <link rel="stylesheet" href="./font-awesome/css/font-awesome.min.css">
        <style>
			.navbar-default {
			  background-color: #337ab7;
			  border-color: #2f6a9d;
			}
			.navbar-default .navbar-brand {
			  color: #ffffff;
			}
			.navbar-default .navbar-brand:hover,
			.navbar-default .navbar-brand:focus {
			  color: #e5e5e5;
			}
			.navbar-default .navbar-text {
			  color: #ffffff;
			}
			.navbar-default .navbar-nav > li > a {
			  color: #ffffff;
			}
			.navbar-default .navbar-nav > li > a:hover,
			.navbar-default .navbar-nav > li > a:focus {
			  color: #e5e5e5;
			}
			.navbar-default .navbar-nav > .active > a,
			.navbar-default .navbar-nav > .active > a:hover,
			.navbar-default .navbar-nav > .active > a:focus {
			  color: #e5e5e5;
			  background-color: #2f6a9d;
			}
			.navbar-default .navbar-nav > .open > a,
			.navbar-default .navbar-nav > .open > a:hover,
			.navbar-default .navbar-nav > .open > a:focus {
			  color: #e5e5e5;
			  background-color: #2f6a9d;
			}
			.navbar-default .navbar-toggle {
			  border-color: #2f6a9d;
			}
			.navbar-default .navbar-toggle:hover,
			.navbar-default .navbar-toggle:focus {
			  background-color: #2f6a9d;
			}
			.navbar-default .navbar-toggle .icon-bar {
			  background-color: #ffffff;
			}
			.navbar-default .navbar-collapse,
			.navbar-default .navbar-form {
			  border-color: #ffffff;
			}
			.navbar-default .navbar-link {
			  color: #ffffff;
			}
			.navbar-default .navbar-link:hover {
			  color: #e5e5e5;
			}
			
			@media (max-width: 767px) {
			  .navbar-default .navbar-nav .open .dropdown-menu > li > a {
				color: #ffffff;
			  }
			  .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
			  .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
				color: #e5e5e5;
			  }
			  .navbar-default .navbar-nav .open .dropdown-menu > .active > a,
			  .navbar-default .navbar-nav .open .dropdown-menu > .active > a:hover,
			  .navbar-default .navbar-nav .open .dropdown-menu > .active > a:focus {
				color: #e5e5e5;
				background-color: #2f6a9d;
			  }
			}
		</style>
	</head>
  	<body>
    
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
            	<div class="navbar-header">
              		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
             		 </button>
              		<a class="navbar-brand" href="index.php"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;AVA</a>
            	</div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
                    	<?php /*?><li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li><?php */?>
                    	<li><a href="#"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;CURSOS</a></li>
                        <li><a href="#"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;FALE CONOSCO</a></li>
                    	<?php /*?><li class="dropdown">
                      		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                             aria-expanded="false">Dropdown <span class="caret"></span></a>
							<ul class="dropdown-menu">
                            	<li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                          	</ul>
   						</li><?php */?>
                  	</ul>
                  <?php /*?>
				  <form class="navbar-form navbar-left">
                    <div class="form-group">
                      <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                  </form>
				  <?php */?>
					<?php /*?><ul class="nav navbar-nav navbar-right">
                    	<li><a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;SAIR</a></li>
                  	</ul><?php */?>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
  
      <?php /*?>  <div class="navbar navbar-dark bg-primary navbar-fixed-top "  role="navigation">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="./index.php">DAW TURISMO</a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                <li><a href="./lista_voos.php">VOOS</a></li>
                <li><a href="./lista_hoteis.php">HOTEIS</a></li>
                <li><a href="./fale_conosco.php">FALE CONOSCO</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </div><?php */?>


