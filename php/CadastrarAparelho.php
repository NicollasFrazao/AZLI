<?php
	if (!isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']) || !isset($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem']))
	{
		exit;
	}
	
	$proximo = $_POST['etapaOrdem'] + 1;
	
	if ($_POST['etapaOrdem'] == $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'])
	{
		//$_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] = $_SESSION['AZLI']['OrdemRecente']['etapaOrdem'] + 1;
		setcookie("AZLI[OrdemRecente][etapaOrdem]", $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] + 1, time() + (86400 * 1), "/"); // 86400 = 1 day
	}
			
	echo '1;-;Cadastrado do aparelho realizado com sucesso!;-;'.$proximo;
?>