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
			
			$result = $conexaoPrincipal -> Query("insert into tb_categoria(cd_categoria, nm_categoria, ic_editavel) values('$codigo', '$nome', 1)");
			
			if (!$result)
			{
				echo mysqli_error($conexaoPrincipal -> getConexao());
			}
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
			
			$result = $conexaoPrincipal -> Query("update tb_categoria set nm_categoria = '$nome' where cd_categoria = '$codigo'");
		}
	}
	
	if (trim($excluir) != '')
	{
		$excluir = explode('{}', $excluir);
		
		foreach ($excluir as $aux)
		{
			$codigo = $aux;
			
			$result = $conexaoPrincipal -> Query("delete from tb_categoria where cd_categoria = '$codigo'");
		}
	}
	
	echo 1;
	
	$conexaoPrincipal -> FecharConexao();
?>