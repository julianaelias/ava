<?php
require_once("authSession.php");

	$_SESSION = array();  //Limpa o vetor de sessão

	// Se queremos terminar a própria sessão, precisamos matar o cookie com o session_ID
	if (ini_get("session.use_cookies")) {					//verifica se a sessão usa cookies
		$params = session_get_cookie_params();				//carrega todos os parâmetros do cookie da sessão
		setcookie(session_name(), '', time() - 42000,		//configura um cookie exatamente igual para 42000seg (700h) atrás
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	
	setcookie('loginSenhaAVA', '', time() - 42000);	
	setcookie('loginAVA', '', time() - 42000);		
	
}
	session_destroy();		// Destruímos a sessão em si
	header("Location: ../index.php");		//redirecionando para a página principal
?>
