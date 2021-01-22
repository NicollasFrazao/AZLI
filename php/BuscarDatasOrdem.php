<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigoOrdem']))
	{
		exit;
	}
	
	$codigoOrdem = mysql_escape_string($_GET['codigoOrdem']);
	
	$result = $conexaoPrincipal -> Query("select tb_os.cd_status,
													tb_os.cd_funcionario,
												   tb_os.dt_orcamento,
												   tb_os.dt_inicio,
												   tb_os.dt_finalizacao,
												   tb_os.dt_retirada,
												   tb_os.dt_expiracao,
												   tb_os.ds_os
											  from tb_os
												 where tb_os.cd_os = '$codigoOrdem'");	
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	$dataOrcamento = $linha['dt_orcamento'];
	$dataConfirmacao = $linha['dt_inicio'];
	$dataFinalizacao = $linha['dt_finalizacao'];
	$dataRetirada = $linha['dt_retirada'];
	$dataExpiracao = $linha['dt_expiracao'];
	
	if ($dataOrcamento != '')
	{
		$dataOrcamento = date('d/m/Y', strtotime($dataOrcamento));
		$dataOrcamento = str_replace('/', '', $dataOrcamento);
	}
	
	if ($dataConfirmacao != '')
	{
		$dataConfirmacao = date('d/m/Y', strtotime($dataConfirmacao));
		$dataConfirmacao = str_replace('/', '', $dataConfirmacao);
	}
	
	if ($dataFinalizacao != '')
	{
		$dataFinalizacao = date('d/m/Y', strtotime($dataFinalizacao));
		$dataFinalizacao = str_replace('/', '', $dataFinalizacao);
	}
	
	if ($dataRetirada != '')
	{
		$dataRetirada = date('d/m/Y', strtotime($dataRetirada));
		$dataRetirada = str_replace('/', '', $dataRetirada);
	}
	
	if ($dataExpiracao != '')
	{
		$dataExpiracao = date('d/m/Y', strtotime($dataExpiracao));
		$dataExpiracao = str_replace('/', '', $dataExpiracao);
	}
	
	echo $codigoOrdem.';-;'.$linha['cd_status'].';-;'.$dataOrcamento.';-;'.$dataConfirmacao.';-;'.$dataFinalizacao.';-;'.$dataRetirada.';-;'.$dataExpiracao.';-;'.$linha['ds_os'].';-;'.$linha['cd_funcionario'];
	
	$conexaoPrincipal -> FecharConexao();
?>