<?php
	//session_start();
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	//print_r($_POST['servicos']);
	
	if (!isset($_POST['IMEI']) || !isset($_POST['servicos']))
	{
		exit;
	}
	
	if (isset($_POST['marca']))
	{
		$marca = mysql_escape_string($_POST['marca']);
	}
	else
	{
		$marca = '';
	}
	
	if (isset($_POST['modelo']))
	{
		$modelo = mysql_escape_string($_POST['modelo']);
	}
	else
	{
		$modelo = '';
	}
	
	if (isset($_POST['IMEI']))
	{
		$IMEI = mysql_escape_string($_POST['IMEI']);
		
		$IMEI = str_replace('-', '', $IMEI);
	}
	else
	{
		$IMEI = '';
	}
	
	if (isset($_POST['IMEISecundario']))
	{
		$IMEISecundario = mysql_escape_string($_POST['IMEISecundario']);
		
		$IMEISecundario = str_replace('-', '', $IMEISecundario);
	}
	else
	{
		$IMEISecundario = '';
	}
	
	if (isset($_POST['numeroSerie']))
	{
		$numeroSerie = mysql_escape_string($_POST['numeroSerie']);
		
		$numeroSerie = str_replace('-', '', $numeroSerie);
		$numeroSerie = strtoupper($numeroSerie);
	}
	else
	{
		$numeroSerie = '';
	}
	
	if (isset($_POST['descricao']))
	{
		$descricao = mysql_escape_string($_POST['descricao']);
	}
	else
	{
		$descricao = '';
	}
	
	if (isset($_POST['notas']))
	{
		$notas = mysql_escape_string($_POST['notas']);
	}
	else
	{
		$notas = '';
	}
	
	if (isset($_POST['codigoOrdemAparelho']))
	{
		$codigoOrdem = mysql_escape_string($_POST['codigoOrdemAparelho']);
	}
	else
	{
		$codigoOrdem = '';
	}
	
	if (isset($_POST['codigo']))
	{
		$codigo = mysql_escape_string($_POST['codigo']);
	}
	else
	{
		$codigo = '';
	}
	
	$codigoAparelho = $codigo;
	
	if ($codigoOrdem != '')
	{
		$result = $conexaoPrincipal -> Query("select cd_cliente from tb_os where cd_os = '$codigoOrdem'");
		$linha = mysqli_fetch_assoc($result);
		$total = mysqli_num_rows($result);
		
		$codigoCliente = $linha['cd_cliente'];
	}
	else if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoCliente']))
	{		
		$codigoCliente = $_COOKIE['AZLI']['OrdemRecente']['codigoCliente'];
	}
	else
	{
		$codigoCliente = '';
	}
	
	if ($codigoOrdem == '')
	{
		$result = $conexaoPrincipal -> Query("select cd_aparelho from tb_aparelho where cd_aparelho = '$codigo'");
		$linha = mysqli_fetch_assoc($result);
		$total = mysqli_num_rows($result);
	}
	else
	{
		$result = $conexaoPrincipal -> Query("select tb_aparelho.cd_aparelho from tb_aparelho inner join item_os on tb_aparelho.cd_aparelho = item_os.cd_aparelho where tb_aparelho.cd_aparelho = '$codigo' and item_os.cd_os = '$codigoOrdem'");
		$linha = mysqli_fetch_assoc($result);
		$total = mysqli_num_rows($result);
		
		if ($total == 0)
		{
			$result = $conexaoPrincipal -> Query("select cd_aparelho from tb_aparelho where cd_aparelho = '$codigo'");
			$linha = mysqli_fetch_assoc($result);
			$total = mysqli_num_rows($result);
		}
	}
	
	if (!$result)
	{
		$aviso = mysqli_error($conexaoPrincipal -> getConexao());
	}
	
	if ($total == 0)
	{	
		$result = $conexaoPrincipal -> Query("insert into tb_aparelho(cd_imei, cd_imei_secundario, cd_numero_serie, ds_aparelho, cd_modelo, cd_cliente, dt_cadastro) values(".(($IMEI != '') ? "'$IMEI'" : 'null').", ".(($IMEISecundario != '') ? "'$IMEISecundario'" : 'null').", ".(($numeroSerie != '') ? "'$numeroSerie'" : 'null').", '$descricao', '$modelo', '$codigoCliente', now())");
		
		if ($result)
		{
			$result = $conexaoPrincipal -> Query("select cd_aparelho from tb_aparelho where cd_imei ".(($IMEI != '') ? "= '$IMEI'" : 'is null')." and cd_imei_secundario ".(($IMEISecundario != '') ? "= '$IMEISecundario'" : 'is null')." and cd_numero_serie ".(($numeroSerie != '') ? "= '$numeroSerie'" : 'is null')."");
			$linha = mysqli_fetch_assoc($result);
			$total = mysqli_num_rows($result);
			
			$codigoAparelho = $linha['cd_aparelho'];
			
			if ($codigoOrdem == '')
			{
				if (!isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
				{
					setcookie("AZLI[OrdemRecente][codigoAparelho]", $codigoAparelho, time() + (86400 * 1), "/");
					setcookie("AZLI[OrdemRecente][problemasAparelho]", $notas, time() + (86400 * 1), "/");
				}
				else
				{
					$aux = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
					$aux = explode(';-;', $aux);
					
					if (!in_array($codigoAparelho, $aux))
					{
						setcookie("AZLI[OrdemRecente][codigoAparelho]", $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'].';-;'.$codigoAparelho, time() + (86400 * 1), "/");
						setcookie("AZLI[OrdemRecente][problemasAparelho]", $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho'].';-;'.$notas, time() + (86400 * 1), "/");
					}
				}
				
				$aviso = '1;-;Aparelho adicionado com sucesso!';
			}
			else
			{
				$result = $conexaoPrincipal -> Query("insert into item_os(cd_os, cd_aparelho, ds_item_os) values('$codigoOrdem', '$codigoAparelho', '$notas')");
				
				if ($result)
				{
					$aviso = '1;-;Aparelho adicionado com sucesso!';
				}
				else
				{
					$aviso = '0;-;Aparelho já adicionado nessa ordem de serviço.';
					//$aviso = mysqli_error($conexaoPrincipal -> getConexao());
				}
			}
		}
		else
		{
			$aviso = '0;-;Não foi possível adicionar o aparelho! Já existe um aparelho que contém o mesmo IMEI digitado, utilize outro e tente novamente. IMEI: '.$IMEI.' Erro: '.mysqli_error($conexaoPrincipal -> getConexao());
			//$aviso = mysqli_error($conexaoPrincipal -> getConexao());
		}
	}
	else
	{
		if (isset($_POST['editar']) && mysql_escape_string($_POST['editar']) == 1)
		{
			$result = $conexaoPrincipal -> Query("update tb_aparelho set ds_aparelho = '$descricao', cd_modelo = '$modelo', cd_cliente = '$codigoCliente', cd_imei = ".(($IMEI != '') ? "'$IMEI'" : 'null').", cd_imei_secundario = ".(($IMEISecundario != '') ? "'$IMEISecundario'" : 'null').", cd_numero_serie = ".(($numeroSerie != '') ? "'$numeroSerie'" : 'null')." where cd_aparelho = '$codigoAparelho'");
			
			if ($result)
			{
				if ($codigoOrdem == '')
				{
					if (!isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
					{
						setcookie("AZLI[OrdemRecente][codigoAparelho]", $codigoAparelho, time() + (86400 * 1), "/");
						setcookie("AZLI[OrdemRecente][problemasAparelho]", $notas, time() + (86400 * 1), "/");
					}
					else
					{
						$aux = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
						$aux = explode(';-;', $aux);
						
						if (!in_array($codigoAparelho, $aux))
						{
							setcookie("AZLI[OrdemRecente][codigoAparelho]", $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'].';-;'.$codigoAparelho, time() + (86400 * 1), "/");
							setcookie("AZLI[OrdemRecente][problemasAparelho]", $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho'].';-;'.$notas, time() + (86400 * 1), "/");
						}
						else
						{
							/*$aux = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
							$aux = explode(';-;', $aux);
							$auxnotas = explode(';-;', $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho']);
							$auxnotas[array_search($codigoAparelho, $aux)] = $notas;
							
							$aux = '';
							
							for ($cont = 0; $cont <= count($auxnotas) - 1; $cont = $cont + 1)
							{
								if ($cont == 0)
								{
									$aux = $aux.$auxnotas[$cont];
								}
								else
								{
									$aux = $aux.';-;'.$auxnotas[$cont];
								}
							}
							
							setcookie("AZLI[OrdemRecente][problemasAparelho]", $aux, time() + (86400 * 1), "/");*/
						}
					}
				}
				else
				{
					$result = $conexaoPrincipal -> Query("update item_os set ds_item_os = '$notas' where cd_os = '$codigoOrdem' and cd_aparelho = '$codigoAparelho'");
				}
				
				$aviso = '1;-;Aparelho alterado e adicionado com sucesso!';
			}
			else
			{
				$aviso = '0;-;Ocorreu um erro durante o processo: Já existe um aparelho com o IMEI digitado.';
				//mysqli_error($conexaoPrincipal -> getConexao());
			}
		}
		else
		{
			if ($codigoOrdem == '')
			{
				if (!isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
				{
					setcookie("AZLI[OrdemRecente][codigoAparelho]", $codigoAparelho, time() + (86400 * 1), "/");
					setcookie("AZLI[OrdemRecente][problemasAparelho]", $notas, time() + (86400 * 1), "/");
				}
				else
				{
					$aux = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
					$aux = explode(';-;', $aux);
					
					if (!in_array($codigoAparelho, $aux))
					{
						setcookie("AZLI[OrdemRecente][codigoAparelho]", $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'].';-;'.$codigoAparelho, time() + (86400 * 1), "/");
						setcookie("AZLI[OrdemRecente][problemasAparelho]", $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho'].';-;'.$notas, time() + (86400 * 1), "/");
					}
				}
				
				$aviso = '1;-;Aparelho alterado e adicionado com sucesso!';
			}
			else
			{
				$result = $conexaoPrincipal -> Query("insert into item_os(cd_os, cd_aparelho, ds_item_os) values('$codigoOrdem', '$codigoAparelho', '$notas')");
				
				if (!$result)
				{
					$result = $conexaoPrincipal -> Query("update item_os set ds_item_os = '$notas' where cd_os = '$codigoOrdem' and cd_aparelho = '$codigoAparelho'");
				}
				
				$aviso = '1;-;Aparelho alterado e adicionado com sucesso!';
			}
		}
	}
	
	if ($codigoAparelho != '')
	{
		if (isset($_COOKIE['AZLI']['OrdemRecente']['codigosServico']))
		{
			$codigosServico = $_COOKIE['AZLI']['OrdemRecente']['codigosServico'];
		}
		else
		{
			$codigosServico = '';
		}
		
		$servicos = $_POST['servicos'];
		
		$query = "delete from tb_servico where cd_os ".(($codigoOrdem != '') ? "= '$codigoOrdem'" : 'is null')." and cd_aparelho = '$codigoAparelho'";// and (";
		
		/*$cont = 0;
		
		foreach ($servicos as $servico)
		{
			$codigoServico = $servico['cd_servico'];
			
			if ($cont == 0)
			{
				$query = $query."cd_servico != '$codigoServico'";
			}
			else
			{
				$query = $query.", cd_servico != '$codigoServico'";
			}
			
			$cont = $cont + 1;
		}
		
		$query = $query.')';*/
		$result = $conexaoPrincipal -> Query($query);
		
		$cont = 0;
		
		$servicos = $_POST['servicos'];
		
		foreach ($servicos as $servico)
		{
			$codigoServico = $servico['cd_servico'];
			
			$result = $conexaoPrincipal -> Query("select cd_servico, (select cd_servico from tb_servico order by cd_servico desc limit 1) as ultimoServico from tb_servico where cd_servico = '$codigoServico'");
			$linha = mysqli_fetch_assoc($result);
			$total = mysqli_num_rows($result);
			
			if ($total > 0)
			{
				$codigoServico = $codigoServico + (($linha['ultimoServico'] > 0) ? $linha['ultimoServico'] : 0);
			}
			
			$descricaoServico = $servico['ds_servico'];
			$fornecedorServico = $servico['cd_fornecedor'];
			$custoServico = $servico['vl_custo'];
			$precoServico = $servico['vl_preco'];
			
			$result = $conexaoPrincipal -> Query("insert into tb_servico(cd_servico, ds_servico, cd_fornecedor, vl_custo, vl_preco, cd_aparelho, cd_os, dt_solicitacao) values('$codigoServico', '$descricaoServico', ".(($fornecedorServico != '') ? "'$fornecedorServico'" : 'null').", '$custoServico', '$precoServico', '$codigoAparelho', ".(($codigoOrdem != '') ? "'$codigoOrdem'" : 'null').", now())");
			
			if ($result)
			{
				
			}
			else
			{
				$a = mysqli_error($conexaoPrincipal -> getConexao());
				
				if (strpos($aviso, 'Duplicate') === false)
				{
					//$aviso = $a;
				}
			}
			
			if ($codigoOrdem == '')
			{
				if ($codigosServico == '')
				{
					$codigosServico = $codigoServico;
				}
				else
				{
					$codigosServico = $codigosServico.';-;'.$codigoServico;
				}
			}
		}
		if ($codigoOrdem == '')
		{
			setcookie("AZLI[OrdemRecente][codigosServico]", $codigosServico, time() + (86400 * 1), "/");
		}
	}
	
	echo $aviso;
	
	$conexaoPrincipal -> FecharConexao();
?>