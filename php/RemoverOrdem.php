<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigoOrdemRemover']))
	{
		exit;
	}
	
	$codigoOrdem = mysql_escape_string($_GET['codigoOrdemRemover']);
	
	if ($codigoOrdem != '')
	{
		$result = $conexaoPrincipal -> Query("delete from tb_os where cd_os = '$codigoOrdem'");
		
		if ($result)
		{
			echo '1;-;Ordem de serviço removida com sucesso!';
		}
		else
		{
			echo '0;-;'.mysqli_error($conexaoPrincipal -> getConexao());
		}
	}
?>