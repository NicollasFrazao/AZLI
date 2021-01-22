<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigo']))
	{
		exit;
	}
	
	$codigo = mysql_escape_string($_GET['codigo']);
	
	$result = $conexaoPrincipal -> Query("delete from tb_cliente where cd_cliente = '$codigo'");
	
	if ($result)
	{
		if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoCliente']) && $_COOKIE['AZLI']['OrdemRecente']['codigoCliente'] == $codigo)
		{
			include 'CancelarOrdem.php';
		}
		else
		{
			echo 1;
		}
	}
	
	$conexaoPrincipal -> FecharConexao();
?>