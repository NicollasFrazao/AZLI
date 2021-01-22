<?php
	include 'php/Conexao.php';
	include 'php/ConexaoPrincipal.php';
	
	include 'pdf/mpdf.php';
	
	if (!isset($_GET['codigo']))
	{
		exit;
	}
	
	function ConfereQuebra()
	{
		switch (strtoupper(substr(PHP_OS, 0, 3))) 
		{
			// Windows
			case 'WIN':
			{
				$quebra = "\r\n";
			}
			break;

			// Mac
			case 'DAR':
			{
				$quebra = "\r";
			}
			break;

			// Unix
			default:
			{
				$quebra = "\n";
			}
		}
		
		return $quebra;
	}
	
	$codigo = mysql_escape_string($_GET['codigo']);
	
	$result_Cliente = $conexaoPrincipal -> Query("select tb_cliente.nm_cliente,
														   tb_cliente.cd_rg,
														   tb_cliente.cd_cpf,
														   tb_cliente.cd_telefone1,
														   tb_cliente.cd_telefone2,
														   tb_cliente.nm_endereco,
														   tb_cliente.ds_referencias
													  from tb_os inner join tb_cliente
														on tb_os.cd_cliente = tb_cliente.cd_cliente
														  where tb_os.cd_verificador = '$codigo'");
	$linha_Cliente = mysqli_fetch_assoc($result_Cliente);
	$total_Cliente = mysqli_num_rows($result_Cliente);
	
	$query_Aparelhos = "select concat(tb_marca.nm_marca, ' ', tb_modelo.nm_modelo) as nm_marca_modelo,
							   tb_aparelho.cd_imei,
							   tb_categoria.nm_categoria,
							   item_os.ds_item_os
						  from tb_os inner join item_os
							on tb_os.cd_os = item_os.cd_os
							  inner join tb_aparelho
								on item_os.cd_aparelho = tb_aparelho.cd_aparelho
								  inner join tb_modelo
									on tb_aparelho.cd_modelo = tb_modelo.cd_modelo
									  inner join tb_marca
										on tb_modelo.cd_marca = tb_marca.cd_marca
										  inner join tb_categoria
											on tb_modelo.cd_categoria = tb_categoria.cd_categoria
							where tb_os.cd_verificador = '$codigo'
								order by nm_marca_modelo";
	$result_Aparelhos = $conexaoPrincipal -> Query($query_Aparelhos);
	$linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos);
	$total_Aparelhos = mysqli_num_rows($result_Aparelhos);
	
	$result_Datas = $conexaoPrincipal -> Query("select tb_os.dt_orcamento,
														   tb_os.dt_inicio,
														   tb_os.dt_finalizacao,
														   tb_os.dt_expiracao,
														   tb_os.dt_retirada
													  from tb_os
														where tb_os.cd_verificador = '$codigo'");
	$linha_Datas = mysqli_fetch_assoc($result_Datas);
	$total_Datas = mysqli_num_rows($result_Datas);
	
	//<link rel="stylesheet" type="text/css" href="css/styleModeloOrdem.css">
	
	$html = '<html>
	<body>
		<div class="ordem fica">
			<label class="doctitle">Fora de Garantia</label>
			<div class="data-client">
				<label class="data-title">Dados do Cliente</label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Nome</label><br>
							<label class="lbl-item">'.$linha_Cliente['nm_cliente'].'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">RG</label><br>
							<label class="lbl-item">';
	
	$aux = $linha_Cliente['cd_rg']; 
	$aux = $aux[0].$aux[1].'.'.$aux[2].$aux[3].$aux[4].'.'.$aux[5].$aux[6].$aux[7].'-'.$aux[8]; 
	
		$html = $html.$aux.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">CPF</label><br>
							<label class="lbl-item">';
	
	$aux = $linha_Cliente['cd_cpf']; 
	$aux = $aux[0].$aux[1].$aux[2].'.'.$aux[3].$aux[4].$aux[5].'.'.$aux[6].$aux[7].$aux[8].'-'.$aux[9].$aux[10];

		$html = $html.$aux.'</label>
						</td>
						<td width="30%">
							<label class="lbl-title">Telefone</label><br>
							<label class="lbl-item">';
							
	$aux = $linha_Cliente['cd_telefone1']; 
	if (strlen($aux) == 10) 
	{
		$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];
	}
	else 
	{
		$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];
	}
	
								$html = $html.$aux; 
	
	$aux = $linha_Cliente['cd_telefone2']; 
	if ($aux != '') 
	{
		if (strlen($aux) == 10) 
		{
			$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];
		}
		else 
		{
			$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];
		}
		
								$html = $html.' / '.$aux;
	}
	
			$html = $html.'</label>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label class="lbl-title">Endereço</label><br>
							<label class="lbl-item">'.$linha_Cliente['nm_endereco'].'</label>
						</td>
						<td colspan="2">
							<label class="lbl-title">Referências</label><br>
							<label class="lbl-item">'.$linha_Cliente['ds_referencias'].'</label>
						</td>
					</tr>
				</table>
			</div>		
			<br>
			<div class="data-client">
				<label class="data-title">Ordem de Serviço - Código '.$codigo.'</label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Marca e modelo</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">IMEI</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">Categoria</label><br>
						</td>
						<td width="30%">
							<label class="lbl-title">Sintomas e Serviços</label><br>
						</td>
					</tr>';
					
				if ($total_Aparelhos > 0)
				{
					do
					{
						$html = $html.'<tr>
							<td width="30%">
								<label class="lbl-item">'.$linha_Aparelhos['nm_marca_modelo'].'</label>
							</td>
							<td width="20%">
								<label class="lbl-item">';
								
								$aux = $linha_Aparelhos['cd_imei']; 
								$aux = $aux[0].$aux[1].'-'.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].'-'.$aux[8].$aux[9].$aux[10].$aux[11].$aux[12].$aux[13].'-'.$aux[14]; 
								
							$html = $html.$aux.'</label>
							</td>
							<td width="20%">
								<label class="lbl-item">'.$linha_Aparelhos['nm_categoria'].'</label>
							</td>
							<td width="30%">
								<label class="lbl-item">';
								
								$aux = $linha_Aparelhos['ds_item_os']; 
								$aux = str_replace(ConfereQuebra(), '<br/>', $aux); 
								
							$html = $html.$aux.'</label>
							</td>
						</tr>';
					}
					while ($linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos));
				}
				
				$html = $html.'</table>
			</div>
			<br>
			<div class="data-client">
				<table width="100%" cellspacing="0" >
					<tr align="center">
						<td width="20%">
							<label class="lbl-title">Data de orçamento</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_orcamento']; 
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux)); 
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de início</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_inicio'];
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux));
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de finalização</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_finalizacao'];
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux));
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de expiração</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_expiracao'];
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux)); 
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de retirada</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_retirada'];
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux)); 
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
								$html = $html.'</label>
							</td>
						</tr>
				</table>
			</div>	
			<Br><br>
			<label class="lbl-item-signature">_________________________________________________</label>
			<label class="lbl-item-signature">Ciente dos termos acima (Assinatura do cliente)</label>
		</div>
		
		<br><div class="pontilhado">&nbsp;</div>
		
		<div class="ordem destaca">
			<label class="doctitle">Fora de Garantia</label>
			<div class="data-client">
				<label class="data-title">Dados do Cliente</label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Nome</label><br>
							<label class="lbl-item">'.$linha_Cliente['nm_cliente'].'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">RG</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Cliente['cd_rg'];
							$aux = $aux[0].$aux[1].'.'.$aux[2].$aux[3].$aux[4].'.'.$aux[5].$aux[6].$aux[7].'-'.$aux[8];
							
							$html = $html.$aux.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">CPF</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Cliente['cd_cpf']; 
							$aux = $aux[0].$aux[1].$aux[2].'.'.$aux[3].$aux[4].$aux[5].'.'.$aux[6].$aux[7].$aux[8].'-'.$aux[9].$aux[10];
							
							$html = $html.$aux.'</label>
						</td>
						<td width="30%">
							<label class="lbl-title">Telefone(s)</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Cliente['cd_telefone1'];
							if (strlen($aux) == 10) 
							{
								$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];
							}
							else 
							{
								$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];
							}

							$html = $html.$aux; 
							
							$aux = $linha_Cliente['cd_telefone2']; 
							if ($aux != '') 
							{
								if (strlen($aux) == 10) 
								{
									$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];
								}
								else 
								{
									$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];
								}

								$html = $html.' / '.$aux;
							}
							
							$html = $html.'</label>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label class="lbl-title">Endereço</label><br>
							<label class="lbl-item">'.$linha_Cliente['nm_endereco'].'</label>
						</td>
						<td colspan="2">
							<label class="lbl-title">Referências</label><br>
							<label class="lbl-item">'.$linha_Cliente['ds_referencias'].'</label>
						</td>
					</tr>
				</table>
			</div>		
			<br>
			<div class="data-client">
				<label class="data-title">Ordem de Serviço - Código '.$codigo.'</label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Marca e modelo</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">IMEI</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">Categoria</label><br>
						</td>
						<td width="30%">
							<label class="lbl-title">Sintomas e Serviços</label><br>
						</td>
					</tr>';
			$result_Aparelhos = $conexaoPrincipal -> Query($query_Aparelhos);
			$linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos);
			$total_Aparelhos = mysqli_num_rows($result_Aparelhos);
			
			if ($total_Aparelhos > 0)
			{
				do
				{
					$html = $html.'<tr>
						<td width="30%">
							<label class="lbl-item">'.$linha_Aparelhos['nm_marca_modelo'].'</label>
						</td>
						<td width="20%">
							<label class="lbl-item">';
							
							$aux = $linha_Aparelhos['cd_imei'];
							$aux = $aux[0].$aux[1].'-'.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].'-'.$aux[8].$aux[9].$aux[10].$aux[11].$aux[12].$aux[13].'-'.$aux[14];

							$html = $html.$aux.'</label>
						</td>
						<td width="20%">
							<label class="lbl-item">'.$linha_Aparelhos['nm_categoria'].'</label>
						</td>
						<td width="30%">
							<label class="lbl-item">';
							
							$aux = $linha_Aparelhos['ds_item_os'];
							$aux = str_replace(ConfereQuebra(), '<br/>', $aux);

							$html = $html.$aux.'</label>
						</td>
					</tr>';
				}
				while ($linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos));
			}
			
				$html = $html.'</table>
			</div>
			<br>
			<div class="data-text">
				<label class="lbl-title">Condições da prestação dos serviços</label><br>
				<label class="lbl-item2">A Stratus Telecom, pode retirar o equipamento para análise em laboratório, sempre as condições não forem equivalentes; </label><br>
				<label class="lbl-item2">Em caso de sinistro não aprovado pela seguradora, o equipamento será devolvido pela asseguradora, o equipamento será devolvido com o mesmo sintoma/vício apresentado;</label><br>			
				<label class="lbl-title">Não estão cobertos pela garantia</label><br>
				<label class="lbl-item2">Defeitos ocasionados por transporte inadequado do produto;</label>
				<label class="lbl-item2">Defeitos ocasionados por causas externas ao produto, que estejam interferindo no seu correto funcionamento, como: interferências eletromagnéticas, flutuação de energia elétrica, descargas elétricas, umidade, entre outras;</label><br>
				<label class="lbl-item2">Manutenção preventiva, ou seja, intervenes que sejam caracterizadas para a correção de um problema específico;</label><br>
				<label class="lbl-item2">Problemas advindos de erros de operação que causem perdas de funcionalidades, desconfiguração, inclusive SETUP da Bios, sistemas operacionais e aplicativos;</label><br>
				<label class="lbl-item2">Danos causados por vírus;</label><br>
				<label class="lbl-item2">Geração de cópia de segurança(Backup) dos programas e arquivos;</label><br>
				<label class="lbl-item2">Defeitos provocados pelo uso indevido do equipamento, em desacordo com o pôster de instruções do equipamento, batidas, fogo, queda, influência de temperaturas anormais, utilização de agentes químicos e corrosivos ou provenientes de casos fortuitos e força maior.</label><br>
				
				<label class="lbl-title">Aviso: Caso o equipamento esteja a disposição para retirada, reparado ou não e o cliente não venha retirar em até 90 dias, o equipamento será doado para uma instituição sem fins lucrativos.</label>
			</div>
			<br>
			<div class="data-client">
				<table width="100%" cellspacing="0" >
					<tr align="center">
						<td width="20%">
							<label class="lbl-title">Data de orçamento</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_orcamento']; 
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux));
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de início</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_inicio'];
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux));
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de finalização</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_finalizacao'];
							if ($aux != '')
							{
								$aux = date('d/m/Y', strtotime($aux));
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de expiração</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_expiracao'];
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux));
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de retirada</label><br>
							<label class="lbl-item">';
							
							$aux = $linha_Datas['dt_retirada']; 
							if ($aux != '') 
							{
								$aux = date('d/m/Y', strtotime($aux)); 
								$html = $html.$aux;
							}
							else 
							{
								$html = $html.'-';
							}
							
							$html = $html.'</label>
						</td>
					</tr>
				</table>
			</div>	
			<br><br>
			<label class="lbl-item-signature">_________________________________________________</label>
			<label class="lbl-item-signature">Assinatura do responsável pela loja</label>
		</div>
	</body>
</html>';

	$mpdf = new mPDF();
	$mpdf -> allow_charset_conversion = true;
	$mpdf -> charset_in = 'UTF-8';
	$stylesheet = file_get_contents('css/styleModeloOrdem.css');
	$mpdf -> WriteHTML($stylesheet,1);
	$mpdf -> WriteHTML($html,2);
	$mpdf -> Output(null, 'I');
?>		
					