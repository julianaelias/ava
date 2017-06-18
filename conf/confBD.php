<?php
	function conn_mysql(){

		$servidor = 'localhost';
		$porta = 3306;
		$banco = "ava";
		$usuario = "root";
		$senha = "";
	   
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