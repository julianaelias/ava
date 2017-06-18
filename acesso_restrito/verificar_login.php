<?php
session_start();

	
	require_once("../conf/confBD.php");
	
      if(isset($_POST["login"])){		//existe um login enviado via POST (formulário)
            $log = utf8_encode(htmlspecialchars($_POST["login"]));
            $senha = utf8_encode(htmlspecialchars($_POST["senha"]));
			/*if(isset($_POST["lembrarLogin"])){
				$lembrar = utf8_encode(htmlspecialchars($_POST["lembrarLogin"]));
			}else{
			    $lembrar="";
			}*/
      }else if(!empty($_SESSION['email']) &&  !empty($_SESSION['senha'])){
		   $log = $_SESSION['email'];
		   $senha = $_SESSION['senha'];		   
		  
	  }
      /*else if(isset($_COOKIE["loginSenhaAVA"])){ 	//existe um cookie com nome senha --> login automático
            $log = utf8_encode(htmlspecialchars($_COOKIE["loginAVA"]));
            $senha = utf8_encode(htmlspecialchars($_COOKIE["loginSenhaAVA"]));
		
		   }*/
        else{
	  	       header("Location:erro_login.php");
               die();
		}  
		

 try{
		// instancia objeto PDO, conectando no mysql
		$conexao = conn_mysql();
						
		// instrução SQL básica (sem restrição de nome)
		$SQLSelect = 'SELECT usuario, email, nome, senha, tipo FROM usuarios WHERE senha=? AND email=?';
				
		//prepara a execução da sentença
		$operacao = $conexao->prepare($SQLSelect);					  
				
		//executa a sentença SQL com o valor passado por parâmetro
		$pesquisar = $operacao->execute(array($senha, $log));
		
		//captura TODOS os resultados obtidos
		$resultados = $operacao->fetchAll();
		
		// fecha a conexão (os resultados já estão capturados)
		$conexao = null;
		
		
		// se há zero ou mais de um resultado, login inválido.
		if (count($resultados)!=1){	
			header("Location:erro_login.php");
            die();
		}   
		else{ // se há um resultado, login confirmado.
			/*setcookie("loginAVA", $log, time()+60*60*24*90); //guarda o login por 90 dias a partir de agora
			if(!empty($lembrar)){
 			    setcookie("loginSenhaAVA", $senha, time()+60*60*24*90); //guarda a senha por 90 dias a partir de agora	
			}*/
		   $_SESSION['auth']=true;
		   foreach($resultados as $valor){
			   $_SESSION['codigo'] = $valor['usuario'];
			   $_SESSION['email'] = $valor['email'];
			   $_SESSION['nome'] = utf8_encode($valor['nome']);
			   $_SESSION['tipo'] = utf8_encode($valor['tipo']);
			   $_SESSION['senha'] = $valor['senha'];
			   
		   }
		   
		   if($valor['tipo'] == 1){
			    header("Location: ../admin/area_admin.php");			   
		   }else if($valor['tipo'] == 2){
		        header("Location: ../usuario/area_aluno.php");	
		   }
		  
		   die();
		}
	} //try
	catch (PDOException $e)
	{
		// caso ocorra uma exceção, exibe na tela
		echo "Erro!: " . $e->getMessage() . "<br>";
		die();
	}
?>