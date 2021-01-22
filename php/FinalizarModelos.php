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
			$marca = $aux[2];
			$categoria = $aux[3];
			
			$result = $conexaoPrincipal -> Query("insert into tb_modelo(cd_modelo, nm_modelo, cd_marca, cd_categoria, ic_editavel) values('$codigo', '$nome', '$marca', '$categoria', 1)");
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
			
			$result = $conexaoPrincipal -> Query("update tb_modelo set nm_modelo = '$nome' where cd_modelo = '$codigo'");
		}
	}
	
	if (trim($excluir) != '')
	{
		$excluir = explode('{}', $excluir);
		
		foreach ($excluir as $aux)
		{
			$codigo = $aux;
			
			$result = $conexaoPrincipal -> Query("delete from tb_modelo where cd_modelo = '$codigo'");
		}
	}
	
	echo 1;
	
	$conexaoPrincipal -> FecharConexao();
?>