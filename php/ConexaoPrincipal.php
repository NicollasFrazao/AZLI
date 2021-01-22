<?php
	$host = 'localhost';
	$user = 'anyte539_admin';
	$pass = 'anytech.all';
	$banco = 'anyte539_azli';

	$conexaoPrincipal = new Conexao();
	$conexaoPrincipal -> AbrirConexao($host, $user, $pass, $banco);
?>