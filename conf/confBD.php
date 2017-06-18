<?php
	function conn_mysql(){

		$servidor = 'mysql.hostinger.com.br';
		$porta = 3306;
		$banco = "u576607581_ava";
		$usuario = "u576607581_ava";
		$senha = "18041990";
	   
		$conn = new PDO("mysql:host=$servidor;
					   port=$porta;
					   dbname=$banco", 
					   $usuario, 
					   $senha,
					   array(PDO::ATTR_PERSISTENT => true)
					   );
		return $conn;
	}
?>