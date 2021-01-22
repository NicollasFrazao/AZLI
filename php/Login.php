<?php
	if (!isset($_POST['usuario']) || !isset($_POST['senha']))
	{
		exit;
	}
	
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	$usuario = mysql_escape_string($_POST['usuario']);
	$senha = mysql_escape_string($_POST['senha']);
	$senhaCriptografada = base64_encode($senha);
	
	$query = "select cd_usuario from tb_usuario where nm_usuario = '$usuario' and (cd_senha = '$senha' or cd_senha = '$senhaCriptografada')";
	$result = $conexaoPrincipal -> Query($query);
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	if ($total > 0)
	{
		session_start();
		$codigoUsuario = $linha['cd_usuario'];
		$_SESSION['AZLI']['codigoUsuario'] = $codigoUsuario;
		
		$resut = $conexaoPrincipal -> Query("update tb_usuario set cd_senha = '$senhaCriptografada' where cd_usuario = '$codigoUsuario'");
		
		echo '1;-;Usuário logado com sucesso!';
	}
	else
	{
		echo '0;-;Usuário e/ou senha estão incorretos!';
	}
?>