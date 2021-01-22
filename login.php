<?php
	session_start();
	
	if (isset($_SESSION['AZLI']['codigoUsuario']))
	{
		header('Location: index.php');
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>AZ Litoral Informática</title>
		<style>
			*
			{
				padding: 0px;
				margin: 0px;
				border: 0px;
				font-family: century gothic;
			}
			
			html, body
			{
				display: inline-block;
				width: 100%;
				background-color: #eee;
			}
			
			.azli_login
			{
				display: inline-block;
				width: 36%;
				height: auto;
				background-color: gray;
				margin-left: 30%;
				top:20%;
				position: absolute;
				padding:2%;
			}
			
			.azli_data
			{
				display: inline-block;
				width: 84%;
				margin: 4%;
				padding: 4%;
			}
			
			.azli_btn
			{
				display: inline-block;
				width: 92%;
				margin: 4%;
				padding: 4%;
			}
			
			.azli_btnfg
			{
				display: inline-block;
				width: 92%;
				margin: 4%;
				padding: 4%;
				background-color: transparent;
			}
			
			.azli_title
			{
				font-size: 1.3em;
				color: white;
			}
		</style>
	</head>
	<script src="js/ajax.js"></script>
	<script src="js/AnyTech - Validação.js"></script>
	<body>
		<div class="azli_login" align="center">
			<form id="Frm_Login" method="POST" action="php/Login.php" onkeypress="if (event.keyCode == 13) {btn_acessar.click();}">
				<label class="azli_title">AZ Litoral Informática</label>
				<input type="text" placeholder="Usuário" name="usuario" class="azli_data at-valida" tipo="Nickname" required/>
				<input type="password" placeholder="Senha" name="senha" class="azli_data at-valida" tipo="Senha" required/>
				<input type="submit" id="btn_acessar" value="Acessar" class="azli_btn">
				<input type="button" value="Não consigo acessar minha conta" class="azli_btnfg">
			</form>
			<script>
				Frm_Login.onsubmit = function()
				{
					if (VerificarForm(this))
					{
						AjaxForm(this, "btn_acessar.disabled = true;", "btn_acessar.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); if (indicador == 1) {window.location.href = 'index.php';} else {}");
					}
					else
					{
						alert('Alguns campos não estão corretos!');
					}
					
					return false;
				}
			</script>
		</div>
	</body>
	<script>
		window.onload = function()
		{
			MapearForms();
		}
	</script>
</html>	