<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['valorBusca']) || !isset($_GET['codigoOrdem']))
	{
		exit;
	}
	
	$codigoOrdem = mysql_escape_string($_GET['codigoOrdem']);
	$result = false;
	
	$valorBusca = mysql_escape_string($_GET['valorBusca']);
		
	$valorBusca = str_replace('.', '', $valorBusca);
	$valorBusca = str_replace('-', '', $valorBusca);
	
	if ($valorBusca != '')
	{
		$result = $conexaoPrincipal -> Query("select cd_cliente, nm_cliente, cd_rg, cd_cpf, cd_telefone1, cd_telefone2, nm_endereco, ds_referencias from tb_cliente where cd_rg = '$valorBusca'");
		$total = mysqli_num_rows($result);
		
		if ($total == 0)
		{
			$result = $conexaoPrincipal -> Query("select cd_cliente, nm_cliente, cd_rg, cd_cpf, cd_telefone1, cd_telefone2, nm_endereco, ds_referencias from tb_cliente where cd_cpf = '$valorBusca'");
			$total = mysqli_num_rows($result);
			
			if ($total == 0)
			{
				$result = $conexaoPrincipal -> Query("select cd_cliente, nm_cliente, cd_rg, cd_cpf, cd_telefone1, cd_telefone2, nm_endereco, ds_referencias from tb_cliente where cd_cliente = '$valorBusca'");
			}
		}
	}
	else
	{
		$result = $conexaoPrincipal -> Query("select tb_cliente.cd_cliente,
													   tb_cliente.nm_cliente,
													   tb_cliente.cd_rg,
													   tb_cliente.cd_cpf,
													   tb_cliente.cd_telefone1,
													   tb_cliente.cd_telefone2,
													   tb_cliente.nm_endereco,
													   tb_cliente.ds_referencias
												  from tb_os inner join tb_cliente
													on tb_os.cd_cliente = tb_cliente.cd_cliente
													  where tb_os.cd_os = '$codigoOrdem';");
	}
	
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	if ($total != 0)
	{
		echo $linha['cd_cliente'].';-;'.$linha['nm_cliente'].';-;'.$linha['cd_rg'].';-;'.$linha['cd_cpf'].';-;'.$linha['cd_telefone1'].';-;'.$linha['cd_telefone2'].';-;'.$linha['nm_endereco'].';-;'.$linha['ds_referencias'].';-;'.$codigoOrdem;
	}
	
	$conexaoPrincipal -> FecharConexao();
?>