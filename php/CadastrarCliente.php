<?php
	//session_start();
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_POST['RG']) && !isset($_POST['CPF']))
	{
		exit;
	}
	
	if (isset($_POST['nome']))
	{
		$nome = mysql_escape_string($_POST['nome']);
	}
	else
	{
		$nome = '';
	}
	
	if (isset($_POST['RG']))
	{
		$RG = mysql_escape_string($_POST['RG']);
	}
	else
	{
		$RG = '';
	}
	
	if (isset($_POST['CPF']))
	{
		$CPF = mysql_escape_string($_POST['CPF']);
	}
	else
	{
		$CPF = '';
	}
	
	if (isset($_POST['telefone1']))
	{
		$telefone1 = mysql_escape_string($_POST['telefone1']);
	}
	else
	{
		$telefone1 = '';
	}
	
	if (isset($_POST['telefone2']))
	{
		$telefone2 = mysql_escape_string($_POST['telefone2']);
	}
	else
	{
		$telefone2 = '';
	}
	
	if (isset($_POST['endereco']))
	{
		$endereco = mysql_escape_string($_POST['endereco']);
	}
	else
	{
		$endereco = '';
	}
	
	if (isset($_POST['referencias']))
	{
		$referencias = mysql_escape_string($_POST['referencias']);
	}
	else
	{
		$referencias = '';
	}
	
	if (isset($_POST['codigo']))
	{
		$codigo = mysql_escape_string($_POST['codigo']);
	}
	else
	{
		$codigo = '';
	}
	
	if (isset($_POST['codigoOrdem']))
	{
		$codigoOrdem = mysql_escape_string($_POST['codigoOrdem']);
	}
	else
	{
		$codigoOrdem = '';
	}
	
	$RG = str_replace('.', '', $RG);
	$RG = str_replace('-', '', $RG);
	
	$CPF = str_replace('.', '', $CPF);
	$CPF = str_replace('-', '', $CPF);
	
	$telefone1 = str_replace('(', '', $telefone1);
	$telefone1 = str_replace(')', '', $telefone1);
	$telefone1 = str_replace(' ', '', $telefone1);
	$telefone1 = str_replace('-', '', $telefone1);
	
	$telefone2 = str_replace('(', '', $telefone2);
	$telefone2 = str_replace(')', '', $telefone2);
	$telefone2 = str_replace(' ', '', $telefone2);
	$telefone2 = str_replace('-', '', $telefone2);
	
	$proximo = $_POST['etapaOrdem'] + 1;
	
	$result = $conexaoPrincipal -> Query("select cd_cliente from tb_cliente where cd_cliente = '$codigo'");
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	if ($codigo == '')
	{
		$codigoCliente = $linha['cd_cliente'];
	}
	else
	{
		$codigoCliente = $codigo;
	}
	
	if ($total == 0)
	{
		$result = $conexaoPrincipal -> Query("insert into tb_cliente(nm_cliente, cd_rg, cd_cpf, cd_telefone1, cd_telefone2, nm_endereco, ds_referencias, dt_cadastro) values('$nome', ".(($RG != '') ? "'$RG'" : "null").", ".(($CPF != '') ? "'$CPF'" : "null").", '$telefone1', '$telefone2', '$endereco', '$referencias', now())");
		
		if ($result)
		{
			if ($CPF == '')
			{
				$result = $conexaoPrincipal -> Query("select cd_cliente from tb_cliente where cd_rg = '$RG'");
			}
			else
			{
				$result = $conexaoPrincipal -> Query("select cd_cliente from tb_cliente where cd_cpf = '$CPF'");
			}
			
			$linha = mysqli_fetch_assoc($result);
			$total = mysqli_num_rows($result);
			
			$codigoCliente = $linha['cd_cliente'];
			
			if ($codigoOrdem == '')
			{
				//$_SESSION['AZLI']['OrdemRecente']['codigoCliente'] = $codigoCliente;
				setcookie("AZLI[OrdemRecente][codigoCliente]", $codigoCliente, time() + (86400 * 1), "/"); // 86400 = 1 day
				
				if ($_POST['etapaOrdem'] == $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'])
				{
					//$_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] = $_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] + 1;
					setcookie("AZLI[OrdemRecente][etapaOrdem]", $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1, time() + (86400 * 1), "/"); // 86400 = 1 day
				}
				
				echo '1;-;Cadastrado do cliente realizado com sucesso!;-;'.$proximo.';-;'.$codigoCliente;
			}
			else
			{
				$result = $conexaoPrincipal -> Query("update tb_os set cd_cliente = '$codigoCliente' where cd_os = '$codigoOrdem'");
				
				if ($result)
				{
					echo '1;-;Cliente da ordem de serviço alterado com sucesso!;-;'.$codigoCliente;
				}
				else
				{
					echo '0;-;Não foi possível alterar o cliente da ordem de serviço! '.mysqli_error($conexaoPrincipal -> getConexao());
				}
			}
		}
		else
		{
			echo '0;-;Não foi possível realizar o cadastro do cliente! Já existe um usuário que contém o mesmo RG ou CPF digitado, utilize outro e tente novamente. '.mysqli_error($conexaoPrincipal -> getConexao()).';-;'.$proximo.';-;'.$codigoCliente;
		}
	}
	else
	{
		if (isset($_POST['editar']) && mysql_escape_string($_POST['editar']) == 1)
		{
			$result = $conexaoPrincipal -> Query("update tb_cliente set nm_cliente = '$nome', cd_telefone1 = '$telefone1', cd_telefone2 = '$telefone2', nm_endereco = '$endereco', ds_referencias = '$referencias' where cd_cliente = '$codigoCliente'");
			
			if ($result)
			{
				if ($codigoOrdem == '')
				{
					//$_SESSION['AZLI']['OrdemRecente']['codigoCliente'] = $codigoCliente;
					setcookie("AZLI[OrdemRecente][codigoCliente]", $codigoCliente, time() + (86400 * 1), "/"); // 86400 = 1 day
					
					if ($_POST['etapaOrdem'] == $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'])
					{
						//$_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] = $_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] + 1;
						setcookie("AZLI[OrdemRecente][etapaOrdem]", $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1, time() + (86400 * 1), "/"); // 86400 = 1 day
					}
					
					echo '1;-;Cliente alterado com sucesso!.;-;'.$proximo.';-;'.$codigoCliente;
				}
				else
				{
					$result = $conexaoPrincipal -> Query("update tb_os set cd_cliente = '$codigoCliente' where cd_os = '$codigoOrdem'");
					
					if ($result)
					{
						echo '1;-;Cliente da ordem de serviço alterado com sucesso!;-;'.$codigoCliente;
					}
					else
					{
						echo '0;-;Não foi possível alterar o cliente da ordem de serviço! '.mysqli_error($conexaoPrincipal -> getConexao());
					}
				}
			}
			else
			{
				echo '0;-;Ocorreu um erro durante o processo.;-;'.$proximo.';-;'.$codigoCliente;
			}
		}
		else
		{
			if ($codigoOrdem == '')
			{
				//$_SESSION['AZLI']['OrdemRecente']['codigoCliente'] = $codigoCliente;
				setcookie("AZLI[OrdemRecente][codigoCliente]", $codigoCliente, time() + (86400 * 1), "/"); // 86400 = 1 day
				
				if ($_POST['etapaOrdem'] == $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'])
				{
					//$_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] = $_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] + 1;
					setcookie("AZLI[OrdemRecente][etapaOrdem]", $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1, time() + (86400 * 1), "/"); // 86400 = 1 day
				}
				
				echo '1;-;Cliente já está cadastrado.;-;'.$proximo.';-;'.$codigoCliente;
			}
			else
			{
				$result = $conexaoPrincipal -> Query("update tb_os set cd_cliente = '$codigoCliente' where cd_os = '$codigoOrdem'");
				
				if ($result)
				{
					echo '1;-;Cliente da ordem de serviço alterado com sucesso!;-;'.$codigoCliente;
				}
				else
				{
					echo '0;-;Não foi possível alterar o cliente da ordem de serviço! '.mysqli_error($conexaoPrincipal -> getConexao());
				}
			}
		}
	}
	
	$conexaoPrincipal -> FecharConexao();
?>	