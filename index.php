<?php
	session_start();
	
	if (!isset($_SESSION['AZLI']['codigoUsuario']))
	{
		header('location: login.php');
	}
	
	include 'php/Conexao.php';
	include 'php/ConexaoPrincipal.php';
	
	$result_Categoria = $conexaoPrincipal -> Query("select cd_categoria, nm_categoria from tb_categoria order by nm_categoria");
	$linha_Categoria = mysqli_fetch_assoc($result_Categoria);
	$total_Categoria = mysqli_num_rows($result_Categoria);
	
	if (!isset($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem']))
	{
		setcookie("AZLI[OrdemRecente][etapaOrdem]", 1, time() + (86400 * 1), "/"); // 86400 = 1 day
		header('Location: index.php');
	}
	
	$query_Funcionarios = "select cd_funcionario, nm_funcionario from tb_funcionario order by nm_funcionario";
	$result_Funcionarios = $conexaoPrincipal -> Query($query_Funcionarios);
	$linha_Funcionarios = mysqli_fetch_assoc($result_Funcionarios);
	$total_Funcionarios = mysqli_num_rows($result_Funcionarios);
	
	$query_Fornecedores = "select cd_fornecedor, nm_fornecedor from tb_fornecedor order by nm_fornecedor";
	$result_Fornecedores = $conexaoPrincipal -> Query($query_Fornecedores);
	$linha_Fornecedores = mysqli_fetch_assoc($result_Fornecedores);
	$total_Fornecedores = mysqli_num_rows($result_Fornecedores);
	
	$result_Marca = $conexaoPrincipal -> Query("select cd_marca, nm_marca from tb_marca order by nm_marca");
	$linha_Marca = mysqli_fetch_assoc($result_Marca);
	$total_Marca = mysqli_num_rows($result_Marca);
	
	$query_Status = "select cd_status, nm_status from tb_status";
	$result_Status = $conexaoPrincipal -> Query($query_Status);
	$linha_Status = mysqli_fetch_assoc($result_Status);
	$total_Status = mysqli_num_rows($result_Status);
	
	/*$aux = $_COOKIE['AZLI']['OrdemRecente']['codigosServico'];
	$aux = explode(';-;', $aux);
	print_r($aux);*/
	
	$etapaOrdem = $_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'];
	
	if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoCliente']))
	{
		$codigoClienteOrdem = $_COOKIE['AZLI']['OrdemRecente']['codigoCliente'];
	}
	else
	{
		$codigoClienteOrdem = '';
	}
	
	if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
	{
		$codigoAparelhoOrdem = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
		
		$aparelhosOrdem = $codigoAparelhoOrdem;
		$aparelhosOrdem = explode(';-;', $aparelhosOrdem);
		
		$query_AparelhosOrdem = "select tb_aparelho.cd_aparelho,
									   tb_modelo.nm_modelo,
									   tb_aparelho.cd_imei,
									   tb_aparelho.cd_imei_secundario,
									   tb_aparelho.cd_numero_serie
								  from tb_modelo inner join tb_aparelho
									on tb_modelo.cd_modelo = tb_aparelho.cd_modelo
									  where ";
		
		for ($cont = 0; $cont <= count($aparelhosOrdem) - 1; $cont = $cont + 1)
		{
			if ($cont == 0)
			{
				$aux = $aparelhosOrdem[$cont];
				
				$query_AparelhosOrdem = $query_AparelhosOrdem."tb_aparelho.cd_aparelho = '$aux'";
			}
			else
			{
				$aux = $aparelhosOrdem[$cont];
				
				$query_AparelhosOrdem = $query_AparelhosOrdem." or tb_aparelho.cd_aparelho = '$aux'";
			}
		}
		
		$result_AparelhosOrdem = $conexaoPrincipal -> Query($query_AparelhosOrdem);
		$linha_AparelhosOrdem = mysqli_fetch_assoc($result_AparelhosOrdem);
		$total_AparelhosOrdem = mysqli_num_rows($result_AparelhosOrdem);
	}
	else
	{
		$codigoAparelhoOrdem = '';
		$total_AparelhosOrdem = 0;
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
				overflow: hidden;
				outline: none;
			}
			
			html, body
			{
				display: inline-block;
				width: 100%;
				height: 100%;
				background-color: #232a2b;
			}
			
			::-webkit-scrollbar
			{
				height: 5px;
				width: 6px;
				background: rgb(242,242,242);
				transition: visible 1s;
			}

			::-webkit-scrollbar-thumb
			{
				background: #04aabc;
			}

			::-webkit-scrollbar-corner
			{
				background: #333;
			}
			
			.all
			{
				display: inline-block;
				width: 100%;
				height: 100%;
				background-color: transparent;
			}
			
			.azli-top
			{
				display: inline-block;
				width: 100%;
				height: 10%;
				background-color: transparent;
			}
			
			.azli-medium
			{
				display: inline-block;
				width: 100%;
				height: 73%;
				transition: height 0.5s;
				background-color: transparent;
			}
			
			
			.azli-bottom
			{
				display: inline-block;
				width: 100%;
				height: 26%;
				bottom: 0px;
				position: absolute;
				background-color: #232a2b;
			}
			
			.azli-option
			{
				display: inline-block;
				width: 80px;
				height: 80%;
				margin: 10px;
			}
			
			.azli-option-image
			{
				display: inline-block;
				height: 50px;
				background-color: #04aabc;
				transition: height 0.5s;
				border-radius: 50%;
			}
			
			.azli-option-image:hover
			{
				height: 60px;
				background-color: #03d5ec;
			}
			
			.azli-option-label
			{
				display: inline-block;
				color: #eee;
				font-size: 0.75em;
			}
			
			.azli-window
			{
				display: inline-block;
				width: 95%;
				height: 90%;
				background-color: transparent;
			}
			
			.azli-title
			{
				display: inline-block;
				color: #eee;
				height: 100%;
				font-size: 1em;
				margin-top: 20px;
			}
			
			.azli-display
			{
				display: inline-block;
				background-color: transparent;
				color: #aaa;
				width: 100%;
				position: absolute;
				bottom:5px;
			}
			
			.azli-display:hover
			{
				color: white;
			}
			
			.azli-win-recentes
			{
				display: inline-block;
				width: 96%;
				height: 100%;
				background-color: transparent;
				overflow-y: auto;		
				margin:2%;
			}
			
			.azli-title-box
			{
				display: inline-block;
				background-color: rgba(255,255,255,0.3);
				width: 100%;
				height:2%;
				color: white;
				padding: 2%;
				font-size: 0.9em;
				font-weight: bold;
				text-align: center;
				
			}
			
			.azli-list-option
			{
				display: inline-block;
				width: 100%;
				color: white;
				padding: 2%;
				font-size: 0.9em;
				font-weight: normal;
				text-align: left;
				margin-bottom: 1px;
				margin-top: 1px;
				background-color: rgba(255,255,255,0.1);
			}
			
			.azli-list-option:hover
			{
				background-color: #04aabc;
			}
			
			.azli-list
			{
				display: inline-block;
				width: 100%;				
				
			}
			
			
			.azli-option-image-sub
			{
				width: 100px;
				padding: 2%;
				border-radius: 50%;
			}
			
			.azli-into-sub:hover
			{
				background-color: rgba(255,255,255,0.2);
			}
			
			.azli-into-sub
			{
				display: inline-block;
				width: 100%;
				height: 50%;
				background-color: rgba(255,255,255,0.1);
			}
			
			.azli-label
			{
				display: inline-block;
				width: 100%;
				color: white;
				margin-top: 20px;
			}
			
			.azli-input
			{
				display: inline-block;
				width: 100%;
				padding:10px;
			}
			
			.azli-select
			{
				display: inline-block;
				width: 100%;
				padding:9px;
			}
			
			.azli-window-form
			{
				display: none;
				width: 95%;
				padding: 2.5%;
				height: 80%;
				background-color: rgba(255,255,255,0.1);
				overflow: auto;
				transition: margin 1s;
			}
			
			.azli-button
			{
				display: inline-block;
				background-color: #04aabc;
				padding: 10px;
				color: white;
				margin-top: 10px;
				margin-bottom: 30px;
			}
			
			.azli-button:hover, .azli-button:focus
			{
				background-color: #03d5ec;
			}
				
			.cancel-btn
			{
				background-color: rgba(255,255,255,0.1);
				margin-bottom: 30px;
			}
			
			.cancel-btn:hover, .cancel-btn:focus
			{
				background-color: rgba(255,255,255,0.3);
			}
			
			##dados_aparelho_dp, #dados_cliente_dp, #dados_os_list, #data_lista_os_ap, #dados_lista_cliente, #azli_win_os, #dados_aparelho, #dados_cliente, #dados_servico, #dados_funcionario, #dados_categoria, #dados_marca, #dados_modelo, #dados_lista_aparelho
			{
				display: none;
			}
			
			.func-at
			{
				background-color: rgba(255,255,255,0.1);
				color: white;
				margin-top: 5px;
			}
			
			.func-at:hover
			{
				background-color: rgba(255,255,255,0.4);
			}
			
			.func-del
			{
				display: inline-block;
				width: 100%;
				height: 90%;
				margin-top:5px;
				background-color: rgba(255,255,255,0.3);
				color: white;
				padding-left: 7px;
				padding-right: 7px;
			}
			
			.func-del:hover, .func-del:focus
			{
				background-color: rgba(255,255,255,0.4);
			}
			
			.list
			{
				text-align: left;
			}
			
			#azli_win_os
			{
				display: inline-block;
			}
			
			
			.dp
			{
				background-color: rgba(255,255,255,0.1);
				color: white;
			}
			
			.fontm
			{
				font-size: 0.7em;
			}
		</style>
		
	</head>
	<script src="js/AnyTech - Validação.js"></script>
	<body onkeyup="if (event.keyCode == 27) {displayMenu(azli_display_button.value);}">
		<div class="all">
			<div class="azli-top" align="center">
				<label class="azli-title">AZ Litoral Informática</label>
			</div>
			<div class="azli-medium" align="center">
				<div class="azli-window-form" id="azli_win_os">
					<table width="100%" height="100%">
						<tr valign="center" >
							<td width="33%%" height="100%" id="azli_win_sub" align="center">
								<div class="azli-into-sub" onclick="LimparCadastroCliente(); BuscarCliente(codigoCliente, ''); BuscarAparelhosOrdem(codigoOrdem); BuscarDatasOrdem(codigoOrdem); EtapaOrdemServico(etapaOrdem);">
									<image src="images/new.png" class="azli-option-image-sub"><br>
									<label class="azli-option-label" id="lbl_novaOrdem">Nova Ordem de Serviço</label>	
								</div>
								
								<div class="azli-into-sub" onclick="enterWin('#dados_os_list');">
									<image src="images/pesquisar-ordem.png" class="azli-option-image-sub"><br>
									<label class="azli-option-label">Pesquisar Ordem de Serviço</label>								
								</div>
							</td>
							
							<td width="66%">						
								
								<div class="azli-win-recentes">
									<div class="azli-list">
										<label class="azli-title-box">Ordens de Serviço Recentes</label>
										<?php
											$result_Ordem = $conexaoPrincipal -> Query("
												select tb_os.cd_verificador,
													   tb_os.dt_cadastro,
													   tb_status.cd_status,
													   tb_status.nm_status
												  from tb_os inner join tb_status
													on tb_os.cd_status = tb_status.cd_status
													  order by dt_cadastro desc");
											$linha_Ordem = mysqli_fetch_assoc($result_Ordem);
											$total_Ordem = mysqli_num_rows($result_Ordem);
											
											if ($total_Ordem > 0)
											{
												do
												{
										?>
													<input type="button" class="azli-list-option" value="Nº <?php echo $linha_Ordem['cd_verificador'].' - '.date('d/m/Y H:i', strtotime($linha_Ordem['dt_cadastro'])).' - '.$linha_Ordem['nm_status']; ?>" onclick="cmb_statusOrdem.value = '<?php echo $linha_Ordem['cd_status']; ?>'; txt_codigoOrdem.value = '<?php echo $linha_Ordem['cd_verificador']; ?>'; btn_pesquisarOrdem.click(); enterWin('#dados_os_list');"/>
										<?php
												}
												while ($linha_Ordem = mysqli_fetch_assoc($result_Ordem));
											}
										?>
									</div>
								</div>
							</td>
						</tr>
					</table>	
				</div>
				
				<div class="azli-window-form" id="dados_cliente">
					<?php
						if ($_COOKIE['AZLI']['OrdemRecente']['etapaOrdem'] > 1 && isset($_COOKIE['AZLI']['OrdemRecente']['codigoCliente']))
						{
							$codigoCliente = $_COOKIE['AZLI']['OrdemRecente']['codigoCliente'];
							
							$result_Cliente = $conexaoPrincipal -> Query("select cd_cliente, nm_cliente, cd_rg, cd_cpf, cd_telefone1, cd_telefone2, nm_endereco, ds_referencias from tb_cliente where cd_cliente = '$codigoCliente'");
							$linha_Cliente = mysqli_fetch_assoc($result_Cliente);
						}
					?>
					<form id="Frm_CadastroCliente" method="POST" action="php/CadastrarCliente.php">
						<table width="80%">
							<tr>
								<td colspan="2">
									<label class="azli-label">Nome do cliente - Obrigatório</label>
									<input type="text" id="txt_codigoClienteCadastro" name="codigo" value="" style="display: none;"/>
									<input type="text" id="txt_nomeClienteCadastro" class="azli-input at-valida" tipo="NomeCompleto" name="nome" placeholder="Insira o nome do cliente" value="" required tabindex="1"/>
								</td>
							</tr>
							
							<tr>
								<td width="50%">
									<label class="azli-label">RG e CPF - Obrigatório o preenchimento de um deles</label>
								</td>
							</tr>
							
							<tr>
								<td width="50%">
									<label class="azli-label">RG</label>
									<input type="text" id="txt_RGClienteCadastro" class="azli-input at-valida" tipo="RG" placeholder="Exemplo: 91.122.534-1" name="RG" value="" tabindex="2"/>
								</td>
								<td width="50%">
									<label class="azli-label">CPF</label>
									<input type="text" id="txt_CPFClienteCasdastro" class="azli-input at-valida" tipo="CPF" placeholder="Exemplo: 233.547.595-93" name="CPF" value="" tabindex="3"/>
								</td>
							</tr>
							<tr>
								<td align="left">
									<label class="azli-label">Telefones - Obrigatório o preenchimento de um deles</label>
								</td>
								
								<td align="right">
									<input type="button" class="azli-button cancel-btn" value="Buscar cliente" id="btn_buscarCliente" style="margin-bottom: 0px;"  tabindex="4"/>
								</td>
							</tr>
							
							<tr>
								<td>
									<label class="azli-label">Telefone 1</label>
									<input type="text" id="txt_telefone1ClienteCadastro" class="azli-input at-valida" tipo="Telefone" placeholder="Exemplo: (13) 3232-2323" name="telefone1" value=""  tabindex="5"/>
								</td>
								
								<td>
									<label class="azli-label">Telefone 2</label>
									<input type="text" id="txt_telefone2ClienteCadastro" class="azli-input at-valida" tipo="Celular" placeholder="Exemplo: (13) 98877-6655" name="telefone2" value=""  tabindex="6"/>
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<label class="azli-label">Endereço</label>
									<input type="text" id="txt_enderecoClienteCadastro" class="azli-input at-valida" tipo="Endereço" placeholder="Exemplo: Rua Jardel Franca, nº 355, Esplanada dos Barreiros, São Vicente - SP" name="endereco" value=""  tabindex="7"/>
								</td>							
							</tr>
							
							<tr>
								<td colspan="2">
									<label class="azli-label">Referências</label>
									<textarea id="txt_referenciasClienteCadastro" class="azli-input at-valida" tipo="TextoNaoObrigatorio" rows="3"  placeholder="Insira complementos do endereço, pontos de referência e anotações sobre o cliente" name="referencias" maxLength="500" tabindex="8"/></textarea>
								</td>							
							</tr>
							
							<tr>
								<td align="left">
									<label id="lbl_avisoCliente" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
								</td>
								
								<td align="right">
									<input type="button" class="azli-button cancel-btn" value="Novo cliente" id="btn_novoCliente" onclick="Frm_CadastroCliente.reset();" tabindex="9"/>
									<input type="button" id="btn_cancelarOrdemCliente" class="azli-button cancel-btn" value="Cancelar" tabindex="10"/>
									<input type="submit" class="azli-button" id="btn_cadastrarCliente" value="Avançar" onclick="//enterWin('#dados_aparelho')" tabindex="11">
								</td>
							</tr>
						</table>
						<input type="text" id="ic_clienteCadastrado" name="clienteCadastrado" value="0" style="display: none;"/>
						<input type="text" id="ic_editar" name="editar" value="0" style="display: none;"/>
						<input type="text" name="etapaOrdem" value="1" style="display: none;"/>
						<input type="hidden" id="txt_codigoOrdemCliente" name="codigoOrdem" value=""/>
					</form>
					<script>
						btn_cancelarOrdemCliente.onclick = function()
						{
							if (codigoOrdem == '')
							{
								if (confirm("Tem certeza que deseja cancelar a ordem de serviço?"))
								{
									btn_cancelarOrdem.disabled = true;
									Ajax("GET", "php/CancelarOrdem.php", "", "btn_cancelarOrdem.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); if (indicador == 1) {CancelarOrdem();}");
								}
							}
							else
							{
								LimparCadastroCliente();
								enterWin('#dados_os_list');
							}
						}
						
						function LimparCadastroCliente()
						{
							btn_buscarCliente.style.display = 'inline-block';
							btn_cadastrarCliente.value = 'Avançar';
							codigoOrdem = '';
							Frm_CadastroCliente.reset();
							VerificarForm(Frm_CadastroCliente);
						}
					
						Frm_CadastroCliente.onsubmit = function()
						{
							lbl_avisoCliente.style.display = 'none';
							
							var campos = this.getElementsByClassName('at-valida');
								
							for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
							{
								campos[cont].style.border = '0px';
							}
							
							if (txt_CPFClienteCasdastro.getAttribute('correto') == 0 && txt_RGClienteCadastro.getAttribute('correto') == 0)
							{
								lbl_avisoCliente.style.display = 'inline-block'; lbl_avisoCliente.textContent = "Não deixe os campos RG ou CPF sem estar preenchidos.";
							}
							else if (VerificarForm(this))
							{
								if (ic_clienteCadastrado.value == 1)
								{
									//txt_RGClienteCadastro.value = RGOriginal;
									//txt_CPFClienteCasdastro.value = CPFOriginal;
									
									VerificarForm(this);
									
									if (confirm("Deseja alterar os dados deste cliente já cadastrado?"))
									{
										ic_editar.value = 1;
									}
									else
									{
										ic_editar.value = 0;
									}
								}				
								
								AjaxForm(this, "btn_cadastrarCliente.disabled = true;", "btn_cadastrarCliente.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); if (indicador == 1) {if (codigoOrdem == '') {etapaOrdem = retorno.split(';-;')[2].trim(); EtapaOrdemServico(etapaOrdem); VerificarProcessoOrdem(etapaOrdem); codigoCliente = retorno.split(';-;')[3].trim();} else {btn_cancelarOrdemCliente.click(); btn_pesquisarOrdem.click();}} else {var aviso = retorno.split(';-;')[1].trim(); lbl_avisoCliente.style.display = 'inline-block'; lbl_avisoCliente.textContent = aviso; lbl_avisoCliente.focus();}");
							}
							else
							{
								var campos = this.getElementsByClassName('at-valida');
								
								for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
								{
									if (campos[cont].getAttribute('correto') == 0)
									{
										campos[cont].style.border = '1px solid red';
									}
								}
								
								lbl_avisoCliente.style.display = 'inline-block';
								lbl_avisoCliente.textContent = "Alguns campos não foram preenchidos corretamente! Verifique-os e tente novamente."
								lbl_avisoCliente.focus();
							}
							
							return false;
						}
						
						function ExibirBuscaCliente(retorno)
						{
							if (retorno.trim() != "")
							{
								aux = retorno.split(';-;');
								
								if (aux[0].trim() != '')
								{
									Frm_CadastroCliente.reset();
									
									ic_clienteCadastrado.value = 1;
									
									txt_codigoClienteCadastro.value = aux[0].trim();
									txt_nomeClienteCadastro.value = aux[1].trim();
									//RGOriginal = aux[2].trim();
									txt_RGClienteCadastro.value = aux[2].trim();
									//CPFOriginal = aux[3].trim();
									txt_CPFClienteCasdastro.value = aux[3].trim();
									txt_telefone1ClienteCadastro.value = aux[4].trim();
									txt_telefone2ClienteCadastro.value = aux[5].trim();
									txt_enderecoClienteCadastro.value = aux[6].trim();
									txt_referenciasClienteCadastro.value = aux[7].trim();
									txt_codigoOrdemCliente.value = aux[8].trim();
								}
								
								VerificarForm(Frm_CadastroCliente);
								
								if (codigoOrdem != '')
								{
									txt_nomeClienteCadastro.focus();
									enterWin('#dados_cliente');
								}
							}
							else
							{
								ic_clienteCadastrado.value = 0;
								ic_editar.value = 0;
							}
						}
						
						function BuscarCliente(valorBusca, codigo)
						{
							btn_buscarCliente.disabled = true;
							
							Ajax("GET", "php/BuscarCliente.php", "valorBusca=" + valorBusca + "&codigoOrdem=" + codigo, "btn_buscarCliente.disabled = false; var retorno = this.responseText; ExibirBuscaCliente(retorno);");
							
							txt_codigoOrdemCliente.value = codigo;
						}
						
						btn_buscarCliente.onclick = function()
						{
							btn_buscarCliente.style.display = 'inline-block';
							clientePesquisado = '';
							
							if (codigoOrdem == '')
							{
								if (txt_RGClienteCadastro.getAttribute('correto') == 1)
								{
									BuscarCliente(txt_RGClienteCadastro.value, '');
								}
								else if (txt_CPFClienteCasdastro.getAttribute('correto') == 1)
								{
									BuscarCliente(txt_CPFClienteCasdastro.value, '');
								}
								else
								{
									ic_clienteCadastrado.value = 0;
									ic_editar.value = 0;
								}
							}
							else
							{
								if (txt_RGClienteCadastro.getAttribute('correto') == 1)
								{
									BuscarCliente(txt_RGClienteCadastro.value, codigoOrdem);
								}
								else if (txt_CPFClienteCasdastro.getAttribute('correto') == 1)
								{
									BuscarCliente(txt_CPFClienteCasdastro.value, codigoOrdem);
								}
								else
								{
									Frm_CadastroCliente.reset();
									BuscarCliente('', codigoOrdem);
									
									//btn_buscarCliente.style.display = 'none';
									btn_cadastrarCliente.value = 'Alterar';
								}
							}
							
						}
					</script>
				</div>
				
				<div class="azli-window-form" id="dados_cliente_dp">
					<form id="Frm_AlterarCliente" method="POST" action="php/AlterarCliente.php">
						<table width="80%">
							<tr>
								<td colspan="2">
									<label class="azli-label">Nome do cliente</label>
									<input type="text" id="txt_nomeClienteAlterar" class="azli-input dp at-valida" tipo="NomeCompleto" name="nome" required/>
									<input type="text" id="txt_codigoClienteAlterar" class="azli-input dp" name="codigo" style="display: none;" value="" required/>
								</td>
							</tr>
							
							<tr>
								<td width="50%">
									<label class="azli-label">RG</label>
									<input type="text" id="txt_RGClienteAlterar" class="azli-input dp at-valida" tipo="RG" name="RG"/>
								</td>
								
								<td width="50%">
									<label class="azli-label">CPF</label>
									<input type="text" id="txt_CPFClienteAlterar" class="azli-input dp at-valida" tipo="CPF" name="CPF"/>
								</td>
							</tr>
							
							<tr>
								<td>
									<label class="azli-label">Telefone 1</label>
									<input type="text" id="txt_telefone1ClienteAlterar" class="azli-input dp at-valida" tipo="Telefone" name="telefone1" required/>
								</td>
								
								<td>
									<label class="azli-label">Telefone 2</label>
									<input type="text" id="txt_telefone2ClienteAlterar" class="azli-input dp at-valida" tipo="Celular" name="telefone2" required/>
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<label class="azli-label">Endereço</label>
									<input type="text" id="txt_enderecoClienteAlterar" class="azli-input dp at-valida" tipo="Endereço" name="endereco" required/>
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<label class="azli-label">Referências</label>
									<textarea id="txt_referenciasClienteAlterar" class="azli-input dp at-valida" tipo="TextoNaoObrigatorio" name="referencias" rows="3"></textarea>
								</td>
							</tr>
							
							<tr>
								<td align="left">
									<label id="lbl_avisoClienteAlterar" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
								</td>
								
								<td align="right">
									<input type="button" id="btn_cancelarAlterarCliente" class="azli-button cancel-btn" value="Cancelar" onclick="enterWin('#dados_lista_cliente'); setTimeout('Frm_AlterarCliente.reset()', 1500);"/>
									<input type="submit" id="btn_alterarClientes" class="azli-button" value="Alterar"/>
								</td>
							</tr>
						</table>
					</form>
					
					<script>
						Frm_AlterarCliente.onsubmit = function()
						{
							lbl_avisoClienteAlterar.style.display = 'none';
							VerificarForm(this);
							
							var campos = this.getElementsByClassName('at-valida');
								
							for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
							{
								campos[cont].style.border = '0px';
							}
							
							if (txt_CPFClienteAlterar.getAttribute('correto') == 0 && txt_RGClienteAlterar.getAttribute('correto') == 0)
							{
								lbl_avisoClienteAlterar.style.display = 'inline-block'; lbl_avisoClienteAlterar.textContent = "Não deixe os campos RG e CPF sem estar preenchidos.";
							}
							else if (VerificarForm(this))
							{
								AjaxForm(this, "btn_alterarClientes.disabled = true;", "btn_alterarClientes.disabled = false; var retorno = this.responseText; var indicador = retorno.split('{}')[0].trim(); if (indicador == 1) {btn_cancelarAlterarCliente.click(); AlterarCliente(retorno.split('{}')[2].trim());} else {var aviso = retorno.split('{}')[1].trim(); lbl_avisoClienteAlterar.style.display = 'inline-block'; lbl_avisoClienteAlterar.textContent = aviso;}");
							}
							else
							{
								var campos = this.getElementsByClassName('at-valida');
								
								for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
								{
									if (campos[cont].getAttribute('correto') == 0)
									{
										campos[cont].style.border = '1px solid red';
									}
								}
								
								lbl_avisoClienteAlterar.style.display = 'inline-block';
								lbl_avisoClienteAlterar.textContent = "Alguns campos não foram preenchidos corretamente! Verifique-os e tente novamente."
								lbl_avisoClienteAlterar.focus();
							}
							
							return false;
						}
						
						function BuscarClienteAlterar(valorBusca)
						{
							var achou = 0;
							
							if (alterarClientes != '')
							{
								var aux = alterarClientes.split('{}');
								var cont;
								
								for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
								{
									if (aux[cont].split(';-;')[0].toString().toLowerCase().trim() == valorBusca.toString().toLowerCase().trim())
									{
										var retorno = aux[cont];
										achou = 1;
									}
								}
							}
							
							if (achou == 0)
							{
								btn_buscarCliente.disabled = true;
									
								Ajax("GET", "php/BuscarCliente.php", "valorBusca=" + valorBusca + "&codigoOrdem=" + codigoOrdem, "btn_buscarCliente.disabled = false; var retorno = this.responseText; ExibirBuscaClienteAlterar(retorno);");
							}
							else
							{
								ExibirBuscaClienteAlterar(retorno);
							}
						}
						
						function ExibirBuscaClienteAlterar(retorno)
						{
							if (retorno.trim() != "")
							{
								aux = retorno.split(';-;');
								
								if (aux[0].toString().trim() != '')
								{
									txt_codigoClienteAlterar.value = aux[0].toString().trim();
									txt_nomeClienteAlterar.value = aux[1].toString().trim();
									//RGOriginal = aux[2].trim();
									txt_RGClienteAlterar.value = aux[2].toString().trim();
									//CPFOriginal = aux[3].trim();
									txt_CPFClienteAlterar.value = aux[3].toString().trim();
									txt_telefone1ClienteAlterar.value = aux[4].toString().trim();
									txt_telefone2ClienteAlterar.value = aux[5].toString().trim();
									txt_enderecoClienteAlterar.value = aux[6].toString().trim();
									txt_referenciasClienteAlterar.value = aux[7].toString().trim();
								}
								
								VerificarForm(Frm_AlterarCliente);
								
								enterWin("#dados_cliente_dp");
							}
						}
					</script>
				</div>
				
				<div class="azli-window-form" id="dados_aparelho">
					<form id="Frm_AdicionarAparelho" method="POST" action="php/AdicionarAparelhoOrdem.php">
						<table width="80%">
							<tr>
								<td>
									<label class="azli-label">Categoria - Obrigatório</label>
									<input type="text" id="txt_codigoAparelhoCadastro" name="codigo" value="" style="display: none;"/>
									<select id="cmb_categoria" class="azli-select at-valida" tipo="Combobox" name="categoria" required tabindex="1">
										<option value="">Selecione a categoria do aparelho</option>
										<?php
											if ($total_Categoria > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Categoria['cd_categoria']; ?>"><?php echo $linha_Categoria['nm_categoria']; ?></option>
										<?php
												}
												while ($linha_Categoria = mysqli_fetch_assoc($result_Categoria));
											}
										?>
									<select>
									<script>
										cmb_categoria.onchange = function()
										{
											if (this.value == "")
											{
												lbl_avisoObrigatorioIMEI.textContent = '';
												
												lbl_IMEI.style.display = 'none';
												txt_IMEI.style.display = 'none';
												txt_IMEI.required = false;
												txt_IMEI.tabIndex = -1;
												
												lbl_IMEISecundario.style.display = 'none';
												txt_IMEISecundario.style.display = 'none';
												txt_IMEISecundario.tabIndex = -1;
												
												lbl_numeroSerie.style.display = 'none';
												txt_numeroSerie.style.display = 'none';
												txt_numeroSerie.required = false;
												txt_numeroSerie.tabIndex = -1;
												
												btn_novoAparelho.style.display = 'none';
												btn_buscarAparelho.style.display = 'none';
											}
											else if (this.selectedOptions[0].textContent.indexOf("Smartphone") != -1)
											{
												lbl_avisoObrigatorioIMEI.textContent = 'IMEI\'s - Obrigatório o preenchimento de um deles';
												
												lbl_numeroSerie.style.display = 'none';
												txt_numeroSerie.style.display = 'none';
												txt_numeroSerie.required = false;
												txt_numeroSerie.tabIndex = -1;
												
												lbl_IMEI.style.display = 'inline-block';
												txt_IMEI.style.display = 'inline-block';
												txt_IMEI.required = true;
												txt_IMEI.tabIndex = 6;
												
												lbl_IMEISecundario.style.display = 'inline-block';
												txt_IMEISecundario.style.display = 'inline-block';
												txt_IMEI.tabIndex = 7;
												
												btn_novoAparelho.style.display = 'inline-block';
												btn_buscarAparelho.style.display = 'inline-block';
											}
											else
											{
												lbl_avisoObrigatorioIMEI.textContent = '';
												
												lbl_IMEI.style.display = 'none';
												txt_IMEI.style.display = 'none';
												txt_IMEI.required = false;
												txt_IMEI.tabIndex = -1;
												
												lbl_IMEISecundario.style.display = 'none';
												txt_IMEISecundario.style.display = 'none';
												txt_IMEISecundario.tabIndex = -1;
												
												lbl_numeroSerie.style.display = 'inline-block';
												txt_numeroSerie.style.display = 'inline-block';
												txt_numeroSerie.required = true;
												txt_numeroSerie.tabIndex = 5;
												
												btn_novoAparelho.style.display = 'inline-block';
												btn_buscarAparelho.style.display = 'inline-block';
											}
											
											cmb_marca.innerHTML = '<option value="">Selecione a marca do aparelho</option>';
											
											if (this.value != "")
											{
												Ajax("GET", "php/BuscarMarca.php", "codigoCategoria=" + this.value, "var retorno = this.responseText; cmb_marca.innerHTML = retorno; if (ic_aparelhoCadastrado.value == 1) {cmb_marca.value = marcaAparelhoPesquisado; cmb_marca.onchange();}");
											}
										}
									</script>
								</td>
								<td>
									<label class="azli-label">Marca - Obrigatório</label>
									<select class="azli-select at-valida" tipo="Combobox" id="cmb_marca" name="marca" required tabindex="2">
										<option value="">Selecione a marca do aparelho</option>
									</select>
									<script>
										cmb_marca.onchange = function()
										{
											cmb_modelo.innerHTML = '<option value="">Selecione o modelo do aparelho</option>';
											
											if (this.value != "")
											{
												Ajax("GET", "php/BuscarModelo.php", "codigoMarca=" + this.value + "&codigoCategoria=" + cmb_categoria.value, "var retorno = this.responseText; cmb_modelo.innerHTML = retorno; ModelosOriginais(); if (ic_aparelhoCadastrado.value == 1) {cmb_modelo.value = modeloAparelhoPesquisado;}");
											}
										}
									</script>
								</td>
							</tr>
							
							<tr>
								<td>
									<label class="azli-label">Filtrar modelo</label>
									<input type="text" id="txt_filtrarModelo" class="azli-input" placeholder="Insira o modelo a ser pesquisado" tabindex="3"/>
								</td>
								<td>
									<label class="azli-label">Modelo - Obrigatório</label>
									<select class="azli-select at-valida" tipo="Combobox" id="cmb_modelo" name="modelo" required tabindex="4">
										<option value="">Selecione o modelo do aparelho</option>
									</select>
								</td>
							</tr>
							<script>
								modelosOriginais = [];
								
								function ModelosOriginais()
								{
									var cont, limite;
									
									modelosOriginais.splice(0, modelosOriginais.length);
									
									limite = cmb_modelo.options.length - 1;
									
									for (cont = 0; cont <= limite; cont = cont + 1)
									{
										var aux = document.createElement('option');
											aux.value = cmb_modelo.options[cont].value;
											aux.textContent = cmb_modelo.options[cont].textContent;
										
										modelosOriginais.push(aux);
									}
								}
								
								txt_filtrarModelo.onkeyup = function()
								{
									var cont, limite;
									
									if (this.value == '')
									{
										while (cmb_modelo.options.length != 0)
										{
											cmb_modelo.options[0].remove();
										}
										
										limite = modelosOriginais.length - 1;
										
										for (cont = 0; cont <= limite; cont = cont + 1)
										{
											var aux = document.createElement('option');
												aux.value = modelosOriginais[cont].value;
												aux.textContent = modelosOriginais[cont].textContent;
											
											cmb_modelo.options.add(aux);
										}
									}
									else
									{
										while (cmb_modelo.options.length != 0)
										{
											cmb_modelo.options[0].remove();
										}
										
										limite = modelosOriginais.length - 1;
										
										for (cont = 0; cont <= limite; cont = cont + 1)
										{
											if (modelosOriginais[cont].textContent.toString().toLowerCase().indexOf(this.value.toString().toLowerCase()) != -1)
											{
												var aux = document.createElement('option');
													aux.value = modelosOriginais[cont].value;
													aux.textContent = modelosOriginais[cont].textContent;
												
												cmb_modelo.options.add(aux);
											}
										}
									}
								}
							</script>
							
							<tr>
								<td colspan="2">
									<label class="azli-label" id="lbl_numeroSerie">Número de série - Obrigatório</label>
									<input type="text" id="txt_numeroSerie" class="azli-input at-valida" tipo="numeroSerie" placeholder="Insira o número de série do aparelho" name="numeroSerie"/>
								</td>
							</tr>
							
							<tr>
								<td>
									<label class="azli-label" id="lbl_IMEI">IMEI primário</label>
									<input type="text" id="txt_IMEI" class="azli-input at-valida" tipo="IMEI" placeholder="Insira o IMEI do aparelho" name="IMEI"/>
								</td>
								<td>
									<label class="azli-label" id="lbl_IMEISecundario">IMEI secundário</label>
									<input type="text" id="txt_IMEISecundario" class="azli-input at-valida" tipo="IMEI" placeholder="Insira o IMEI do aparelho" name="IMEISecundario"/>
								</td>
							</tr>
							
							<tr>
								<td width="50%" align="left">
									<label class="azli-label" id="lbl_avisoObrigatorioIMEI"></label>
								</td>
								<td width="50%" align="right">
									<input type="button" class="azli-button cancel-btn" id="btn_novoAparelho" value="Novo aparelho" onclick="Frm_AdicionarAparelho.reset();" style="margin-bottom: 0px;" tabindex="8"/>
									<input type="button" class="azli-button cancel-btn" id="btn_buscarAparelho" value="Buscar aparelho" style="margin-bottom: 0px;" tabindex="9"/>
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<label class="azli-label">Descrição do aparelho</label>
									<textarea id="txt_descricaoAparelho" class="azli-input at-valida" tipo="TextoObrigatorio" rows="3" placeholder="Descreva as características do aparelho" name="descricao" tabindex="10"></textarea>
								</td>							
							</tr>
							
							<tr style="display: none">
								<td colspan="2">
									<label class="azli-label">Notas do aparelho</label>
									<textarea id="txt_notasAparelho" class="azli-input at-valida" tipo="TextoObrigatorio" rows="3" placeholder="Descreva o problema apresentado por este aparelho" name="notas"></textarea>
								</td>							
							</tr>
						</table>
						  
						<table width="80%;">
							<tr>
								<td align="left">
									<label class="azli-label">Descrição do serviço</label>
									<input type="text" id="txt_descricaoServico" class="azli-input" placeholder="Insira a descrição do serviço" tabindex="11"/>
								</td>
								<td align="left">
									<label class="azli-label">Fornecedor</label>
									<select id="cmb_fornecedor" class="azli-select at-valida" tipo="Combobox" tabindex="12">
										<option value="">Não necessitou de fornecedor</option>
										<?php
											$result_Fornecedores = $conexaoPrincipal -> Query($query_Fornecedores);
											$linha_Fornecedores = mysqli_fetch_assoc($result_Fornecedores);
											$total_Fornecedores = mysqli_num_rows($result_Fornecedores);
											
											if ($total_Fornecedores > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Fornecedores['cd_fornecedor']; ?>"><?php echo $linha_Fornecedores['nm_fornecedor']; ?></option>
										<?php
												}
												while ($linha_Fornecedores = mysqli_fetch_assoc($result_Fornecedores));
											}
										?>
									</select>
								</td>
								<td align="left">
									<label class="azli-label">Lucro</label>
									<input type="text" id="txt_lucro" class="azli-input at-valida" tipo="Real" placeholder="R$ 999,99" style="text-transform: uppercase;" readonly tabindex="15"/>
									<script>
										txt_lucro.onfocus = function()
										{
											var custo = txt_custo.value;
											var preco = txt_preco.value;
											
											while (custo.indexOf('R') != -1 || custo.indexOf('$') != -1 || custo.indexOf(' ') != -1 || custo.indexOf(',') != -1)
											{
												custo = custo.replace('R', '');
												custo = custo.replace('$', '');
												custo = custo.replace(' ', '');
												custo = custo.replace(',', '.');
											}
											
											while (preco.indexOf('R') != -1 || preco.indexOf('$') != -1 || preco.indexOf(' ') != -1 || preco.indexOf(',') != -1)
											{
												preco = preco.replace('R', '');
												preco = preco.replace('$', '');
												preco = preco.replace(' ', '');
												preco = preco.replace(',', '.');
											}
											
											if (preco == '')
											{
												preco = 0;
											}
											if (custo == '')
											{
												custo = 0;
											}
											
											preco = parseFloat(preco);
											custo = parseFloat(custo);
											
											this.value = preco - custo;
											this.onblur();
										}
									</script>
								</td>
							</tr>
							<tr>
								<td align="left">
									<label class="azli-label">Custo do serviço</label>
									<input type="text" id="txt_custo" class="azli-input at-valida" tipo="Real" placeholder="R$ 999,99" style="text-transform: uppercase;" tabindex="13"/>
								</td>
								<td align="left">
									<label class="azli-label">Preço cobrado</label>
									<input type="text" id="txt_preco" class="azli-input at-valida" tipo="Real" placeholder="R$ 999,99" style="text-transform: uppercase;" tabindex="14"/>
								</td>
								<td align="right">
									<label class="azli-label">&nbsp;</label>
									<input type="button" id="btn_adicionarServico" class="azli-button cancel-btn" value="Adicionar serviço" tabindex="16"/>
								</td>
							</tr>
						</table>
						
						<table width="80%" id="servicos">
						</table>
						<script>
							//adicionarFuncionarios = '';
							//alterarFuncionarios = '';
							//excluirFuncionarios = '';
							ultimoServico = 0;
							contServico = '';
							
							btn_adicionarServico.onclick = function()
							{
								var descricao = txt_descricaoServico.value;
								var codigoFornecedor = cmb_fornecedor.value;
								var nomeFornecedor = cmb_fornecedor.selectedOptions[0].textContent;
								var custo = txt_custo.value;
								var preco = txt_preco.value;
								
								while (custo.indexOf('R') != -1 || custo.indexOf('$') != -1 || custo.indexOf(' ') != -1 || custo.indexOf(',') != -1)
								{
									custo = custo.replace('R', '');
									custo = custo.replace('$', '');
									custo = custo.replace(' ', '');
									custo = custo.replace(',', '.');
								}
								
								while (preco.indexOf('R') != -1 || preco.indexOf('$') != -1 || preco.indexOf(' ') != -1 || preco.indexOf(',') != -1)
								{
									preco = preco.replace('R', '');
									preco = preco.replace('$', '');
									preco = preco.replace(' ', '');
									preco = preco.replace(',', '.');
								}
								
								if (descricao == '')
								{
									
								}
								else if (preco == '')
								{
								}
								else if (custo == '')
								{
								}
								else
								{
									txt_lucro.onfocus();
									
									var lucro = preco - custo;
									
									ultimoServico = parseInt(ultimoServico);
									ultimoServico = ultimoServico + 1;
									
									if (contServico == '')
									{
										contServico = 0;
									}
									else
									{
										contServico = parseInt(contServico);
										contServico = contServico + 1;
									}	
									
									var tr = document.createElement('tr');
										$(tr).addClass('servico');
										tr.id = 'servico' + ultimoServico;
										
									var td = document.createElement('td');
										td.setAttribute('width', '18%');
										
									var input = document.createElement('input');
										input.type = 'hidden';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.name = 'servicos[' + contServico + '][cd_servico]';
										input.value = ultimoServico;
										
									td.appendChild(input);
									
									var input = document.createElement('input');
										input.type = 'hidden';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.name = 'servicos[' + contServico + '][ds_servico]';
										input.value = descricao;
										
									td.appendChild(input);
									
									var input = document.createElement('input');
										input.type = 'text';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Real');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										//input.name = 'servicos[' + contServico + '][ds_servico]';
										input.value = descricao;
										input.readOnly = true;
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '18%');
									
									var input = document.createElement('input');
										input.type = 'hidden';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.name = 'servicos[' + contServico + '][cd_fornecedor]';
										input.value = codigoFornecedor;
										
									td.appendChild(input);
									
									var input = document.createElement('input');
										input.type = 'text';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Real');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										//input.name = 'servicos[' + contServico + '][ds_servico]';
										input.value = ((codigoFornecedor == '') ? '-' : nomeFornecedor);
										input.readOnly = true;
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '18%');
									
									var input = document.createElement('input');
										input.type = 'hidden';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.name = 'servicos[' + contServico + '][vl_custo]';
										input.value = custo;
										
									td.appendChild(input);
									
									var input = document.createElement('input');
										input.type = 'text';
										//input.setAttribute('codigo', ultimoFuncionario);
										input.setAttribute('tipo', 'Real');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										$(input).addClass('at-valida');
										//input.name = 'servicos[' + contServico + '][ds_servico]';
										input.value = mascaraReal(custo);
										input.readOnly = true;
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '18%');
									
									var input = document.createElement('input');
										input.type = 'hidden';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.name = 'servicos[' + contServico + '][vl_preco]';
										input.value = preco;
										
									td.appendChild(input);
									
									var input = document.createElement('input');
										input.type = 'text';
										//input.setAttribute('codigo', ultimoFuncionario);
										input.setAttribute('tipo', 'Real');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										$(input).addClass('at-valida');
										//input.name = 'servicos[' + contServico + '][vl_preco]';
										input.value = mascaraReal(preco);
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '18%');
									
									var input = document.createElement('input');
										input.type = 'hidden';
										//input.setAttribute('codigo', ultimoFuncionario);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										//input.name = 'servicos[' + contServico + '][vl_custo]';
										input.value = lucro;
										
									td.appendChild(input);
									
									var input = document.createElement('input');
										input.type = 'text';
										//input.setAttribute('codigo', ultimoFuncionario);
										input.setAttribute('tipo', 'Real');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										$(input).addClass('at-valida');
										//input.name = 'servicos[' + contServico + '][ds_servico]';
										input.value = mascaraReal(lucro);
										input.readOnly = true;
										
									if (lucro < 0)
									{
										input.setAttribute('style', 'background-color: red;');
									}
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '18%');
									
									var input = document.createElement('input');
										input.type = 'button';
										$(input).addClass('func-del');
										input.value = "Remover";
										input.setAttribute('codigo', ultimoServico);
										input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover esse serviço?')) {/*ExcluirFuncionario(this.getAttribute('codigo'));*/ eval('servico' + this.getAttribute('codigo') + '.remove();'); contServico = contServico - 1; qt_servicos.textContent = contServico;}");
										
									td.appendChild(input);
									tr.appendChild(td);
									
									servicos.appendChild(tr);
									
									txt_descricaoServico.value = '';
									cmb_fornecedor.value = '';
									txt_custo.value = '';
									txt_preco.value = '';
									txt_lucro.value = '';
									
									qt_servicos.textContent = contServico;
									
									txt_descricaoServico.focus();
								}
							}
						</script>
						
						<table width="80%">	
							<tr>
								<td align="left">
									<label id="lbl_avisoAparelho" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
								</td>
								
								<td align="right">
									<input type="button" id="btn_voltarAdicionarAparelho" class="azli-button cancel-btn" value="Voltar" onclick="voltarAparelho = 1; BuscarAparelhosOrdem(codigoOrdem);">
									<input type="submit" class="azli-button" id="btn_cadastrarAparelho" value="Adicionar" onclick="//enterWin('#dados_servico')">
								</td>
							</tr>
						</table>
						<input type="hidden" id="txt_codigoOrdemAparelho" name="codigoOrdemAparelho" value=""/>
						<input type="text" id="ic_aparelhoCadastrado" name="aparelhoCadastrado" value="0" style="display: none;"/>
						<input type="text" id="ic_editarAparelho" name="editar" value="0" style="display: none;"/>
						<input type="text" name="etapaOrdem" value="2" style="display: none;"/>
					</form>
					<script>
						btn_buscarAparelho.onclick = function()
						{
							if (txt_IMEI.getAttribute('correto') == 1 || txt_IMEISecundario.getAttribute('correto') == 1 || txt_numeroSerie.getAttribute('correto') == 1)
							{
								aparelhoPesquisado = '';
							}
							else
							{
								ic_aparelhoCadastrado.value = 0;
								ic_editarAparelho.value = 0;
							}
	
							BuscarAparelho(txt_IMEI.value, txt_IMEISecundario.value, txt_numeroSerie.value, codigoOrdem);
						}
						
						function ExibirBuscaAparelho(retorno)
						{
							if (retorno.trim() != "")
							{
								retornoPesquisaAparelho = retorno.split(';-;');
								
								if (retornoPesquisaAparelho[0].trim() != '')
								{
									ic_aparelhoCadastrado.value = 1;
									
									txt_codigoAparelhoCadastro.value = retornoPesquisaAparelho[0].trim();
									
									marcaAparelhoPesquisado = retornoPesquisaAparelho[2].trim();
									modeloAparelhoPesquisado = retornoPesquisaAparelho[3].trim();
									
									cmb_categoria.value = retornoPesquisaAparelho[1].trim();
									cmb_categoria.onchange();
									
									//cmb_marca.value = retornoPesquisaAparelho[2].trim();
									//cmb_marca.onchange();
									
									IMEIOriginal = retornoPesquisaAparelho[4].trim();
									txt_IMEI.value = retornoPesquisaAparelho[4].trim();
									txt_descricaoAparelho.value = retornoPesquisaAparelho[5].trim();
									txt_notasAparelho.value = retornoPesquisaAparelho[6].trim();
									txt_codigoOrdemAparelho.value = retornoPesquisaAparelho[7].trim();
									txt_IMEISecundario.value = retornoPesquisaAparelho[8].trim();
									txt_numeroSerie.value = retornoPesquisaAparelho[9].trim();
									servicos.innerHTML = retornoPesquisaAparelho[10].trim();
									qt_servicos.textContent = retornoPesquisaAparelho[11].trim();
									ultimoServico = retornoPesquisaAparelho[12].trim();
									contServico = retornoPesquisaAparelho[13].trim();
								}
								
								VerificarForm(Frm_AdicionarAparelho);
								
								cmb_categoria.focus(); enterWin('#dados_aparelho');
							}
							else
							{
								ic_aparelhoCadastrado.value = 0;
								ic_editarAparelho.value = 0;
							}
						}
						
						function BuscarAparelho(imei, imeiSecundario, numeroSerie, codigo)
						{
							btn_buscarAparelho.disabled = true;
							
							Ajax("GET", "php/BuscarAparelho.php", "IMEI=" + imei + "&IMEISecundario=" + imeiSecundario + "&numeroSerie=" + numeroSerie + "&codigoOrdem=" + codigo, "btn_buscarAparelho.disabled = false; var retorno = this.responseText; ExibirBuscaAparelho(retorno);");
							
							txt_codigoOrdemAparelho.value = codigo;
						}
						
						Frm_AdicionarAparelho.onsubmit = function()
						{
							lbl_avisoAparelho.style.display = 'none';
							
							var campos = this.getElementsByClassName('at-valida');
								
							for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
							{
								campos[cont].style.border = '0px';
							}
							
							if (txt_IMEI.value == '' && txt_numeroSerie.value == '')
							{
								txt_IMEI.style.border = '1px solid red';
								txt_numeroSerie.style.border = '1px solid red';
								
								lbl_avisoAparelho.style.display = 'inline-block';
								lbl_avisoAparelho.textContent = "IMEI primário e número de série não podem estar em branco. . Escolha qual vai ser a identificação do aparelho, por IMEI ou número de série.";
							}
							else if (txt_IMEI.value != '' && txt_numeroSerie.value != '')
							{
								txt_IMEI.style.border = '1px solid red';
								txt_numeroSerie.style.border = '1px solid red';
								
								lbl_avisoAparelho.style.display = 'inline-block';
								lbl_avisoAparelho.textContent = "Apenas um dos dois campos (IMEI primário e número de série) podem ser preenchidos. . Escolha qual vai ser a identificação do aparelho, por IMEI ou número de série.";
							}
							else if (txt_numeroSerie.value != '' && txt_IMEISecundario.value != '')
							{
								txt_IMEISecundario.style.border = '1px solid red';
								txt_numeroSerie.style.border = '1px solid red';
								
								lbl_avisoAparelho.style.display = 'inline-block';
								lbl_avisoAparelho.textContent = "Para ter um IMEI secundário é necessário ter um IMEI primário. Escolha qual vai ser a identificação do aparelho, por IMEI ou número de série.";
							}
							else if (document.getElementsByClassName('servico').length == 0)
							{
								lbl_avisoAparelho.style.display = 'inline-block';
								lbl_avisoAparelho.textContent = "Insira algum serviço ao aparelho.";
							}
							else if (VerificarForm(this))
							{
								if (ic_aparelhoCadastrado.value == 1)
								{
									//txt_IMEI.value = IMEIOriginal;
									
									VerificarForm(this);
									
									if (confirm("Deseja alterar os dados deste aparelho já cadastrado?"))
									{
										ic_editarAparelho.value = 1;
									}
									else
									{
										ic_editarAparelho.value = 0;
									}
								}				
								
								AjaxForm(this, "cmb_categoria.disabled = false; btn_cadastrarAparelho.disabled = true;", "btn_cadastrarAparelho.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); /*alert(retorno);*/ if (indicador == 1) {btn_voltarAdicionarAparelho.click();} else {var aviso = retorno.split(';-;')[1].trim(); lbl_avisoAparelho.style.display = 'inline-block'; lbl_avisoAparelho.textContent = aviso; lbl_avisoAparelho.focus();} cmb_categoria.disabled = true;");
							}
							else
							{
								var campos = this.getElementsByClassName('at-valida');
								
								for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
								{
									if (campos[cont].getAttribute('correto') == 0 && campos[cont].required == true)
									{
										campos[cont].style.border = '1px solid red';
									}
								}
								
								lbl_avisoAparelho.style.display = 'inline-block';
								lbl_avisoAparelho.textContent = "Alguns campos não foram preenchidos corretamente! Verifique-os e tente novamente.";
								lbl_avisoAparelho.focus();
							}
							
							return false;
						}
					</script>
				</div>
				
				<div class="azli-window-form" id="dados_aparelho_dp">
					<table width="80%">
						<tr>
							<td>
								<label class="azli-label">Categoria</label>
								<input type="text" class="azli-input dp">
							</td>
							<td>
								<label class="azli-label">Marca</label>
								<input type="text" class="azli-input dp">
							</td>
						</tr>
						
						<tr>
							<td width="50%">
								<label class="azli-label">Modelo</label>
								<input type="text" class="azli-input dp">
							</td>
							
							<td width="50%">
								<label class="azli-label">IMEI</label>
								<input type="text" class="azli-input dp">
							</td>
						</tr>
						
						<tr>
							<td colspan="2">
								<label class="azli-label">Descrição do aparelho</label>
								<textarea class="azli-input dp" rows="3"></textarea>
							</td>							
						</tr>
						
						<tr>
							<td colspan="2">
								<label class="azli-label">Notas do aparelho</label>
								<textarea class="azli-input dp" rows="3"></textarea>
							</td>							
						</tr>
						
						<tr>
							<td>
								
							</td>
							
							<td align="right">
								<input type="button" class="azli-button cancel-btn" value="Voltar">
								<input type="button" class="azli-button" value="Editar">
							</td>
						</tr>
					</table>
				</div>
				
				<div class="azli-window-form" id="dados_servico">
					<form id="Frm_CadastroOrdem" method="POST" action="php/CadastrarOrdem.php">
						<table width="80%">
							<tr>
								<td colspan="2">
									<label class="azli-label">Funcionário - Obrigatório</label>
									<select id="cmb_funcionario" class="azli-select at-valida" tipo="Combobox" name="funcionario" required tabindex="1">
										<option value="">Selecione o funcionário responsável por essa ordem de serviço</option>
										<?php
											if ($total_Funcionarios > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Funcionarios['cd_funcionario']; ?>"><?php echo $linha_Funcionarios['nm_funcionario']; ?></option>
										<?php
												}
												while ($linha_Funcionarios = mysqli_fetch_assoc($result_Funcionarios));
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td width="50%">
									<label class="azli-label">Status de serviço - Obrigatório</label>
									<input type="text" id="txt_codigoOrdemDatas" name="codigoOrdemDatas" value="" style="display: none;"/>
									<select id="cmb_status" class="azli-select at-valida" tipo="Combobox" name="status" required tabindex="2">
										<option value="">Selecione o status do serviço</option>
										<?php
											if ($total_Status > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Status['cd_status']; ?>"><?php echo $linha_Status['nm_status']; ?></option>
										<?php
												}
												while ($linha_Status = mysqli_fetch_assoc($result_Status));
											}
										?>
									<select>
								</td>
								<td width="50%">
									<label class="azli-label">Data do orçamento - Obrigatório</label>
									<input type="text" id="txt_dataOrcamento" class="azli-input at-valida" tipo="Data" name="dataOrcamento" placeholder="Exemplo: 01/01/2015" required tabindex="3"/>
								</td>
							</tr>
							
							<tr>
								<td>
									<label class="azli-label">Data de confirmação</label>
									<input type="text" id="txt_dataConfirmacao" class="azli-input at-valida" tipo="Data" name="dataConfirmacao" placeholder="Exemplo: 01/01/2015" tabindex="4"/>
								</td>
								<td>
									<label class="azli-label">Data de finalização</label>
									<input type="text" id="txt_dataFinalizacao" class="azli-input at-valida" tipo="Data" name="dataFinalizacao" placeholder="Exemplo: 01/01/2015" tabindex="5"/>
								</td>
							</tr>
							
							<tr>
								<td>
									<label class="azli-label">Data de retirada</label>
									<input type="text" id="txt_dataRetirada" class="azli-input at-valida" tipo="Data" name="dataRetirada" placeholder="Exemplo: 01/01/2016" tabindex="6"/>
								</td>

								<td>
									<label class="azli-label">Data de expiração</label>
									<input type="text" id="txt_dataExpiracao" class="azli-input at-valida" tipo="Data" name="dataExpiracao" placeholder="Exemplo: 01/01/2015" readonly tabindex="7"/>
								</td>							
							</tr>
							
							<tr>
								<td colspan="2">
									<label class="azli-label">Notas do serviço</label>
									<textarea id="txt_notasServico" class="azli-input at-valida" name="notas" tipo="TextoObrigatorio" rows="3" placeholder="Insira anotações gerais da ordem de serviço" maxLength="500" tabindex="8"></textarea>
								</td>							
							</tr>
							
							<tr>
								<td align="left">
									<label id="lbl_avisoServico" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
								</td>
								
								<td align="right">
									<input type="button" id="btn_voltarOrdem" class="azli-button cancel-btn" value="Voltar" onclick="if (codigoOrdem == '') {btn_adicionarAparelho.focus(); enterWin('#dados_lista_os_ap');} else {codigoOrdem = ''; enterWin('#dados_os_list');}" tabindex="9"/>
									<input type="button" id="btn_cancelarOrdem" class="azli-button cancel-btn" value="Cancelar" tabindex="10"/>
									<input type="submit" id="btn_cadastrarOrdem" class="azli-button" value="Finalizar" tabindex="11"/>
								</td>
							</tr>
						</table>
						<input type="hidden" name="etapaOrdem" value="3"/>
					</form>
					<script>
						Frm_CadastroOrdem.onsubmit = function()
						{
							txt_dataExpiracao.onfocus();
							lbl_avisoServico.style.display = 'none';
							
							var campos = this.getElementsByClassName('at-valida');
								
							for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
							{
								campos[cont].style.border = '0px';
							}
							
							if (VerificarForm(this))
							{
								var dataHoje = new Date();
									dataHoje = dataHoje.getDate() + '/' + (((dataHoje.getMonth() + 1) < 10) ? '0' : '') + (dataHoje.getMonth() + 1) + '/' + dataHoje.getFullYear();
									dataHoje = dataHoje.split('/');
									dataHoje = dataHoje[1] + '-' + dataHoje[0] + '-' + dataHoje[2];
									dataHoje = new Date(dataHoje).getTime();
								var dataOrcamento = txt_dataOrcamento.value;
									dataOrcamento = dataOrcamento.split('/');
									dataOrcamento = dataOrcamento[1] + '-' + dataOrcamento[0] + '-' + dataOrcamento[2];
									dataOrcamento = new Date(dataOrcamento).getTime();
									
								if (txt_dataConfirmacao.value != "")
								{
									var dataConfirmacao = txt_dataConfirmacao.value;
									dataConfirmacao = dataConfirmacao.split('/');
									dataConfirmacao = dataConfirmacao[1] + '-' + dataConfirmacao[0] + '-' + dataConfirmacao[2];
									dataConfirmacao = new Date(dataConfirmacao).getTime();
								}
								
								if (txt_dataFinalizacao.value != "")
								{
									var dataFinalizacao = txt_dataFinalizacao.value;
									dataFinalizacao = dataFinalizacao.split('/');
									dataFinalizacao = dataFinalizacao[1] + '-' + dataFinalizacao[0] + '-' + dataFinalizacao[2];
									dataFinalizacao = new Date(dataFinalizacao).getTime();
								}
								
								if (txt_dataRetirada.value != "")
								{
									var dataRetirada = txt_dataRetirada.value;
									dataRetirada = dataRetirada.split('/');
									dataRetirada = dataRetirada[1] + '-' + dataRetirada[0] + '-' + dataRetirada[2];
									dataRetirada = new Date(dataRetirada).getTime();
								}
								
								/*if (dataOrcamento < dataHoje)
								{
									txt_dataOrcamento.style.border = '1px solid red';
									txt_dataOrcamento.focus();
									
									lbl_avisoServico.textContent = 'A data do orçamento não pode ser menor que a data atual.';
									lbl_avisoServico.style.display = 'inline-block';
								}
								else */if (dataConfirmacao < dataOrcamento)
								{
									txt_dataConfirmacao.style.border = '1px solid red';
									txt_dataConfirmacao.focus();
									
									lbl_avisoServico.textContent = 'A data do confirmação não pode ser menor que a data do orçamento.';
									lbl_avisoServico.style.display = 'inline-block';
								}
								else if (dataFinalizacao < dataOrcamento)
								{
									txt_dataFinalizacao.style.border = '1px solid red';
									txt_dataFinalizacao.focus();
									
									lbl_avisoServico.textContent = 'A data de finalização não pode ser menor que a data do orçamento.';
									lbl_avisoServico.style.display = 'inline-block';
								}
								else if (dataRetirada < dataOrcamento)
								{
									txt_dataRetirada.style.border = '1px solid red';
									txt_dataRetirada.focus();
									
									lbl_avisoServico.textContent = 'A data de finalização não pode ser menor que a data do orçamento.';
									lbl_avisoServico.style.display = 'inline-block';
								}
								else
								{
									AjaxForm(this, "btn_cadastrarOrdem.disabled = true;", "btn_cadastrarOrdem.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); /*alert(retorno);*/ if (indicador == 1) {if (codigoOrdem == '') {var codigoVerificador = retorno.split(';-;')[3].trim(); alert('Ordem de serviço efetuada com sucesso!\\n\\nCódigo verificador dessa ordem de serviço: ' + codigoVerificador + '\\n\\nATENÇÃO: Guarde bem esse código para evitar transtornos.'); window.open('ImprimirOrdem.php?codigo=' + codigoVerificador, '_blank'); CancelarOrdem();} else {btn_voltarOrdem.click(); btn_pesquisarOrdem.click();}} else {var aviso = retorno.split(';-;')[1].trim(); lbl_avisoServico.style.display = 'inline-block'; lbl_avisoServico.textContent = aviso; lbl_avisoServico.focus();}");
								}
							}
							else
							{
								var campos = this.getElementsByClassName('at-valida');
								
								for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
								{
									if (campos[cont].getAttribute('correto') == 0)
									{
										campos[cont].style.border = '1px solid red';
									}
								}
								
								lbl_avisoCliente.style.display = 'inline-block';
								lbl_avisoCliente.textContent = "Alguns campos não foram preenchidos corretamente! Verifique-os e tente novamente."
								lbl_avisoCliente.focus();
							}
							
							return false;
						}
						
						btn_cancelarOrdem.onclick = function()
						{
							if (confirm("Tem certeza que deseja cancelar a ordem de serviço?"))
							{
								btn_cancelarOrdem.disabled = true;
								Ajax("GET", "php/CancelarOrdem.php", "", "btn_cancelarOrdem.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); if (indicador == 1) {CancelarOrdem();}");
							}
						}
						
						txt_dataExpiracao.onfocus = function()
						{
							if (txt_dataFinalizacao.value.length == 10 && txt_dataFinalizacao.getAttribute('correto') == 1)
							{
								this.value = CalcularExpiracao(txt_dataFinalizacao.value);
							}
							else
							{
								this.value = '';
							}
						}
						
						function CalcularExpiracao(finalizacao)
						{
							var aux = finalizacao.split('/');
								aux = aux[1] + '-' + aux[0] + '-' + aux[2];
								aux = new Date(aux).getTime();
								aux = aux + (1000*60*60*24*90);
								aux = new Date(aux);
								
							return (((aux.getDate() + 1) < 10) ? '0' : '') + (aux.getDate() + 1) + '/' + (((aux.getMonth() + 1) < 10) ? '0' : '') + (aux.getMonth() + 1) + '/' + aux.getFullYear();
							
						}
						
						function BuscarDatasOrdem(codigo)
						{
							if (codigo == '')
							{
								btn_cancelarOrdem.style.display = 'inline-block';
								btn_cadastrarOrdem.value = 'Finalizar';
							}
							else
							{
								btn_cancelarOrdem.style.display = 'none';
								btn_cadastrarOrdem.value = 'Alterar';
							}
							
							Ajax("GET", "php/BuscarDatasOrdem.php", "codigoOrdem=" + codigo, "var retorno = this.responseText; ExibirBuscaDatasOrdem(retorno);");
						}
						
						function ExibirBuscaDatasOrdem(retorno)
						{
							Frm_CadastroOrdem.reset();

							if (retorno.trim() != "")
							{
								aux = retorno.split(';-;');
								txt_codigoOrdemDatas.value = aux[0].trim();
								cmb_status.value = aux[1].trim();
								txt_dataOrcamento.value = aux[2].trim();
								txt_dataConfirmacao.value = aux[3].trim();
								txt_dataFinalizacao.value = aux[4].trim();
								txt_dataRetirada.value = aux[5].trim();
								txt_dataExpiracao.value = aux[6].trim();
								txt_notasServico.value = aux[7].trim();
								cmb_funcionario.value = aux[8].trim();
								
								VerificarForm(Frm_CadastroOrdem);
								
								if (codigoOrdem != '')
								{
									enterWin('#dados_servico');
								}
							}
						}
					</script>
				</div>
				
				<div class="azli-window-form" id="dados_os_list">
					<form id="Frm_PesquisarOrdem" method="POST" action="php/PesquisarOrdem.php" onkeypress="if (event.keyCode == 13) {btn_pesquisarOrdem.click();}">
						<table width="90%">
							<tr>
								<td colspan="1">
									<label class="azli-label">Status</label>
									<select id="cmb_statusOrdem" class="azli-select at-valida" tipo="Combobox" name="status" required>
										<option value="all">Todos</option>
										<?php
											$result_Status = $conexaoPrincipal -> Query($query_Status);
											$linha_Status = mysqli_fetch_assoc($result_Status);
											$total_Status = mysqli_num_rows($result_Status);
											
											if ($total_Status > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Status['cd_status']; ?>"><?php echo $linha_Status['nm_status']; ?></option>
										<?php
												}
												while ($linha_Status = mysqli_fetch_assoc($result_Status));
											}
										?>
									</select>
								</td>
								<td colspan="1">
									<label class="azli-label">Código</label>
									<input type="text" id="txt_codigoOrdem" class="azli-input at-valida" tipo="int" placeholder="Insira o código da ordem de serviço" name="codigo">
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">
									<input type="reset" class="azli-button cancel-btn" value="Limpar"/>
									<input type="submit" id="btn_pesquisarOrdem" class="azli-button" value="Pesquisar"/>
								</td>
							</tr>
						</table>
					</form>
					
					<script>
						codigoOrdem = '';
					
						Frm_PesquisarOrdem.onsubmit = function()
						{
							if (VerificarForm(this))
							{
								AjaxForm(this, "btn_pesquisarOrdem.disabled = true;", "btn_pesquisarOrdem.disabled = false; var retorno = this.responseText; ordens.innerHTML = retorno;");
							}
							
							return false;
						}
						
						function ExibirClienteOrdem(codigo)
						{
							Frm_CadastroCliente.reset();
							VerificarForm(Frm_CadastroCliente);
							
							codigoOrdem = codigo;
							
							btn_buscarCliente.click();
						}
						
						function ExibirAparelhosOrdem(codigo)
						{
							codigoOrdem = codigo;
							BuscarAparelhosOrdem(codigo);
						}
						
						function ExibirDatasOrdem(codigo)
						{
							codigoOrdem = codigo;
							btn_cancelarOrdem.style.display = 'none';
							BuscarDatasOrdem(codigo);
						}
						
						function SairAlterarAparelhosOrdem()
						{
							codigoOrdem = '';
							enterWin('#dados_os_list');
						}
						
						function RemoverOrdem(codigo)
						{
							Ajax("GET", "php/RemoverOrdem.php", "codigoOrdemRemover=" + codigo, "var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); var aviso = retorno.split(';-;')[1].trim(); if (indicador == 1) {btn_pesquisarOrdem.click();} else {alert(aviso);}");
						}
					</script>
					
					<table width="90%" id="ordens">
						<?php
							$result_Ordem = $conexaoPrincipal -> Query("select tb_os.cd_verificador,
																			   tb_os.dt_cadastro,
																			   tb_status.nm_status,
																			   tb_os.cd_os,
														   (select concat('R$ ', sum(tb_servico_valores.vl_custo),'/R$ ', sum(tb_servico_valores.vl_preco),'/R$ ', (sum(tb_servico_valores.vl_preco) - sum(tb_servico_valores.vl_custo)))
  from tb_os  as tb_os_valores inner join item_os as item_os_valores
    on tb_os_valores.cd_os = item_os_valores.cd_os
      inner join tb_aparelho as tb_aparelho_valores
        on item_os_valores.cd_aparelho = tb_aparelho_valores.cd_aparelho
          inner join tb_servico as tb_servico_valores
            on tb_aparelho_valores.cd_aparelho = tb_servico_valores.cd_aparelho
     where tb_os_valores.cd_os = tb_os.cd_os and tb_servico_valores.cd_os = tb_os.cd_os) as ds_valores
																			from tb_os inner join tb_status 
																				on tb_os.cd_status = tb_status.cd_status
																					order by tb_os.dt_cadastro desc");
							$linha_Ordem = mysqli_fetch_assoc($result_Ordem);
							$total_Ordem = mysqli_num_rows($result_Ordem);
							
							if ($total_Ordem > 0)
							{
								do
								{
						?>
									<tr>
										<td width="auto" style="max-width: 30%;">
											<input type="button" class="azli-input func-at list fontm" value="Nº <?php echo $linha_Ordem['cd_verificador'].' - '.date('d/m/Y H:i', strtotime($linha_Ordem['dt_cadastro'])).' - '.$linha_Ordem['nm_status'].' - '.(($linha_Ordem['ds_valores'] != '') ? $linha_Ordem['ds_valores'] : 'R$ 0,00/R$ 0,00/R$ 0,00'); ?>">
										</td>
										<td width="10%">
											<a href="ImprimirOrdem.php?codigo=<?php echo $linha_Ordem['cd_verificador']; ?>" target="_blank" title="Imprimir ordem de serviço."><input type="button" class="func-del fontm" value="Imprimir"></a>
										</td>
										<td width="10%">
											<input type="button" class="func-del fontm" value="Cliente" onclick="ExibirClienteOrdem('<?php echo $linha_Ordem['cd_os']; ?>');"/>
										</td>
										<td width="10%">
											<input type="button" class="func-del fontm" value="Aparelho(s)" onclick="ExibirAparelhosOrdem('<?php echo $linha_Ordem['cd_os']; ?>');"/>
										</td>
										<td width="10%">
											<input type="button" class="func-del fontm" value="Status" onclick="ExibirDatasOrdem('<?php echo $linha_Ordem['cd_os']; ?>');"/>
										</td>
										<td width="10%">
											<input type="button" class="func-del fontm" value="Remover" onclick="if (confirm('Tem certeza que deseja remover essa ordem de serviço?')) {RemoverOrdem('<?php echo $linha_Ordem['cd_os']; ?>')};"/>
										</td>
									</tr>
						<?php
								}
								while ($linha_Ordem = mysqli_fetch_assoc($result_Ordem));
							}
						?>
					</table>
				</div>
				
				<div class="azli-window-form" id="dados_funcionario">
					<form>
						<table width="80%" id="funcionarios">
							<script> ultimoFuncionario = 0; </script>
							<tr>
								<td colspan="2">
									<input type="text" id="txt_nomeFuncionarioCadastrar" class="azli-input at-valida" tipo="Nome" placeholder="Insira o nome do novo funcionário"/>
								</td>
							<tr>
							
							<?php
								$result_Funcionarios = $conexaoPrincipal -> Query($query_Funcionarios);
								$linha_Funcionarios = mysqli_fetch_assoc($result_Funcionarios);
								$total_Funcionarios = mysqli_num_rows($result_Funcionarios);
								
								if ($total_Funcionarios > 0)
								{
									do
									{
							?>
										<tr id="funcionario<?php echo $linha_Funcionarios['cd_funcionario']; ?>">
											<td width="90%">
												<input type="text" codigo="<?php echo $linha_Funcionarios['cd_funcionario']; ?>" class="azli-input func-at" value="<?php echo $linha_Funcionarios['nm_funcionario']; ?>" onfocus="this.setAttribute('nomeAnterior', this.value.trim());" onblur="if (this.value.trim() != '') {AlterarFuncionario(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}"/>
											</td>
											<td width="10%">
												<input type="button" codigo="<?php echo $linha_Funcionarios['cd_funcionario']; ?>" class="func-del" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse funcionário?')) {ExcluirFuncionario(this.getAttribute('codigo'));}"/>
											</td>
										</tr>
										<script> if (<?php echo $linha_Funcionarios['cd_funcionario']; ?> > ultimoFuncionario) {ultimoFuncionario = <?php echo $linha_Funcionarios['cd_funcionario']; ?>;} </script>
							<?php
									}
									while ($linha_Funcionarios = mysqli_fetch_assoc($result_Funcionarios));
								}
							?>
						</table>
					</form>
					<table width="80%">
						<tr>
							<td width="50%">
								<label id="lbl_avisoFuncionarios" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
							</td>
							
							<td align="right" width="50%">
								<input type="button" id="btn_finalizarFuncionarios" class="azli-button" value="Finalizar" onclick="FinalizarFuncionarios();"/>
							</td>
						</tr>
					</table>
				</div>
				<script>
					adicionarFuncionarios = '';
					alterarFuncionarios = '';
					excluirFuncionarios = '';
				
					txt_nomeFuncionarioCadastrar.onkeyup = function()
					{
						var cont, aux, foi;
						
						if (event.keyCode == 13 && this.value.trim() != '' && this.disabled == false) 
						{
							this.disabled = true;
							this.value = this.value.trim();
							
							if (adicionarFuncionarios == '')
							{
								ultimoFuncionario = ultimoFuncionario + 1;
								
								adicionarFuncionarios = ultimoFuncionario + ';' + this.value;
								
								foi = 1;
							}
							else
							{
								aux = adicionarFuncionarios.split('{}');
								
								for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
								{
									if (aux[cont].split(';')[1].toString().toLowerCase() === this.value.toString().toLowerCase())
									{
										foi = 0;
										break;
									}
									else
									{
										foi = 1;
									}
								}
								
								if (foi == 1)
								{
									ultimoFuncionario = ultimoFuncionario + 1;
								
									adicionarFuncionarios = adicionarFuncionarios + '{}' + ultimoFuncionario + ';' + this.value;
								}
							}
							
							if (foi == 1)
							{
								var tr = document.createElement('tr');
									$(tr).addClass('funcionario');
									tr.id = 'funcionario' + ultimoFuncionario;
									
								var td = document.createElement('td');
									td.setAttribute('width', '90%');
									
								var input = document.createElement('input');
									input.type = 'text';
									input.setAttribute('codigo', ultimoFuncionario);
									//input.setAttribute('tipo', 'Texto');
									$(input).addClass('azli-input');
									$(input).addClass('func-at');
									//$(input).addClass('at-valida');
									input.value = this.value;
									input.setAttribute('onfocus', "this.setAttribute('nomeAnterior', this.value.trim());");
									input.setAttribute('onblur', "if (this.value.trim() != '') {AlterarFuncionario(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}");
									
								td.appendChild(input);
								tr.appendChild(td);
								
								var td = document.createElement('td');
									td.setAttribute('width', '90%');
								
								var input = document.createElement('input');
									input.type = 'button';
									$(input).addClass('func-del');
									input.value = "Remover";
									input.setAttribute('codigo', ultimoFuncionario);
									input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover esse funcionário?')) {ExcluirFuncionario(this.getAttribute('codigo'));}");
									
								td.appendChild(input);
								tr.appendChild(td);
								
								funcionarios.appendChild(tr);
								
								this.value = '';
							}
							else
							{
								alert('Funcionário já adicionado!');
							}
							
						}
						
						this.disabled = false;
						return true;
					}
					
					function AlterarFuncionario(codigo, nome)
					{
						if (alterarFuncionarios == '')
						{
							alterarFuncionarios = alterarFuncionarios + codigo + ';' + nome;
						}
						else
						{
							alterarFuncionarios = alterarFuncionarios + '{}' + codigo + ';' + nome;
						}
					}
					
					function ExcluirFuncionario(codigo)
					{							
						var aux = adicionarFuncionarios.split('{}'); 
						var foi = 0;
						
						adicionarFuncionarios = '';
						
						for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
						{
							if (aux[cont].split(';')[0] == codigo)
							{
								aux.splice(cont, 1);
								foi = 1;
							}
							
							if (aux.length != 0)
							{
								if (adicionarFuncionarios == '')
								{
									adicionarFuncionarios = adicionarFuncionarios + aux[cont];
								}									
								else if (cont <= aux.length - 1)
								{
									adicionarFuncionarios = adicionarFuncionarios + '{}' + aux[cont];
								}
							}
							else
							{
								adicionarFuncionarios = '';
							}
						}
						
						if (foi == 0)
						{
							if (excluirFuncionarios == '')
							{
								excluirFuncionarios = excluirFuncionarios + codigo;
							}
							else
							{
								excluirFuncionarios = excluirFuncionarios + '{}' + codigo;
							}
						}
						
						aux = alterarFuncionarios.split('{}');
						
						alterarFuncionarios = '';
						
						for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
						{
							if (aux[cont].split(';')[0] == codigo)
							{
								aux.splice(cont, 1);
								
								if (cont != 0)
								{
									cont = cont - 1;
								}
							}
							else
							{
								if (alterarFuncionarios == '')
								{
									alterarFuncionarios = alterarFuncionarios + aux[cont];
								}
								else if (cont <= aux.length - 1)
								{
									alterarFuncionarios = alterarFuncionarios + '{}' + aux[cont];
								}
							}
						}
						
						eval('funcionario' + codigo + '.remove();'); 
					}
					
					function FinalizarFuncionarios()
					{
						lbl_avisoFuncionarios.style.display = 'none';
						btn_finalizarFuncionarios.disabled = true;
						
						Ajax("GET", "php/FinalizarFuncionarios.php", "adicionar=" + adicionarFuncionarios + "&alterar=" + alterarFuncionarios + "&excluir=" + excluirFuncionarios, "btn_finalizarFuncionarios.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();} else {lbl_avisoFuncionarios.textContent = 'Ocorreu um erro durante o processo: ' + retorno; lbl_avisoFuncionarios.style.display = 'inline-block';}");
					}
				</script>
				
				<!----------------->
				
				<div class="azli-window-form" id="dados_fornecedor">
					<form>
						<table width="80%" id="fornecedores">
							<script> ultimoFornecedor = 0; </script>
							<tr>
								<td colspan="2">
									<input type="text" id="txt_nomeFornecedorCadastrar" class="azli-input at-valida" tipo="Nome" placeholder="Insira o nome do novo fornecedor"/>
								</td>
							<tr>
							
							<?php
								$result_Fornecedores = $conexaoPrincipal -> Query($query_Fornecedores);
								$linha_Fornecedores = mysqli_fetch_assoc($result_Fornecedores);
								$total_Fornecedores = mysqli_num_rows($result_Fornecedores);
								
								if ($total_Fornecedores > 0)
								{
									do
									{
							?>
										<tr id="fornecedor<?php echo $linha_Fornecedores['cd_fornecedor']; ?>">
											<td width="90%">
												<input type="text" codigo="<?php echo $linha_Fornecedores['cd_funcionario']; ?>" class="azli-input func-at" value="<?php echo $linha_Fornecedores['nm_fornecedor']; ?>" onfocus="this.setAttribute('nomeAnterior', this.value.trim());" onblur="if (this.value.trim() != '') {AlterarFornecedor(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}"/>
											</td>
											<td width="10%">
												<input type="button" codigo="<?php echo $linha_Fornecedores['cd_fornecedor']; ?>" class="func-del" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse fornecedor?')) {ExcluirFornecedor(this.getAttribute('codigo'));}"/>
											</td>
										</tr>
										<script> if (<?php echo $linha_Fornecedores['cd_fornecedor']; ?> > ultimoFornecedor) {ultimoFornecedor = <?php echo $linha_Fornecedores['cd_fornecedor']; ?>;} </script>
							<?php
									}
									while ($linha_Fornecedores = mysqli_fetch_assoc($result_Fornecedores));
								}
							?>
						</table>
					</form>
					<table width="80%">
						<tr>
							<td width="50%">
								<label id="lbl_avisoFornecedores" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
							</td>
							
							<td align="right" width="50%">
								<input type="button" id="btn_finalizarFornecedores" class="azli-button" value="Finalizar" onclick="FinalizarFornecedores();"/>
							</td>
						</tr>
					</table>
				</div>
				<script>
					adicionarFornecedores = '';
					alterarFornecedores = '';
					excluirFornecedores = '';
				
					txt_nomeFornecedorCadastrar.onkeyup = function()
					{
						var cont, aux, foi;
						
						if (event.keyCode == 13 && this.value.trim() != '' && this.disabled == false) 
						{
							this.disabled = true;
							this.value = this.value.trim();
							
							if (adicionarFornecedores == '')
							{
								ultimoFornecedor = ultimoFornecedor + 1;
								
								adicionarFornecedores = ultimoFornecedor + ';' + this.value;
								
								foi = 1;
							}
							else
							{
								aux = adicionarFornecedores.split('{}');
								
								for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
								{
									if (aux[cont].split(';')[1].toString().toLowerCase() === this.value.toString().toLowerCase())
									{
										foi = 0;
										break;
									}
									else
									{
										foi = 1;
									}
								}
								
								if (foi == 1)
								{
									ultimoFornecedor = ultimoFornecedor + 1;
								
									adicionarFornecedores = adicionarFornecedores + '{}' + ultimoFornecedor + ';' + this.value;
								}
							}
							
							if (foi == 1)
							{
								var tr = document.createElement('tr');
									$(tr).addClass('fornecedor');
									tr.id = 'fornecedor' + ultimoFuncionario;
									
								var td = document.createElement('td');
									td.setAttribute('width', '90%');
									
								var input = document.createElement('input');
									input.type = 'text';
									input.setAttribute('codigo', ultimoFornecedor);
									//input.setAttribute('tipo', 'Texto');
									$(input).addClass('azli-input');
									$(input).addClass('func-at');
									//$(input).addClass('at-valida');
									input.value = this.value;
									input.setAttribute('onfocus', "this.setAttribute('nomeAnterior', this.value.trim());");
									input.setAttribute('onblur', "if (this.value.trim() != '') {AlterarFornecedor(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}");
									
								td.appendChild(input);
								tr.appendChild(td);
								
								var td = document.createElement('td');
									td.setAttribute('width', '90%');
								
								var input = document.createElement('input');
									input.type = 'button';
									$(input).addClass('func-del');
									input.value = "Remover";
									input.setAttribute('codigo', ultimoFornecedor);
									input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover esse fornecedor?')) {ExcluirFornecedor(this.getAttribute('codigo'));}");
									
								td.appendChild(input);
								tr.appendChild(td);
								
								fornecedores.appendChild(tr);
								
								this.value = '';
							}
							else
							{
								alert('Fornecedor já adicionado!');
							}
							
						}
						
						this.disabled = false;
						return true;
					}
					
					function AlterarFornecedor(codigo, nome)
					{
						if (alterarFornecedores == '')
						{
							alterarFornecedores = alterarFornecedores + codigo + ';' + nome;
						}
						else
						{
							alterarFornecedores = alterarFornecedores + '{}' + codigo + ';' + nome;
						}
					}
					
					function ExcluirFornecedor(codigo)
					{							
						var aux = adicionarFornecedores.split('{}'); 
						var foi = 0;
						
						adicionarFornecedores = '';
						
						for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
						{
							if (aux[cont].split(';')[0] == codigo)
							{
								aux.splice(cont, 1);
								foi = 1;
							}
							
							if (aux.length != 0)
							{
								if (adicionarFornecedores == '')
								{
									adicionarFornecedores = adicionarFornecedores + aux[cont];
								}									
								else if (cont <= aux.length - 1)
								{
									adicionarFornecedores = adicionarFornecedores + '{}' + aux[cont];
								}
							}
							else
							{
								adicionarFornecedores = '';
							}
						}
						
						if (foi == 0)
						{
							if (excluirFornecedores == '')
							{
								excluirFornecedores = excluirFornecedores + codigo;
							}
							else
							{
								excluirFornecedores = excluirFornecedores + '{}' + codigo;
							}
						}
						
						aux = alterarFornecedores.split('{}');
						
						alterarFornecedores = '';
						
						for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
						{
							if (aux[cont].split(';')[0] == codigo)
							{
								aux.splice(cont, 1);
								
								if (cont != 0)
								{
									cont = cont - 1;
								}
							}
							else
							{
								if (alterarFornecedores == '')
								{
									alterarFornecedores = alterarFornecedores + aux[cont];
								}
								else if (cont <= aux.length - 1)
								{
									alterarFornecedores = alterarFornecedores + '{}' + aux[cont];
								}
							}
						}
						
						eval('fornecedor' + codigo + '.remove();'); 
					}
					
					function FinalizarFornecedores()
					{
						lbl_avisoFornecedores.style.display = 'none';
						btn_finalizarFornecedores.disabled = true;
						
						Ajax("GET", "php/FinalizarFornecedores.php", "adicionar=" + adicionarFornecedores + "&alterar=" + alterarFornecedores + "&excluir=" + excluirFornecedores, "btn_finalizarFornecedores.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();} else {lbl_avisoFornecedores.textContent = 'Ocorreu um erro durante o processo: ' + retorno; lbl_avisoFornecedores.style.display = 'inline-block';}");
					}
				</script>
				
				<div class="azli-window-form" id="dados_categoria">
					<form>
						<table width="80%" id="categorias">
							<script> ultimaCategoria = 0; </script>
							<tr>
								<td colspan="2">
									<input type="text" id="txt_nomeCategoriaCadastrar" class="azli-input at-valida" tipo="Texto" placeholder="Insira o nome da nova categoria"/>
								</td>
							<tr>
							
							<?php
								$result_Categoria = $conexaoPrincipal -> Query("select cd_categoria, nm_categoria, ic_editavel from tb_categoria order by nm_categoria");
								$linha_Categoria = mysqli_fetch_assoc($result_Categoria);
								$total_Categoria = mysqli_num_rows($result_Categoria);
								
								if ($total_Categoria > 0)
								{
									do
									{
							?>
										<tr class="categoria cadastrado" id="categoria<?php echo $linha_Categoria['cd_categoria']; ?>">
											<td width="90%" colspan="<?php if ($linha_Categoria['ic_editavel'] == 0) {echo 2;} else {echo 1;} ?>">
												<input type="text" codigo="<?php echo $linha_Categoria['cd_categoria']; ?>" class="azli-input func-at" value="<?php echo $linha_Categoria['nm_categoria']; ?>" onfocus="this.setAttribute('nomeAnterior', this.value.trim());" onblur="if (this.value.trim() != '') {AlterarCategoria(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}" <?php if ($linha_Categoria['ic_editavel'] == 0) {echo 'disabled';} ?>/>
											</td>
											<?php
												if ($linha_Categoria['ic_editavel'] == 1)
												{
											?>
													<td width="10%">
														<input type="button" class="func-del" value="Remover" codigo="<?php echo $linha_Categoria['cd_categoria']; ?>" onclick="if (confirm('Tem certeza que deseja remover essa categoria?')) {ExcluirCategoria(this.getAttribute('codigo'));}"/>
													</td>
											<?php
												}
											?>
										</tr>
										<script> if (<?php echo $linha_Categoria['cd_categoria']; ?> > ultimaCategoria) {ultimaCategoria = <?php echo $linha_Categoria['cd_categoria']; ?>;} </script>
							<?php
									}
									while ($linha_Categoria = mysqli_fetch_assoc($result_Categoria));
								}
							?>						
						</table>
						<table width="80%">
							<tr>
								<td width="50%">
									<label id="lbl_avisoCategorias" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
								</td>
								
								<td align="right" width="50%">
									<input type="button" class="azli-button cancel-btn" value="Cancelar" onclick="window.location.reload();"/>
									<input type="button" id="btn_finalizarCategorias" class="azli-button" value="Finalizar" onclick="FinalizarCategorias();">
								</td>
							</tr>
						</table>
					</form>
					
					<script>
						adicionarCategorias = '';
						alterarCategorias = '';
						excluirCategorias = '';
					
						txt_nomeCategoriaCadastrar.onkeyup = function()
						{
							var cont, aux, foi;
							
							if (event.keyCode == 13 && this.value.trim() != '' && this.disabled == false) 
							{
								this.disabled = true;
								this.value = this.value.trim();
								
								if (adicionarCategorias == '')
								{
									ultimaCategoria = ultimaCategoria + 1;
									
									adicionarCategorias = ultimaCategoria + ';' + this.value;
									
									foi = 1;
								}
								else
								{
									aux = adicionarCategorias.split('{}');
									
									for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
									{
										if (aux[cont].split(';')[1].toString().toLowerCase() === this.value.toString().toLowerCase())
										{
											foi = 0;
											break;
										}
										else
										{
											foi = 1;
										}
									}
									
									if (foi == 1)
									{
										ultimaCategoria = ultimaCategoria + 1;
									
										adicionarCategorias = adicionarCategorias + '{}' + ultimaCategoria + ';' + this.value;
									}
								}
								
								if (foi == 1)
								{
									var tr = document.createElement('tr');
										$(tr).addClass('categoria');
										tr.id = 'categoria' + ultimaCategoria;
										
									var td = document.createElement('td');
										td.setAttribute('width', '90%');
										
									var input = document.createElement('input');
										input.type = 'text';
										input.setAttribute('codigo', ultimaCategoria);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.value = this.value;
										input.setAttribute('onfocus', "this.setAttribute('nomeAnterior', this.value.trim());");
										input.setAttribute('onblur', "if (this.value.trim() != '') {AlterarCategoria(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}");
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '90%');
									
									var input = document.createElement('input');
										input.type = 'button';
										$(input).addClass('func-del');
										input.value = "Remover";
										input.setAttribute('codigo', ultimaCategoria);
										input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover essa categoria?')) {ExcluirCategoria(this.getAttribute('codigo'));}");
										
									td.appendChild(input);
									tr.appendChild(td);
									
									categorias.appendChild(tr);
									
									this.value = '';
								}
								else
								{
									alert('Categoria já adicionada!');
								}
								
							}
							
							this.disabled = false;
							return true;
						}
						
						function AlterarCategoria(codigo, nome)
						{
							if (alterarCategorias == '')
							{
								alterarCategorias = alterarCategorias + codigo + ';' + nome;
							}
							else
							{
								alterarCategorias = alterarCategorias + '{}' + codigo + ';' + nome;
							}
						}
						
						function ExcluirCategoria(codigo)
						{							
							var aux = adicionarCategorias.split('{}'); 
							var foi = 0;
							
							adicionarCategorias = '';
							
							for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
							{
								if (aux[cont].split(';')[0] == codigo)
								{
									aux.splice(cont, 1);
									foi = 1;
								}
								
								if (aux.length != 0)
								{
									if (adicionarCategorias == '')
									{
										adicionarCategorias = adicionarCategorias + aux[cont];
									}									
									else if (cont <= aux.length - 1)
									{
										adicionarCategorias = adicionarCategorias + '{}' + aux[cont];
									}
								}
								else
								{
									adicionarCategorias = '';
								}
							}
							
							if (foi == 0)
							{
								if (excluirCategorias == '')
								{
									excluirCategorias = excluirCategorias + codigo;
								}
								else
								{
									excluirCategorias = excluirCategorias + '{}' + codigo;
								}
							}
							
							aux = alterarCategorias.split('{}');
							
							alterarCategorias = '';
							
							for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
							{
								if (aux[cont].split(';')[0] == codigo)
								{
									aux.splice(cont, 1);
									
									if (cont != 0)
									{
										cont = cont - 1;
									}
								}
								else
								{
									if (alterarCategorias == '')
									{
										alterarCategorias = alterarCategorias + aux[cont];
									}
									else if (cont <= aux.length - 1)
									{
										alterarCategorias = alterarCategorias + '{}' + aux[cont];
									}
								}
							}
							
							eval('categoria' + codigo + '.remove();'); 
						}
						
						function FinalizarCategorias()
						{
							lbl_avisoCategorias.style.display = 'none';
							btn_finalizarCategorias.disabled = true;
							
							Ajax("GET", "php/FinalizarCategorias.php", "adicionar=" + adicionarCategorias + "&alterar=" + alterarCategorias + "&excluir=" + excluirCategorias, "btn_finalizarCategorias.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();} else {lbl_avisoCategorias.textContent = 'Ocorreu um erro durante o processo: ' + retorno; lbl_avisoCategorias.style.display = 'inline-block';}");
						}
					</script>
				</div>
				
				<div class="azli-window-form" id="dados_marca">
					<form>
						<table width="80%" id="marcas">
							<script> ultimaMarca = 0; </script>
							<tr>
								<td colspan="2">
									<input type="text" id="txt_nomeMarcaCadastrar" class="azli-input at-valida" placeholder="Insira o nome da nova marca" tipo="Texto">
								</td>
							<tr>
							
							<?php
								$result_Marca = $conexaoPrincipal -> Query("select cd_marca, nm_marca, ic_editavel from tb_marca order by nm_marca");
								$linha_Marca = mysqli_fetch_assoc($result_Marca);
								$total_Marca = mysqli_num_rows($result_Marca);
								
								if ($total_Marca > 0)
								{
									do
									{
							?>
										<tr class="marca cadastrado" id="marca<?php echo $linha_Marca['cd_marca']; ?>">
											<td width="90%" colspan="<?php if ($linha_Marca['ic_editavel'] == 0) {echo 2;} else {echo 1;} ?>">
												<input type="text" class="azli-input func-at" value="<?php echo $linha_Marca['nm_marca']; ?>" codigo="<?php echo $linha_Marca['cd_marca']; ?>" onfocus="this.setAttribute('nomeAnterior', this.value.trim());" onblur="if (this.value.trim() != '') {AlterarMarca(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}" <?php if ($linha_Marca['ic_editavel'] == 0) {echo 'disabled';} ?>/>
											</td>
											<?php
												if ($linha_Marca['ic_editavel'] == 1)
												{
											?>
													<td width="10%">
														<input type="button" class="func-del" value="Remover" codigo="<?php echo $linha_Marca['cd_marca']; ?>" onclick="if (confirm('Tem certeza que deseja remover essa marca?')) {ExcluirMarca(this.getAttribute('codigo'));}"/>
													</td>
											<?php
												}
											?>
										</tr>
										<script> if (<?php echo $linha_Marca['cd_marca']; ?> > ultimaMarca) {ultimaMarca = <?php echo $linha_Marca['cd_marca']; ?>;} </script>
							<?php
									}
									while ($linha_Marca = mysqli_fetch_assoc($result_Marca));
								}
							?>
						</table>
					</form>
					
					<script>
						adicionarMarcas = '';
						alterarMarcas = '';
						excluirMarcas = '';
					
						txt_nomeMarcaCadastrar.onkeyup = function()
						{
							var cont, aux, foi;
							
							if (event.keyCode == 13 && this.value.trim() != '' && this.disabled == false) 
							{
								this.disabled = true;
								this.value = this.value.trim();
								
								if (adicionarMarcas == '')
								{
									ultimaMarca = ultimaMarca + 1;
									
									adicionarMarcas = ultimaMarca + ';' + this.value;
									
									foi = 1;
								}
								else
								{
									aux = adicionarMarcas.split('{}');
									
									for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
									{
										if (aux[cont].split(';')[1].toString().toLowerCase() === this.value.toString().toLowerCase())
										{
											foi = 0;
											break;
										}
										else
										{
											foi = 1;
										}
									}
									
									if (foi == 1)
									{
										ultimaMarca = ultimaMarca + 1;
									
										adicionarMarcas = adicionarMarcas + '{}' + ultimaMarca + ';' + this.value;
									}
								}
								
								if (foi == 1)
								{
									var tr = document.createElement('tr');
										$(tr).addClass('marca');
										tr.id = 'marca' + ultimaMarca;
										
									var td = document.createElement('td');
										td.setAttribute('width', '90%');
										
									var input = document.createElement('input');
										input.type = 'text';
										input.setAttribute('codigo', ultimaMarca);
										//input.setAttribute('tipo', 'Texto');
										$(input).addClass('azli-input');
										$(input).addClass('func-at');
										//$(input).addClass('at-valida');
										input.value = this.value;
										input.setAttribute('onfocus', "this.setAttribute('nomeAnterior', this.value.trim());");
										input.setAttribute('onblur', "if (this.value.trim() != '') {AlterarMarca(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}");
										
									td.appendChild(input);
									tr.appendChild(td);
									
									var td = document.createElement('td');
										td.setAttribute('width', '90%');
									
									var input = document.createElement('input');
										input.type = 'button';
										$(input).addClass('func-del');
										input.value = "Remover";
										input.setAttribute('codigo', ultimaCategoria);
										input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover essa marca?')) {ExcluirMarca(this.getAttribute('codigo'));}");
										
									td.appendChild(input);
									tr.appendChild(td);
									
									marcas.appendChild(tr);
									
									this.value = '';
								}
								else
								{
									alert('Marca já adicionada!');
								}
								
							}
							
							this.disabled = false;
							return true;
						}
						
						function AlterarMarca(codigo, nome)
						{
							if (alterarMarcas == '')
							{
								alterarMarcas = alterarMarcas + codigo + ';' + nome;
							}
							else
							{
								alterarMarcas = alterarMarcas + '{}' + codigo + ';' + nome;
							}
						}

						function ExcluirMarca(codigo)
						{							
							var aux = adicionarMarcas.split('{}'); 
							var foi = 0;
							
							adicionarMarcas = '';
							
							for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
							{
								if (aux[cont].split(';')[0] == codigo)
								{
									aux.splice(cont, 1);
									foi = 1;
								}
								
								if (aux.length != 0)
								{
									if (adicionarMarcas == '')
									{
										adicionarMarcas = adicionarMarcas + aux[cont];
									}									
									else if (cont <= aux.length - 1)
									{
										adicionarMarcas = adicionarMarcas + '{}' + aux[cont];
									}
								}
								else
								{
									adicionarMarcas = '';
								}
							}
							
							if (foi == 0)
							{
								if (excluirMarcas == '')
								{
									excluirMarcas = excluirMarcas + codigo;
								}
								else
								{
									excluirMarcas = excluirMarcas + '{}' + codigo;
								}
							}
							
							aux = alterarMarcas.split('{}');
							
							alterarMarcas = '';
							
							for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
							{
								if (aux[cont].split(';')[0] == codigo)
								{
									aux.splice(cont, 1);
									
									if (cont != 0)
									{
										cont = cont - 1;
									}
								}
								else
								{
									if (alterarMarcas == '')
									{
										alterarMarcas = alterarMarcas + aux[cont];
									}
									else if (cont <= aux.length - 1)
									{
										alterarMarcas = alterarMarcas + '{}' + aux[cont];
									}
								}
							}
							
							eval('marca' + codigo + '.remove();'); 
						}
						
						function FinalizarMarcas()
						{
							lbl_avisoMarcas.style.display = 'none';
							btn_finalizarMarcas.disabled = true;
							
							Ajax("GET", "php/FinalizarMarcas.php", "adicionar=" + adicionarMarcas + "&alterar=" + alterarMarcas + "&excluir=" + excluirMarcas, "btn_finalizarCategorias.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();} else {lbl_avisoCategorias.textContent = 'Ocorreu um erro durante o processo: ' + retorno; lbl_avisoCategorias.style.display = 'inline-block';}");
						}
					</script>
					
					<table width="80%">
						<tr>
							<td width="50%">
								<label id="lbl_avisoMarcas" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
							</td>
							
							<td align="right" width="50%">
								<input type="button" class="azli-button cancel-btn" value="Cancelar" onclick="window.location.reload();"/>
								<input type="button" id="btn_finalizarMarcas" class="azli-button" value="Finalizar" onclick="FinalizarMarcas();"/>
							</td>
						</tr>
					</table>
				</div>
				
				<div class="azli-window-form" id="dados_modelo">
					<form id="Frm_PesquisarModelos" method="POST" action="php/BuscarModelos.php">
						<table width="80%">
							<tr>
								<td colspan="2">
									<label class="azli-label">Novo modelo</label>
									<input type="text" id="txt_nomeModeloCadastrar" class="azli-input at-valida" tipo="Texto" placeholder="Insira o nome da novo modelo">
								</td>
							<tr>
							<tr>	
								<td colspan="1">
									<label class="azli-label">Marca</label>
									<select class="azli-select" id="cmb_marcaPesquisa" name="marca" required>
										<option value="">Selecione a marca do modelo</option>
										<?php
											$result_Marca = $conexaoPrincipal -> Query("select cd_marca, nm_marca from tb_marca order by nm_marca");
											$linha_Marca = mysqli_fetch_assoc($result_Marca);
											$total_Marca = mysqli_num_rows($result_Marca);	
											
											if ($total_Marca > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Marca['cd_marca']; ?>"><?php echo $linha_Marca['nm_marca']; ?></option>
										<?php
												}
												while ($linha_Marca = mysqli_fetch_assoc($result_Marca));
											}
										?>
									</select>
								</td>
								<td colspan="1">
									<label class="azli-label">Categoria</label>
									<select class="azli-select" name="categoria" id="cmb_categoriaPesquisa" required>
										<option value="">Selecione a categoria do modelo</option>
										<?php
											$result_Categoria = $conexaoPrincipal -> Query("select cd_categoria, nm_categoria from tb_categoria order by nm_categoria");
											$linha_Categoria = mysqli_fetch_assoc($result_Categoria);
											$total_Categoria = mysqli_num_rows($result_Categoria);	
											
											if ($total_Categoria > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Categoria['cd_categoria']; ?>"><?php echo $linha_Categoria['nm_categoria']; ?></option>
										<?php
												}
												while ($linha_Categoria = mysqli_fetch_assoc($result_Categoria));
											}
										?>
									</select>
								</td>
								<script>
									// ---------------------------------
									adicionarModelos = '';
									alterarModelos = '';
									excluirModelos = '';
									ultimoModelo = 0;
								
									txt_nomeModeloCadastrar.onkeyup = function()
									{
										var cont, aux, foi;
										
										if (event.keyCode == 13 && this.value.trim() != '' && this.disabled == false && cmb_marcaPesquisa.value != '' && cmb_categoriaPesquisa.value != '' && ultimoModelo != '') 
										{
											this.disabled = true;
											this.value = this.value.trim();
											
											if (adicionarModelos == '')
											{
												ultimoModelo = ultimoModelo + 1;
												
												adicionarModelos = ultimoModelo + ';' + this.value + ';' + cmb_marcaPesquisa.value + ';' + cmb_categoriaPesquisa.value;
												
												foi = 1;
											}
											else
											{
												aux = adicionarModelos.split('{}');
												
												for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
												{
													if (aux[cont].split(';')[1].toString().toLowerCase() === this.value.toString().toLowerCase())
													{
														foi = 0;
														break;
													}
													else
													{
														foi = 1;
													}
												}
												
												if (foi == 1)
												{
													ultimoModelo = ultimoModelo + 1;
												
													adicionarModelos = adicionarModelos + '{}' + ultimoModelo + ';' + this.value + ';' + cmb_marcaPesquisa.value + ';' + cmb_categoriaPesquisa.value;
												}
											}
											
											if (foi == 1)
											{
												var tr = document.createElement('tr');
													$(tr).addClass('modelo');
													tr.id = 'modelo' + ultimoModelo;
													
												var td = document.createElement('td');
													td.setAttribute('width', '90%');
													
												var input = document.createElement('input');
													input.type = 'text';
													input.setAttribute('codigo', ultimoModelo);
													//input.setAttribute('tipo', 'Texto');
													$(input).addClass('azli-input');
													$(input).addClass('func-at');
													//$(input).addClass('at-valida');
													input.value = this.value;
													input.setAttribute('onfocus', "this.setAttribute('nomeAnterior', this.value.trim());");
													input.setAttribute('onblur', "if (this.value.trim() != '') {AlterarModelo(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}");
													
												td.appendChild(input);
												tr.appendChild(td);
												
												var td = document.createElement('td');
													td.setAttribute('width', '90%');
												
												var input = document.createElement('input');
													input.type = 'button';
													$(input).addClass('func-del');
													input.value = "Remover";
													input.setAttribute('codigo', ultimoModelo);
													input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover esse modelo?')) {ExcluirModelo(this.getAttribute('codigo'));}");
													
												td.appendChild(input);
												tr.appendChild(td);
												
												modelos_pendentes.appendChild(tr);
												
												this.value = '';
											}
											else
											{
												alert('Modelo já adicionado!');
											}
											
										}
										
										this.disabled = false;
										return true;
									}
									
									function AlterarModelo(codigo, nome)
									{
										if (alterarModelos == '')
										{
											alterarModelos = alterarModelos + codigo + ';' + nome;
										}
										else
										{
											alterarModelos = alterarModelos + '{}' + codigo + ';' + nome;
										}
									}

									function ExcluirModelo(codigo)
									{							
										var aux = adicionarModelos.split('{}'); 
										var foi = 0;
										
										adicionarModelos = '';
										
										for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
										{
											if (aux[cont].split(';')[0] == codigo)
											{
												aux.splice(cont, 1);
												foi = 1;
											}
											
											if (aux.length != 0)
											{
												if (adicionarModelos == '')
												{
													adicionarModelos = adicionarModelos + aux[cont];
												}									
												else if (cont <= aux.length - 1)
												{
													adicionarModelos = adicionarModelos + '{}' + aux[cont];
												}
											}
											else
											{
												adicionarModelos = '';
											}
										}
										
										if (foi == 0)
										{
											if (excluirModelos == '')
											{
												excluirModelos = excluirModelos + codigo;
											}
											else
											{
												excluirModelos = excluirModelos + '{}' + codigo;
											}
										}
										
										aux = alterarModelos.split('{}');
										
										alterarModelos = '';
										
										for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
										{
											if (aux[cont].split(';')[0] == codigo)
											{
												aux.splice(cont, 1);
												
												if (cont != 0)
												{
													cont = cont - 1;
												}
											}
											else
											{
												if (alterarModelos == '')
												{
													alterarModelos = alterarModelos + aux[cont];
												}
												else if (cont <= aux.length - 1)
												{
													alterarModelos = alterarModelos + '{}' + aux[cont];
												}
											}
										}
										
										eval('modelo' + codigo + '.remove();'); 
									}
									
									function FinalizarModelos()
									{
										lbl_avisoModelos.style.display = 'none';
										btn_finalizarModelos.disabled = true;
										
										Ajax("GET", "php/FinalizarModelos.php", "adicionar=" + adicionarModelos + "&alterar=" + alterarModelos + "&excluir=" + excluirModelos, "btn_finalizarModelos.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();} else {lbl_avisoModelos.textContent = 'Ocorreu um erro durante o processo: ' + retorno; lbl_avisoModelos.style.display = 'inline-block';}");
									}
									// ---------------------------------
									
									Frm_PesquisarModelos.onsubmit = function()
									{
										resultado_modelos.innerHTML = '';
										
										if (VerificarForm(this))
										{
											AjaxForm(this, "cmb_marcaPesquisa.readOnly = true; cmb_categoriaPesquisa.readOnly = true;", "cmb_marcaPesquisa.readOnly = false; cmb_categoriaPesquisa.readOnly = false; var retorno = this.responseText; resultado_modelos.innerHTML = retorno.split(';-;')[0].trim(); if (parseInt(retorno.split(';-;')[1].toString().trim()) > ultimoModelo) {ultimoModelo = parseInt(retorno.split(';-;')[1].toString().trim());}");
											
											modelos_pendentes.innerHTML = '';
											
											var cont;
											var aux = adicionarModelos.split('{}');
											
											for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
											{
												if (aux[cont].indexOf(';') != -1 && aux[cont].split(';')[2].toString().toLowerCase() == cmb_marcaPesquisa.value && aux[cont].split(';')[3].toString().toLowerCase() == cmb_categoriaPesquisa.value)
												{
													var codigo = aux[cont].split(';')[0];
													var nome = aux[cont].split(';')[1];
													var marca = aux[cont].split(';')[2];
													var categoria = aux[cont].split(';')[3];
													
													var cont2;
													var aux2 = alterarModelos.split('{}');
													
													for (cont2 = 0; cont2 <= aux2.length - 1; cont2 = cont2 + 1)
													{
														if (aux2[cont2].indexOf(';') != -1 && aux2[cont2].split(';')[0].toString().toLowerCase() == codigo)
														{
															nome = aux2[cont2].split(';')[1]
														}
													}
													
													var tr = document.createElement('tr');
														$(tr).addClass('modelo');
														tr.id = 'modelo' + codigo;
														
													var td = document.createElement('td');
														td.setAttribute('width', '90%');
														
													var input = document.createElement('input');
														input.type = 'text';
														input.setAttribute('codigo', codigo);
														//input.setAttribute('tipo', 'Texto');
														$(input).addClass('azli-input');
														$(input).addClass('func-at');
														//$(input).addClass('at-valida');
														input.value = nome;
														input.setAttribute('onfocus', "this.setAttribute('nomeAnterior', this.value.trim());");
														input.setAttribute('onblur', "if (this.value.trim() != '') {AlterarModelo(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}");
														
													td.appendChild(input);
													tr.appendChild(td);
													
													var td = document.createElement('td');
														td.setAttribute('width', '90%');
													
													var input = document.createElement('input');
														input.type = 'button';
														$(input).addClass('func-del');
														input.value = "Remover";
														input.setAttribute('codigo', codigo);
														input.setAttribute('onclick', "if (confirm('Tem certeza que deseja remover esse modelo?')) {ExcluirModelo(this.getAttribute('codigo'));}");
														
													td.appendChild(input);
													tr.appendChild(td);
													
													modelos_pendentes.appendChild(tr);
												}
											}
										}
										
										return false;
									}
									
									cmb_marcaPesquisa.onchange = function()
									{
										btn_pesquisarModelos.click();
									}
									
									cmb_categoriaPesquisa.onchange = function()
									{
										btn_pesquisarModelos.click();
									}
								</script>
							</tr>
						</table>
						<input type="submit" id="btn_pesquisarModelos" style="display: none;"/>
					</form>
					
					<script>
					</script>
					
					<table width="80%" id="resultado_modelos">					
					</table>
					<table width="80%" id="modelos_pendentes">					
					</table>
					
					<table width="80%">
						<tr>
							<td width="50%">
								<label id="lbl_avisoModelos" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
							</td>
							
							<td align="right" width="50%">
								<input type="button" id="btn_finalizarModelos" class="azli-button" value="Finalizar" onclick="FinalizarModelos();"/>
							</td>
						</tr>
					</table>
				</div>
				
				<div class="azli-window-form" id="dados_lista_aparelho">
					<table width="80%">
						<tr>	
							<td colspan="2">
								<label class="azli-label">Categoria</label>
								<select class="azli-select">
									<option value="0">Selecione a categoria</option>
								<select>
							</td>
						</tr>
						
						<tr>
							<td>
								<input type="button" class="azli-input func-at list" value="Nokia Lumia 950 (001)">
							</td>
						</tr>
						
					</table>
					<table width="80%">
						<tr>
							<td width="50%">
								
							</td>
							
							<td align="right" width="50%">
								<input type="button" class="azli-button" value="Finalizar">
							</td>
						</tr>
					</table>
				</div>
				
				<div class="azli-window-form" id="dados_lista_cliente">
					<table width="80%">
						<tr>
							<td colspan="2">
								<label class="azli-label">Clientes</label>
							</td>
						<tr>
						
						<?php
							$result_Cliente = $conexaoPrincipal -> Query("select cd_cliente, nm_cliente from tb_cliente order by nm_cliente");
							$linha_Cliente = mysqli_fetch_assoc($result_Cliente);
							$total_Cliente = mysqli_num_rows($result_Cliente);
							
							if ($total_Cliente > 0)
							{
								do
								{
						?>
									<tr id="cliente<?php echo $linha_Cliente['cd_cliente']; ?>">
										<td width="90%">
											<input type="button" id="lbl_nomeCliente<?php echo $linha_Cliente['cd_cliente']; ?>" class="azli-input func-at list" value="<?php echo $linha_Cliente['nm_cliente']; ?>">
										</td>
										<td width="10%">
											<input type="button" class="func-del" value="Editar" onclick="BuscarClienteAlterar('<?php echo $linha_Cliente['cd_cliente']; ?>', codigoOrdem);">
										</td>
										<td width="10%">
											<input type="button" class="func-del" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse cliente?')) {this.disabled = true; ExcluirCliente('<?php echo $linha_Cliente['cd_cliente']; ?>', this);}">
										</td>
									</tr>
						<?php
								}
								while ($linha_Cliente = mysqli_fetch_assoc($result_Cliente));
							}
						?>
					</table>
					
					<script>
						alterarClientes = '';
						excluirClientes = '';
						
						function AlterarCliente(retorno)
						{
							if (alterarClientes == '')
							{
								alterarClientes = alterarClientes + retorno;
							}
							else
							{
								var aux = alterarClientes.split('{}');
								var cont, index = -1;
								
								alterarClientes = '';
								
								for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
								{
									index = cont;
									
									if (aux[cont].split(';-;')[0].toString().toLowerCase().trim() == retorno.split(';-;')[0].toString().toLowerCase().trim())
									{
										if (alterarClientes != '')
										{
											alterarClientes = alterarClientes + '{}' + retorno;
										}
										else
										{
											alterarClientes = alterarClientes + retorno;
										}
									}
									else
									{
										if (alterarClientes != '')
										{
											alterarClientes = alterarClientes + '{}' + aux[cont];
										}
										else
										{
											alterarClientes = alterarClientes + aux[cont];
										}
									}
								}
								
								if (index != -1 && aux[index].indexOf(retorno.split(';-;')[0].toString().toLowerCase().trim() + ';-;') == -1)
								{
									if (alterarClientes != '')
									{
										alterarClientes = alterarClientes + '{}' + retorno;
									}
									else
									{
										alterarClientes = alterarClientes + retorno;
									}
								}							
							}
							
							eval("lbl_nomeCliente" + retorno.split(';-;')[0].toString().trim() + ".value = retorno.split(';-;')[1].toString().trim();");
						}
						
						function ExcluirCliente(codigo, botao)
						{
							var aux = ''; 
							var foi = 0;
							
							if (foi == 0)
							{
								if (excluirClientes == '')
								{
									excluirClientes = excluirClientes + codigo;
								}
								else
								{
									excluirClientes = excluirClientes + '{}' + codigo;
								}
							}
							
							aux = alterarClientes.split('{}');
							
							alterarClientes = '';
							
							for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
							{
								if (aux[cont].split(';-;')[0] == codigo)
								{
									aux.splice(cont, 1);
									
									if (cont != 0)
									{
										cont = cont - 1;
									}
								}
								else
								{
									if (alterarClientes == '')
									{
										alterarClientes = alterarClientes + aux[cont];
									}
									else if (cont <= aux.length - 1)
									{
										alterarClientes = alterarClientes + '{}' + aux[cont];
									}
								}
							}
							
							eval('cliente' + codigo + '.remove();');
						}
						
						function FinalizarClientes()
						{
							lbl_avisoClientes.style.display = 'none';
							btn_finalizarClientes.disabled = true;
							
							Ajax("GET", "php/FinalizarClientes.php", "alterar=" + alterarClientes + "&excluir=" + excluirClientes, "btn_finalizarClientes.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();} else {lbl_avisoClientes.textContent = 'Ocorreu um erro durante o processo: ' + retorno; lbl_avisoClientes.style.display = 'inline-block';}");
						}
					</script>
					
					<table width="80%">
						<tr>
							<td width="50%">
								<label id="lbl_avisoClientes" class="azli-label" style="color: red; display: none;">Aviso Teste</label>
							</td>
							
							<td align="right" width="50%">
								<input type="button" id="btn_finalizarClientes" class="azli-button" value="Finalizar" onclick="FinalizarClientes();">
							</td>
						</tr>
					</table>
				</div>
				<!--<div class="azli-window">
				
				</div>-->
				
				<div class="azli-window-form" id="dados_lista_os_ap">
					<form id="Frm_CadastroAparelho" method="POST" action="php/CadastrarAparelho.php">
						<table width="80%" id="aparelhosOrdem">
							<tr>
								<td colspan="3">
									<label class="azli-label">Aparelhos nessa ordem de serviço (<label id="qt_aparelhosOrdem"><?php echo $total_AparelhosOrdem; ?></label>)</label>
								</td>
							</tr>
							<?php
								if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
								{
									if ($total_AparelhosOrdem > 0)
									{
										do
										{
							?>
											<tr>
												<td width="80%">
													<input type="button" class="azli-input func-at list" value="<?php echo $linha_AparelhosOrdem['nm_modelo'].' - '.(($linha_AparelhosOrdem['cd_imei'] != '') ? $linha_AparelhosOrdem['cd_imei'].(($linha_AparelhosOrdem['cd_imei_secundario'] != '') ? ' / '.$linha_AparelhosOrdem['cd_imei_secundario'] : '') : (($linha_AparelhosOrdem['cd_numero_serie'] != '') ? $linha_AparelhosOrdem['cd_numero_serie'] : '')); ?>">
												</td>
												<td width="10%">
													<input type="button" class="func-del" value="Editar" onclick="BuscarAparelho('<?php echo $linha_AparelhosOrdem['cd_imei']; ?>', '<?php echo $linha_AparelhosOrdem['cd_imei_secundario']; ?>', '<?php echo $linha_AparelhosOrdem['cd_numero_serie']; ?>', codigoOrdem); btn_cadastrarAparelho.value = 'Alterar'; /*txt_IMEI.readOnly = true;*/ btn_novoAparelho.style.display = 'none'; btn_buscarAparelho.style.display = 'none';">
												</td>
												<td width="10%">
													<input type="button" class="func-del" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse aparelho?')) {RemoverAparelho('<?php echo $linha_AparelhosOrdem['cd_aparelho']; ?>', codigoOrdem);}"/>
												</td>
											</tr>
							<?php
										}
										while ($linha_AparelhosOrdem = mysqli_fetch_assoc($result_AparelhosOrdem));
									}
								}
								else
								{
									$total_AparelhosOrdem = 0;
								}
							?>						
						</table>
						<input type="hidden" name="etapaOrdem" value="2"/>
						<table width="80%">
							<tr>
								<td width="50%">
									
								</td>
								
								<td align="right" width="50%">
									<input type="button" class="azli-button cancel-btn" value="Voltar" onclick="if (codigoOrdem == '') {txt_nomeClienteCadastro.focus(); enterWin('#dados_cliente');} else {SairAlterarAparelhosOrdem();}"/>
									<input type="button" class="azli-button cancel-btn" id="btn_adicionarAparelho" value="Adicionar Aparelho" onclick="cmb_categoria.focus(); enterWin('#dados_aparelho'); btn_cadastrarAparelho.value = 'Adicionar'; txt_IMEI.readOnly = false; cmb_categoria.disabled = false; btn_novoAparelho.style.display = 'inline-block'; btn_buscarAparelho.style.display = 'inline-block'; btn_buscarAparelho.click(); txt_codigoOrdemAparelho.value = codigoOrdem;"/>
									<input type="submit" id="btn_cadastrarAparelhos" class="azli-button" value="Avançar"/>
								</td>
							</tr>
						</table>
					</form>
					<script>
						Frm_CadastroAparelho.onsubmit = function()
						{
							if (qt_aparelhosOrdem.textContent > 0)
							{
								if (codigoOrdem == '')
								{
									AjaxForm(this, "btn_cadastrarAparelhos.disabled = true;", "btn_cadastrarAparelhos.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0].trim(); if (indicador == 1) {etapaOrdem = retorno.split(';-;')[2].trim(); EtapaOrdemServico(etapaOrdem);} else {}");
								}
								else
								{
									SairAlterarAparelhosOrdem();
								}
							}
							else
							{
								alert('Adicione algum aparelho para avançar essa etapa.');
							}
							
							return false;
						}
					
						function BuscarAparelhosOrdem(codigo)
						{
							if (codigo == '')
							{
								btn_cadastrarAparelhos.value = 'Avançar';
							}
							else
							{
								btn_cadastrarAparelhos.value = 'Finalizar';
							}
							
							Ajax("GET", "php/BuscarAparelhosOrdem.php", "codigoOrdem=" + codigo, "var retorno = this.responseText; aparelhosOrdem.innerHTML = retorno.split(';-;')[0].trim(); qt_aparelhosOrdem.textContent = retorno.split(';-;')[1].trim(); if (voltarAparelho == 1 || codigoOrdem != '') {btn_adicionarAparelho.focus(); enterWin('#dados_lista_os_ap'); voltarAparelho = 0;} setTimeout('Frm_AdicionarAparelho.reset();', 1500);");
						}
						
						function RemoverAparelho(codigoAparelho, codigo)
						{
							Ajax("GET", "php/RemoverAparelho.php", "codigoAparelho=" + codigoAparelho + "&codigoOrdem=" + codigo, "var retorno = this.responseText; if (retorno == 1) {BuscarAparelhosOrdem(codigoOrdem);}");
						}
					</script>
				</div>
				<!--<div class="azli-window">
				
				</div>-->
			</div>
			<div class="azli-bottom" align="center">
				<table height="100%">
					<tr>
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#azli_win_os')">
								<image src="images/home.png" class="azli-option-image"><br>
								<label class="azli-option-label">Home</label>
							</div>
						</td>
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_os_list')">
								<image src="images/ordem.png" class="azli-option-image"><br>
								<label class="azli-option-label">Ordens de Serviço</label>
							</div>
						</td>
						
						<!--
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_lista_aparelho')">
								<image src="images/aparelho.png" class="azli-option-image"><br>
								<label class="azli-option-label">Aparelhos</label>
							</div>
						</td>
						-->
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_funcionario')">
								<image src="images/funcionario.png" class="azli-option-image"><br>
								<label class="azli-option-label">Funcionários</label>
							</div>
						</td>
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_fornecedor')">
								<image src="images/fornecedor.png" class="azli-option-image"><br>
								<label class="azli-option-label">Fornecedores</label>
							</div>
						</td>
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_lista_cliente')">
								<image src="images/cliente.png" class="azli-option-image"><br>
								<label class="azli-option-label">Clientes</label>
							</div>
						</td>
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_categoria')">
								<image src="images/categoria.png" class="azli-option-image"><br>
								<label class="azli-option-label">Categorias</label>
							</div>
						</td>
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_marca')">
								<image src="images/marca.png" class="azli-option-image"><br>
								<label class="azli-option-label">Marcas</label>
							</div>
						</td>
						
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="enterWin('#dados_modelo')">
								<image src="images/modelo.png" class="azli-option-image"><br>
								<label class="azli-option-label">Modelos</label>
							</div>
						</td>
						
						<!--
						<td valign="center">
							<div class="azli-option" align="center">
								<image src="images/servicos.png" class="azli-option-image"><br>
								<label class="azli-option-label">Serviços</label>
							</div>
						</td>
						-->
						
						<td valign="center">
							<div class="azli-option" align="center" onclick="Logout();" id="btn_logout">
								<image src="images/sair.png" class="azli-option-image"><br>
								<label class="azli-option-label">Sair</label>
							</div>
						</td>
					</tr>
				</table>				
			</div>
			
			
			<input type="button" value="▼" class="azli-display" id="azli_display_button" onclick="displayMenu(this.value)">
		</div>
	</body>
	<script src="js/ajax.js"></script>
	<script src="js/jquery.min.js"></script>
	<script>
		window.onload = function()
		{
			valAt = "";
			clientePesquisado = '';
			MapearForms();
			VerificarForm(Frm_CadastroCliente);
			etapaOrdem = '<?php echo $etapaOrdem; ?>';
			VerificarProcessoOrdem('<?php echo $etapaOrdem; ?>');
			
			codigoCliente = '<?php echo $codigoClienteOrdem; ?>';
			codigoAparelho = '<?php echo $codigoAparelhoOrdem; ?>';
			
			voltarAparelho = 0;
		}
		
		function VerificarProcessoOrdem(etapa)
		{
			if (etapa == 1)
			{
				lbl_novaOrdem.textContent = 'Nova Ordem de Serviço';
			}
			else
			{
				lbl_novaOrdem.textContent = 'Continuar Ordem de Serviço';
			}
		}
		
		function displayMenu(valor)
		{
			if( valor == "▼")
			{
				$('.azli-bottom').slideUp('slow');
				azli_display_button.value = "▲";
				$('.azli-medium').css('height','90%');
			}
			else
			{
				$('.azli-bottom').slideDown('slow');
				azli_display_button.value = "▼";
				$('.azli-medium').css('height','73%');
			}
		}
		
		function enterWin(valNM)
		{
			if(valAt != valNM)
			{
				$('.azli-window-form').fadeOut('slow');
				$(valNM).fadeIn('slow');	
			}
			valAt = valNM;
		}
		
		function EtapaOrdemServico(etapa)
		{
			if (etapa == 1)
			{
				txt_nomeClienteCadastro.focus();
				enterWin('#dados_cliente');
			}
			else if (etapa == 2)
			{
				btn_adicionarAparelho.focus();
				enterWin('#dados_lista_os_ap');
				//enterWin('#dados_aparelho');
			}
			else if (etapa == 3)
			{
				enterWin('#dados_servico');
			}
		}
		
		function CancelarOrdem()
		{
			window.location.reload();
		}
		
		function Logout()
		{
			btn_logout.disabled = true;
			Ajax("GET", "php/Logout.php", "", "btn_logout.disabled = false; var retorno = this.responseText; if (retorno == 1) {window.location.reload();}");
		}
		
		/*azli-window-form .at-valida').focus(function() {
			var ele = $(this);
			$('.azli-window-form').animate({
				scrollTop: ele.offset().top - 80
			}, 200);
		});*/
	</script>
</html>	