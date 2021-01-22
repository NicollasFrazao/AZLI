<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['adicionar']) || !isset($_GET['alterar']) || !isset($_GET['excluir']))
	{
		exit;
	}
	
	$adicionar = mysql_escape_string($_GET['adicionar']);
	$alterar = mysql_escape_string($_GET['alterar']);
	$excluir = mysql_escape_string($_GET['excluir']);
	
	if (trim($adicionar) != '')
	{	
		$adicionar = explode('{}', $adicionar);
		
		foreach ($adicionar as $aux)
		{
			$aux = explode(';', $aux);
			
			$codigo = $aux[0];
			$nome = $aux[1];
			
			$result = $conexaoPrincipal -> Query("insert into tb_fornecedor(cd_fornecedor, nm_fornecedor) values('$codigo', '$nome')");
		}
	}
	
	if (trim($alterar) != '')
	{
		$alterar = explode('{}', $alterar);
		
		foreach ($alterar as $aux)
		{
			$aux = explode(';', $aux);
			
			$codigo = $aux[0];
			$nome = $aux[1];
			
			$result = $conexaoPrincipal -> Query("update tb_fornecedor set nm_fornecedor = '$nome' where cd_fornecedor = '$codigo'");
		}
	}
	
	if (trim($excluir) != '')
	{
		$excluir = explode('{}', $excluir);
		
		foreach ($excluir as $aux)
		{
			$codigo = $aux;
			
			$result = $conexaoPrincipal -> Query("delete from tb_fornecedor where cd_fornecedor = '$codigo'");
		}
	}
	
	echo 1;
	
	$conexaoPrincipal -> FecharConexao();
?>