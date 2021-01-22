<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigoAparelho']) || (!isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']) && !isset($_GET['codigoOrdem'])))
	{
		exit;
	}
	
	$codigoOrdem = mysql_escape_string($_GET['codigoOrdem']);
	$codigoAparelho = mysql_escape_string($_GET['codigoAparelho']);
	
	if ($codigoOrdem == '')
	{
		$codigosAparelho = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
		$codigosAparelho = explode(';-;', $codigosAparelho);
		$problemasAparelho = $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho'];
		$problemasAparelho = explode(';-;', $problemasAparelho);
		array_splice($codigosAparelho, array_search($codigoAparelho, $codigosAparelho), 1);
		array_splice($problemasAparelho, array_search($codigoAparelho, $codigosAparelho), 1);
		
		$auxCodigos = '';
		$auxProblemas = '';
							
		for ($cont = 0; $cont <= count($codigosAparelho) - 1; $cont = $cont + 1)
		{
			if ($cont == 0)
			{
				$auxCodigos = $auxCodigos.$codigosAparelho[$cont];
				$auxProblemas = $auxProblemas.$problemasAparelho[$cont];
			}
			else
			{
				$auxCodigos = $auxCodigos.';-;'.$codigosAparelho[$cont];
				$auxProblemas = $auxProblemas.';-;'.$problemasAparelho[$cont];
			}
		}
		
		setcookie("AZLI[OrdemRecente][codigoAparelho]", $auxCodigos, time() + (86400 * 1), "/");
		setcookie("AZLI[OrdemRecente][problemasAparelho]", $auxProblemas, time() + (86400 * 1), "/");
		
		echo 1;
	}
	else
	{
		$result = $conexaoPrincipal -> Query("delete from item_os where cd_os = '$codigoOrdem' and cd_aparelho = '$codigoAparelho'");
		
		if ($result)
		{
			echo 1;
		}
		else
		{
			echo '0;-;'.mysqli_error($conexaoPrincipal -> getConexao());
		}
	}
?>