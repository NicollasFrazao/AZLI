<?php
	include 'php/Conexao.php';
	include 'php/ConexaoPrincipal.php';
	
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
	
	$query_Aparelhos = "select tb_os.cd_os, tb_aparelho.cd_aparelho, concat(tb_marca.nm_marca, ' ', tb_modelo.nm_modelo) as nm_marca_modelo,
							   tb_aparelho.cd_imei,
							   tb_aparelho.cd_numero_serie,
							   tb_categoria.nm_categoria,
							   item_os.ds_item_os,
							   tb_os.dt_cadastro
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
	
	$dataCadastro = date('d/m/Y H:i', strtotime($linha_Aparelhos['dt_cadastro']));
	
	$result_Datas = $conexaoPrincipal -> Query("select tb_os.dt_orcamento,
														   tb_os.dt_inicio,
														   tb_os.dt_finalizacao,
														   tb_os.dt_expiracao,
														   tb_os.dt_retirada,
							   (select concat('R$ ', sum(vl_custo), '/R$ ', sum(vl_preco), '/R$ ', sum((vl_preco - vl_custo)))
  from tb_servico
    where tb_servico.cd_os = tb_os.cd_os) as ds_total_valores,
								(select concat('R$ ', sum(vl_preco))
  from tb_servico
    where tb_servico.cd_os = tb_os.cd_os) as ds_total_pagar
													  from tb_os
														where tb_os.cd_verificador = '$codigo'");
	$linha_Datas = mysqli_fetch_assoc($result_Datas);
	$total_Datas = mysqli_num_rows($result_Datas);
?>

<html>
	<meta charset="utf-8">
	<head>
		<title>Imprimir ordem de serviço Nº <?php echo $codigo; ?></title>
		<style>
			*
			{
				margin: 0px;
				padding: 0px;
				font-family: century gothic;
			}	
			
			@page
			{
				margin: 0.5cm;
			}
			
			.lbl-top
			{
				display: inline-block;
				background-color: #eee;
				font-size: 0.6em;
				width: 100%;
			}
			
			.lbl-title
			{
				display: inline-block;
				font-weight: bold;
				font-size: 0.5em;
				padding: 0.5em;
				padding-bottom: 0em;
			}
			
			.lbl-item
			{
				display: inline-block;
				font-size: 0.6em;
				padding: 0.5em;
			}
			
			.lbl-item.quebra
			{
				display: block;
			}
			
			.lbl-item2
			{
				display: inline-block;
				width: 100%;
				font-size: 0.5em;
				padding-left: 0.5em;
			}
			
			.tr-title
			{
				background-color: #bbb;
			}
			
			.data-client
			{
				background-color: #eee;
			}
			
			.data-title
			{
				displaY: inline-block;
				width: 98%;
				font-size: 0.6em;
				font-weight: bold;
				background-color: #ddd;
				padding: 1%;
			}
			
			.lbl-item-signature
			{
				display: inline-block;
				width: 100%;
				font-size: 0.6em;
				padding: 0.5em;
				text-align: center
			}
			
			.doctitle
			{
				display: inline-block;
				width: 100%;
				text-align: center;
			}
			
			.pontilhado
			{
				border-top: 2px dashed black;
			}
		</style>
	</head>
	<body>
		<div class="ordem fica">
			<label class="doctitle">Fora de Garantia</label>
			<div class="data-client">
				<label class="data-title">Dados do Cliente</label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Nome</label><br>
							<label class="lbl-item"><?php echo $linha_Cliente['nm_cliente']; ?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">RG</label><br>
							<label class="lbl-item"><?php $aux = $linha_Cliente['cd_rg']; $aux = $aux[0].$aux[1].'.'.$aux[2].$aux[3].$aux[4].'.'.$aux[5].$aux[6].$aux[7].'-'.$aux[8]; echo $aux; ?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">CPF</label><br>
							<label class="lbl-item"><?php $aux = $linha_Cliente['cd_cpf']; $aux = $aux[0].$aux[1].$aux[2].'.'.$aux[3].$aux[4].$aux[5].'.'.$aux[6].$aux[7].$aux[8].'-'.$aux[9].$aux[10]; echo $aux; ?></label>
						</td>
						<td width="30%">
							<label class="lbl-title">Telefone</label><br>
							<label class="lbl-item"><?php $aux = $linha_Cliente['cd_telefone1']; if (strlen($aux) == 10) {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];} else {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];} echo $aux; $aux = $linha_Cliente['cd_telefone2']; if ($aux != '') {if (strlen($aux) == 10) {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];} else {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];} echo ' / '.$aux;}?></label>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label class="lbl-title">Endereço</label><br>
							<label class="lbl-item"><?php echo $linha_Cliente['nm_endereco']; ?></label>
						</td>
						<td colspan="2">
							<label class="lbl-title">Referências</label><br>
							<label class="lbl-item"><?php echo $linha_Cliente['ds_referencias']; ?></label>
						</td>
					</tr>
				</table>
			</div>		
			<br>
			<div class="data-client">
				<label class="data-title">Ordem de Serviço - Nº <?php echo $codigo; ?> - <?php echo $dataCadastro; ?> - Total a pagar<!--dos valores-->: <?php echo $linha_Datas['ds_total_pagar']; /*echo $linha_Datas['ds_total_valores'];*/ ?></label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Marca e modelo</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">IMEI ou Nº de série</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">Categoria</label><br>
						</td>
						<td width="30%">
							<label class="lbl-title">Serviços e valores</label><br>
						</td>
					</tr>
					
					<?php
						if ($total_Aparelhos > 0)
						{
							do
							{
								$codigoAparelho = $linha_Aparelhos['cd_aparelho'];
								$codigoOrdem = $linha_Aparelhos['cd_os'];
								
								/*$result = $conexaoPrincipal -> Query("select concat(tb_servico.ds_servico, ' - R$ ', vl_custo, '/R$ ', vl_preco, '/R$ ', (vl_preco - vl_custo)) as ds_servico_valores
																		  from tb_servico
																			where tb_servico.cd_os = '$codigoOrdem' and tb_servico.cd_aparelho = '$codigoAparelho'");*/
								$result = $conexaoPrincipal -> Query("select concat(tb_servico.ds_servico, ' - R$ ', vl_preco) as ds_servico_valores
																		  from tb_servico
																			where tb_servico.cd_os = '$codigoOrdem' and tb_servico.cd_aparelho = '$codigoAparelho'");
								$linha = mysqli_fetch_assoc($result);
					?>
								<tr>
									<td width="30%">
										<label class="lbl-item"><?php echo $linha_Aparelhos['nm_marca_modelo']; ?></label>
									</td>
									<td width="20%">
										<label class="lbl-item"><?php if ($linha_Aparelhos['cd_imei'] != '') {$aux = $linha_Aparelhos['cd_imei']; $aux = $aux[0].$aux[1].'-'.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].'-'.$aux[8].$aux[9].$aux[10].$aux[11].$aux[12].$aux[13].'-'.$aux[14];} else {$aux = $linha_Aparelhos['cd_numero_serie'];} echo $aux;?></label>
									</td>
									<td width="20%">
										<label class="lbl-item"><?php echo $linha_Aparelhos['nm_categoria']; ?></label>
									</td>
									<td width="30%">
										<?php
											do
											{
										?>
												<label class="lbl-item quebra"><?php /*$aux = $linha_Aparelhos['ds_item_os']; $aux = str_replace(ConfereQuebra(), '<br/>', $aux);*/ echo $linha['ds_servico_valores'];?></label>
										<?php
											}
											while ($linha = mysqli_fetch_assoc($result));
										?>
									</td>
								</tr>
					<?php
							}
							while ($linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos));
						}
					?>
				</table>
			</div>
			<br>
			<div class="data-client">
				<table width="100%" cellspacing="0" >
					<tr align="center">
						<td width="20%">
							<label class="lbl-title">Data de orçamento</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_orcamento']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de início</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_inicio']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de finalização</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_finalizacao']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de expiração</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_expiracao']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de retirada</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_retirada']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
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
							<label class="lbl-item"><?php echo $linha_Cliente['nm_cliente']; ?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">RG</label><br>
							<label class="lbl-item"><?php $aux = $linha_Cliente['cd_rg']; $aux = $aux[0].$aux[1].'.'.$aux[2].$aux[3].$aux[4].'.'.$aux[5].$aux[6].$aux[7].'-'.$aux[8]; echo $aux; ?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">CPF</label><br>
							<label class="lbl-item"><?php $aux = $linha_Cliente['cd_cpf']; $aux = $aux[0].$aux[1].$aux[2].'.'.$aux[3].$aux[4].$aux[5].'.'.$aux[6].$aux[7].$aux[8].'-'.$aux[9].$aux[10]; echo $aux; ?></label>
						</td>
						<td width="30%">
							<label class="lbl-title">Telefone(s)</label><br>
							<label class="lbl-item"><?php $aux = $linha_Cliente['cd_telefone1']; if (strlen($aux) == 10) {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];} else {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];} echo $aux; $aux = $linha_Cliente['cd_telefone2']; if ($aux != '') {if (strlen($aux) == 10) {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].$aux[8].$aux[9];} else {$aux = '('.$aux[0].$aux[1].') '.$aux[2].$aux[3].$aux[4].$aux[5].$aux[6].'-'.$aux[7].$aux[8].$aux[9].$aux[10];} echo ' / '.$aux;}?></label>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label class="lbl-title">Endereço</label><br>
							<label class="lbl-item"><?php echo $linha_Cliente['nm_endereco']; ?></label>
						</td>
						<td colspan="2">
							<label class="lbl-title">Referências</label><br>
							<label class="lbl-item"><?php echo $linha_Cliente['ds_referencias']; ?></label>
						</td>
					</tr>
				</table>
			</div>		
			<br>
			<div class="data-client">
				<label class="data-title">Ordem de Serviço - Nº <?php echo $codigo; ?> - <?php echo $dataCadastro; ?> - Total a pagar: <?php echo $linha_Datas['ds_total_pagar']; ?></label>
				<table width="100%" cellspacing="0">
					<tr>
						<td width="30%">
							<label class="lbl-title">Marca e modelo</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">IMEI ou Nº de série</label><br>
						</td>
						<td width="20%">
							<label class="lbl-title">Categoria</label><br>
						</td>
						<td width="30%">
							<label class="lbl-title">Serviços e preços</label><br>
						</td>
					</tr>
					
					<?php
						$result_Aparelhos = $conexaoPrincipal -> Query($query_Aparelhos);
						$linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos);
						$total_Aparelhos = mysqli_num_rows($result_Aparelhos);
						
						if ($total_Aparelhos > 0)
						{
							do
							{
								$codigoAparelho = $linha_Aparelhos['cd_aparelho'];
								$codigoOrdem = $linha_Aparelhos['cd_os'];
								
								$result = $conexaoPrincipal -> Query("select concat(tb_servico.ds_servico, ' - R$ ', vl_preco) as ds_servico_preco
																		  from tb_servico
																			where tb_servico.cd_os = '$codigoOrdem' and tb_servico.cd_aparelho = '$codigoAparelho'");
								$linha = mysqli_fetch_assoc($result);
					?>
								<tr>
									<td width="30%">
										<label class="lbl-item"><?php echo $linha_Aparelhos['nm_marca_modelo']; ?></label>
									</td>
									<td width="20%">
										<label class="lbl-item"><?php if ($linha_Aparelhos['cd_imei'] != '') {$aux = $linha_Aparelhos['cd_imei']; $aux = $aux[0].$aux[1].'-'.$aux[2].$aux[3].$aux[4].$aux[5].'-'.$aux[6].$aux[7].'-'.$aux[8].$aux[9].$aux[10].$aux[11].$aux[12].$aux[13].'-'.$aux[14];} else {$aux = $linha_Aparelhos['cd_numero_serie'];} echo $aux;?></label>
									</td>
									<td width="20%">
										<label class="lbl-item"><?php echo $linha_Aparelhos['nm_categoria']; ?></label>
									</td>
									<td width="30%">
										<?php
											do
											{
										?>
												<label class="lbl-item quebra"><?php /*$aux = $linha_Aparelhos['ds_item_os']; $aux = str_replace(ConfereQuebra(), '<br/>', $aux);*/ echo $linha['ds_servico_preco'];?></label>
										<?php
											}
											while ($linha = mysqli_fetch_assoc($result));
										?>
									</td>
								</tr>
					<?php
							}
							while ($linha_Aparelhos = mysqli_fetch_assoc($result_Aparelhos));
						}
					?>
				</table>
			</div>
			<br>
			<div class="data-text">
				<label class="lbl-item2">A Azlitoral Informatica, pode retirar o equipamento para análise em laboratorio, sempre que as condições não forem equivalentes;</label><br>
				
				<label class="lbl-title">Não estão cobertos pela garantia</label><br>
				<label class="lbl-item2">Defeitos ocasionados por transporte inadequado do produto;</label><br>
				<label class="lbl-item2">Defeitos ocasionados por causas externas ao produto, que estejam interferindo no seu correto funcionamento, como: interferências eletromagnéticas, flutuação de energia elétrica, descargas elétricas, umidade, entre outros(Mal Uso);</label><br>
				<label class="lbl-item2">Manutenção preventiva, ou seja, intervenes que sejam caracterizadas para a correção de um problema específico;</label><br>
				<label class="lbl-item2">Problemas advindos de erros de operação que causem perdas de funcionalidades, desconfiguração, inclusive SETUP da Bios, sistemas operacionais e aplicativos;</label><br>
				<label class="lbl-item2">Danos causados por vírus;</label><br>
				<label class="lbl-item2">Geração de cópia de segurança(Backup) dos programas e arquivos;</label><br>
				<label class="lbl-item2">Defeitos provocados pelo uso indevido do equipamento, em desacordo com o pôster de instruções do equipamento, batidas, fogo, queda, influência de temperaturas anormais, utilização de agentes químicos e corrosivos ou provenientes de casos fortuitos e força maior.</label><br>
				
				<label class="lbl-title">Em caso de Sistema</label><br>
				<label class="lbl-item2">Defeitos ocasionados por erro durante a recuperação do software;</label><br>
				<label class="lbl-item2">Defeitos ocasionados por falha de bateria ou peças do dispositivo;</label><br>
				<label class="lbl-item2">Defeitos ocasionados por umidade no dispositivo;</label><br>
				<label class="lbl-item2">Em caso de erro no chip de memoria;</label><br>
				
				<label class="lbl-title"></label><br>
				<label class="lbl-item2">Aviso: Caso o equipamento esteja a disposição para retirada, reparado ou não e o cliente não venha retirar em até 90 dias, o equipamento será doado para uma instituição sem fins lucrativos.</label><br>
			</div>
			<br>
			<div class="data-client">
				<table width="100%" cellspacing="0" >
					<tr align="center">
						<td width="20%">
							<label class="lbl-title">Data de orçamento</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_orcamento']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de início</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_inicio']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de finalização</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_finalizacao']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de expiração</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_expiracao']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
						<td width="20%">
							<label class="lbl-title">Data de retirada</label><br>
							<label class="lbl-item"><?php $aux = $linha_Datas['dt_retirada']; if ($aux != '') {$aux = date('d/m/Y', strtotime($aux)); echo $aux;} else {echo '-';}?></label>
						</td>
					</tr>
				</table>
			</div>	
			<Br><br>
			<label class="lbl-item-signature">_________________________________________________</label>
			<label class="lbl-item-signature">Assinatura do responsável pela loja</label>
		</div>
	</body>
	<script>
		window.onload = function()
		{
			this.print();
		}
	</script>
</html>