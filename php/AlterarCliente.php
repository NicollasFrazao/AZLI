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
	
	if ($total != 0)
	{
		$result = $conexaoPrincipal -> Query("select cd_cliente from tb_cliente where (cd_rg = '$RG' or cd_cpf = '$CPF') and cd_cliente != '$codigoCliente'");
		$linha = mysqli_fetch_assoc($result);
		$total = mysqli_num_rows($result);
		
		if ($total == 0)
		{
			echo '1{}Alterado com sucesso!{}'.$codigoCliente.';-;'.$nome.';-;'.$RG.';-;'.$CPF.';-;'.$telefone1.';-;'.$telefone2.';-;'.$endereco.';-;'.$referencias;
		}
		else
		{
			echo '0;-;Não foi possível alterar o cadastro do cliente! Já existe um usuário que contém o mesmo RG ou CPF digitado, utilize outro e tente novamente.';
			//echo mysqli_error($conexaoPrincipal -> getConexao());
		}
	}
	else
	{
		echo '0;-;Não foi possível alterar o cadastro do cliente! Cliente não encontrado.';
	}
	
	$conexaoPrincipal -> FecharConexao();
?>	