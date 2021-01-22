<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['alterar']) || !isset($_GET['excluir']))
	{
		exit;
	}
	
	$alterar = mysql_escape_string($_GET['alterar']);
	$excluir = mysql_escape_string($_GET['excluir']);
	
	if (trim($alterar) != '')
	{
		$alterar = explode('{}', $alterar);
		
		foreach ($alterar as $aux)
		{
			$aux = explode(';-;', $aux);
			
			$codigo = $aux[0];
			$nome = $aux[1];
			$RG = $aux[2];
			$CPF = $aux[3];
			$telefone1 = $aux[4];
			$telefone2 = $aux[5];
			$endereco = $aux[6];
			$referencias = $aux[7];
			
			$result = $conexaoPrincipal -> Query("update tb_cliente set nm_cliente = '$nome', cd_rg = ".(($RG != '') ? "'$RG'" : "null").", cd_cpf = ".(($CPF != '') ? "'$CPF'" : "null").", cd_telefone1 = '$telefone1', cd_telefone2 = '$telefone2', nm_endereco = '$endereco', ds_referencias = '$referencias' where cd_cliente = '$codigo'");
		}
	}
	
	if (trim($excluir) != '')
	{
		$excluir = explode('{}', $excluir);
		
		foreach ($excluir as $aux)
		{
			$codigo = $aux;
			
			$result = $conexaoPrincipal -> Query("delete from tb_cliente where cd_cliente = '$codigo'");
			
			if (!$result)
			{
				echo mysqli_error($conexaoPrincipal -> getConexao());
			}
		}
	}
	
	echo 1;
	
	$conexaoPrincipal -> FecharConexao();
?>