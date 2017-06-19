<?php
session_start();
if(empty($_SESSION['auth'])||($_SESSION['auth']!=true)){
  header("Location:./ava/acesso_restrito/acessoNegado.php");
  die();
}
?>