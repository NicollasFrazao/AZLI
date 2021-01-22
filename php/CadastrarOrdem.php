<?php
	//session_start();
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_POST['dataOrcamento']) || !isset($_POST['status']) || !isset($_POST['codigoOrdemDatas']))
	{
		exit;
	}
	
	if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
	{
		$codigoAparelho = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
	}
	else
	{
		$codigoAparelho = '';
	}
	
	if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoCliente']))
	{
		$codigoCliente = $_COOKIE['AZLI']['OrdemRecente']['codigoCliente'];
	}
	else
	{
		$codigoCliente = '';
	}
	
	$codigoAparelho = explode(';-;', $codigoAparelho);
	
	if (isset($_POST['status']))
	{
		$status = mysql_escape_string($_POST['status']);
	}
	else
	{
		$status = '';
	}
	
	if (isset($_POST['funcionario']))
	{
		$funcionario = mysql_escape_string($_POST['funcionario']);
	}
	else
	{
		$funcionario = '';
	}
	
	if (isset($_POST['dataOrcamento']) && $_POST['dataOrcamento'] != '')
	{
		$dataOrcamento = mysql_escape_string($_POST['dataOrcamento']);
		$dataOrcamento = explode('/', $dataOrcamento);
		$dataOrcamento = $dataOrcamento[2].'-'.$dataOrcamento[1].'-'.$dataOrcamento[0];
	}
	else
	{
		$dataOrcamento = '';
	}
	
	if (isset($_POST['dataConfirmacao']) && $_POST['dataConfirmacao'] != '')
	{
		$dataConfirmacao = mysql_escape_string($_POST['dataConfirmacao']);
		$dataConfirmacao = explode('/', $dataConfirmacao);
		$dataConfirmacao = $dataConfirmacao[2].'-'.$dataConfirmacao[1].'-'.$dataConfirmacao[0];
	}
	else
	{
		$dataConfirmacao = '';
	}
	
	if (isset($_POST['dataFinalizacao']) && $_POST['dataFinalizacao'] != '')
	{
		$dataFinalizacao = mysql_escape_string($_POST['dataFinalizacao']);
		$dataFinalizacao = explode('/', $dataFinalizacao);
		$dataFinalizacao = $dataFinalizacao[2].'-'.$dataFinalizacao[1].'-'.$dataFinalizacao[0];
	}
	else
	{
		$dataFinalizacao = '';
	}
	
	if (isset($_POST['dataRetirada']) && $_POST['dataRetirada'] != '')
	{
		$dataRetirada = mysql_escape_string($_POST['dataRetirada']);
		$dataRetirada = explode('/', $dataRetirada);
		$dataRetirada = $dataRetirada[2].'-'.$dataRetirada[1].'-'.$dataRetirada[0];
	}
	else
	{
		$dataRetirada = '';
	}
	
	if (isset($_POST['dataExpiracao']) && $_POST['dataExpiracao'] != '')
	{
		$dataExpiracao = mysql_escape_string($_POST['dataExpiracao']);
		$dataExpiracao = explode('/', $dataExpiracao);
		$dataExpiracao = $dataExpiracao[2].'-'.$dataExpiracao[1].'-'.$dataExpiracao[0];
	}
	else
	{
		$dataExpiracao = '';
	}
	
	if (isset($_POST['notas']))
	{
		$notas = mysql_escape_string($_POST['notas']);
	}
	else
	{
		$notas = '';
	}
	
	$result = $conexaoPrincipal -> Query("select cd_os from tb_os order by cd_os desc limit 1");
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	if ($total > 0)
	{
		$ultimaOrdem = $linha['cd_os'] + 1;
	}
	else
	{
		$ultimaOrdem = 1;
	}
	
	if (isset($_POST['codigoOrdemDatas']) && $_POST['codigoOrdemDatas'] != '')
	{
		$codigoOrdem = mysql_escape_string($_POST['codigoOrdemDatas']);
		
		$result = $conexaoPrincipal -> Query("update tb_os set cd_funcionario = '$funcionario', cd_status = '$status', dt_orcamento = ".(($dataOrcamento != '') ? "'$dataOrcamento'" : "null").", dt_inicio = ".(($dataConfirmacao != '') ? "'$dataConfirmacao'" : "null").", dt_finalizacao = ".(($dataFinalizacao != '') ? "'$dataFinalizacao'" : "null").", dt_retirada = ".(($dataRetirada != '') ? "'$dataRetirada'" : "null").", dt_expiracao = ".(($dataExpiracao != '') ? "'$dataExpiracao'" : "null").", ds_os = '$notas' where cd_os = '$codigoOrdem'");
		
		if (!$result)
		{
			echo '0;-;'.mysqli_error($conexaoPrincipal -> getConexao());
		}
		else
		{
			echo 1;
		}
	}
	else //*if (!isset($_COOKIE['AZLI']['OrdemRecente']['codigoOrdem']))*/
	{
		do
		{
			$codigoVerificador = rand(1, 99999999999);
			
			if ($codigoVerificador == 2147483647)
			{
				//$total = 1;
				$result = false;
			}
			else
			{
				$result = $conexaoPrincipal -> Query("insert into tb_os(cd_os, cd_funcionario, cd_cliente, cd_status, dt_orcamento, dt_inicio, dt_finalizacao, dt_retirada, dt_expiracao, ds_os, cd_verificador, dt_cadastro) values('$ultimaOrdem', '$funcionario', '$codigoCliente', '$status', ".(($dataOrcamento != '') ? "'$dataOrcamento'" : "null").", ".(($dataConfirmacao != '') ? "'$dataConfirmacao'" : "null").", ".(($dataFinalizacao != '') ? "'$dataFinalizacao'" : "null").", ".(($dataRetirada != '') ? "'$dataRetirada'" : "null").", ".(($dataExpiracao != '') ? "'$dataExpiracao'" : "null").", '$notas', '$codigoVerificador', now())");
				//$result = $conexaoPrincipal -> Query("select cd_verificador from tb_os where cd_verificador = '$codigoVerificador'");
				//$total = mysqli_num_rows($result);	
			}
			/*if (!$result)
			{
				$aviso = mysqli_error($conexaoPrincipal -> getConexao());
			}
			else
			{
				$aviso = '';
			}
			
			//$aviso = strpos($aviso, 'Duplicate');*/
		}
		while (!$result);
		
		//echo $codigoVerificador;

		if ($result)
		{
			$codigoOrdem = $ultimaOrdem;
			
			//print_r($codigoAparelho);
			//echo $aviso.' - '.$codigoVerificador;
			//echo $linha['cd_os'];
			//echo $codigoOrdem;
			//exit;
			
			if (isset($_COOKIE['AZLI']['OrdemRecente']['problemasAparelho']))
			{
				$problemasAparelho = $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho'];
				$problemasAparelho = explode(';-;', $problemasAparelho);
			}
			else
			{
				$problemasAparelho = '';
			}
			
			//$_SESSION['AZLI']['OrdemRecente']['codigoCliente'] = $codigoCliente;
			setcookie("AZLI[OrdemRecente][codigoOrdem]", $codigoOrdem, time() + (86400 * 1), "/"); // 86400 = 1 day
			setcookie("AZLI[OrdemRecente][codigoVerificador]", $codigoVerificador, time() + (86400 * 1), "/"); // 86400 = 1 day
			
			$query = "insert into item_os(cd_aparelho, cd_os, ds_item_os) values";
			
			for ($cont = 0; $cont <= count($codigoAparelho) - 1; $cont = $cont + 1)
			{
				$aux = $codigoAparelho[$cont];
				$auxx = ''/*$problemasAparelho[$cont]*/;
				
				if ($cont == 0)
				{
					$query = $query."('$aux', '$codigoOrdem', '$auxx')";
				}
				else
				{
					$query = $query.", ('$aux', '$codigoOrdem', '$auxx')";
				}
			}
			
			//$result = false;
			//echo $query;
			//exit;
			for ($cont = 1; $cont <= 5; $cont = $cont + 1)
			{
				$result = $conexaoPrincipal -> Query($query);
				
				if ($result)
				{
					break;
				}
			}
			
			if ($result)
			{
				if ($_POST['etapaOrdem'] == $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'])
				{
					//$_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] = $_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] + 1;
					setcookie("AZLI[OrdemRecente][etapaOrdem]", $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1, time() + (86400 * 1), "/"); // 86400 = 1 day
				}
				
				if (isset($_COOKIE['AZLI']['OrdemRecente']['codigosServico']))
				{
					$servicos = $_COOKIE['AZLI']['OrdemRecente']['codigosServico'];
					$servicos = explode(';-;', $servicos);
					
					$query = "update tb_servico set cd_os = '$codigoOrdem' where ";
					$cont = 0;
					
					foreach ($servicos as $servico)
					{
						if ($cont == 0)
						{
							$query = $query."cd_servico = '$servico'";
						}
						else
						{
							$query = $query." or cd_servico = '$servico'";
						}
						
						$cont = $cont + 1;
					}
					
					$result = $conexaoPrincipal -> Query($query);
					
					if (!$result)
					{
						echo mysqli_error($conexaoPrincipal -> getConexao());
					}
					
					$aviso = ';-;Cadastrado da ordem de serviço realizado com sucesso!;-;'.($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1).';-;'.$codigoVerificador;
			
					include 'CancelarOrdem.php';
				}
			}
			else
			{
				echo '0;-;Não foi possível realizar o cadastro do aparelho na ordem de serviço!'.mysqli_error($conexaoPrincipal -> getConexao()).';-;'.($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1);
			}
		}
		else
		{
			echo '0;-;Não foi possível realizar o cadastro da ordem de serviço! '.mysqli_error($conexaoPrincipal -> getConexao()).';-;'.($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1);
			//echo mysqli_error($conexaoPrincipal -> getConexao());
		}
	}
	/*else
	{
		$codigoOrdem = $_COOKIE['AZLI']['OrdemRecente']['codigoOrdem'];
		$codigoVerificador = $_COOKIE['AZLI']['OrdemRecente']['codigoVerificador'];
			
		if (isset($_COOKIE['AZLI']['OrdemRecente']['problemasAparelho']))
		{
			$problemasAparelho = '';//$_COOKIE['AZLI']['OrdemRecente']['problemasAparelho'];
		}
		else
		{
			$problemasAparelho = '';
		}
		
		$result = $conexaoPrincipal -> Query("insert into item_os(cd_aparelho, cd_os, ds_item_os) values('$codigoAparelho', '$codigoOrdem', '$problemasAparelho')");
			
		if ($result)
		{
			if ($_POST['etapaOrdem'] == $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'])
			{
				//$_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] = $_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] + 1;
				setcookie("AZLI[OrdemRecente][etapaOrdem]", $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1, time() + (86400 * 1), "/"); // 86400 = 1 day
			}
			
			$aviso = ';-;Cadastrado da ordem de serviço realizado com sucesso!;-;'.($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1).';-;'.$codigoVerificador;
			
			include 'CancelarOrdem.php';
		}
		else
		{
			echo '0;-;Não foi possível realizar o cadastro do aparelho na ordem de serviço!'.mysqli_error($conexaoPrincipal -> getConexao()).';-;'.($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1);
		}
	}*/
	
	$conexaoPrincipal -> FecharConexao();
?>