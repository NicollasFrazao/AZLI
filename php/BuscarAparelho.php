<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['IMEI']) || !isset($_GET['IMEISecundario']) || !isset($_GET['numeroSerie']))
	{
		exit;
	}
	
	$codigoAparelho = '';
	$IMEI = mysql_escape_string($_GET['IMEI']);
	$IMEISecundario = mysql_escape_string($_GET['IMEISecundario']);
	$numeroSerie = mysql_escape_string($_GET['numeroSerie']);
	
	$IMEI = str_replace('.', '', $IMEI);
	$IMEI = str_replace('-', '', $IMEI);
	
	$IMEISecundario = str_replace('.', '', $IMEISecundario);
	$IMEISecundario = str_replace('-', '', $IMEISecundario);
	
	$numeroSerie = str_replace('.', '', $numeroSerie);
	$numeroSerie = str_replace('-', '', $numeroSerie);
	
	if ($IMEI != '')
	{
		$valorBusca = $IMEI;
		$campo = "tb_aparelho.cd_imei";
	}
	else if ($IMEISecundario != '')
	{
		$valorBusca = $IMEISecundario;
		$campo = "tb_aparelho.cd_imei_secundario";
	}
	else
	{
		$valorBusca = $numeroSerie;
		$campo = "tb_aparelho.cd_numero_serie";
	}
	
	
	if (isset($_GET['codigoOrdem']) && $_GET['codigoOrdem'] != '')
	{
		$codigoOrdem = mysql_escape_string($_GET['codigoOrdem']);
		
		$result = $conexaoPrincipal -> Query("select tb_aparelho.cd_aparelho,
													   tb_modelo.cd_categoria,
													   tb_marca.cd_marca,
													   tb_modelo.cd_modelo,
													   tb_aparelho.cd_imei,
													   tb_aparelho.ds_aparelho,
													   item_os.ds_item_os,
													   tb_aparelho.cd_imei_secundario,
													   tb_aparelho.cd_numero_serie
												  from tb_marca inner join tb_modelo
													on tb_marca.cd_marca = tb_modelo.cd_marca
													  inner join tb_aparelho
														on tb_modelo.cd_modelo = tb_aparelho.cd_modelo
														  inner join item_os
															on tb_aparelho.cd_aparelho = item_os.cd_aparelho
															  inner join tb_os
																on item_os.cd_os = tb_os.cd_os
															  where $campo = '$valorBusca' and tb_os.cd_os = '$codigoOrdem'");
	}
	else
	{
		$codigoOrdem = '';
		
		$result = $conexaoPrincipal -> Query("select tb_aparelho.cd_aparelho,
													   tb_modelo.cd_categoria,
													   tb_marca.cd_marca,
													   tb_modelo.cd_modelo,
													   tb_aparelho.cd_imei,
													   tb_aparelho.ds_aparelho,
													   tb_aparelho.cd_imei_secundario,
													   tb_aparelho.cd_numero_serie
												  from tb_marca inner join tb_modelo
													on tb_marca.cd_marca = tb_modelo.cd_marca
													  inner join tb_aparelho
														on tb_modelo.cd_modelo = tb_aparelho.cd_modelo
													where $campo = '$valorBusca'");
	}
	
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	if ($total != 0)
	{
		$codigoAparelho = $linha['cd_aparelho'];
		
		if (isset($_GET['codigoOrdem']) && $_GET['codigoOrdem'] != '')
		{
			$notas = $linha['ds_item_os'];
		}
		else if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
		{
			$aux = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
			$aux = explode(';-;', $aux);
			
			if (in_array($codigoAparelho, $aux))
			{
				//$notas = explode(';-;', $_COOKIE['AZLI']['OrdemRecente']['problemasAparelho']);
				//$notas = $notas[array_search($codigoAparelho, $aux)];
				$notas = '';
			}
			else
			{
				$notas = '';
			}
		}		
		else
		{
			$notas = '';
		}

	}
	else
	{
		$result = $conexaoPrincipal -> Query("select tb_aparelho.cd_aparelho,
													   tb_modelo.cd_categoria,
													   tb_marca.cd_marca,
													   tb_modelo.cd_modelo,
													   tb_aparelho.cd_imei,
													   tb_aparelho.ds_aparelho
												  from tb_marca inner join tb_modelo
													on tb_marca.cd_marca = tb_modelo.cd_marca
													  inner join tb_aparelho
														on tb_modelo.cd_modelo = tb_aparelho.cd_modelo
													where $campo = '$valorBusca'");
		$linha = mysqli_fetch_assoc($result);
		$total = mysqli_num_rows($result);

		$notas = '';
	}
	
	echo $linha['cd_aparelho'].';-;'.$linha['cd_categoria'].';-;'.$linha['cd_marca'].';-;'.$linha['cd_modelo'].';-;'.$linha['cd_imei'].';-;'.$linha['ds_aparelho'].';-;'.$notas.';-;'.$codigoOrdem.';-;'.$linha['cd_imei_secundario'].';-;'.$linha['cd_numero_serie'].';-;';
	
	$result_Servicos = $conexaoPrincipal -> Query("select tb_servico.cd_servico,
														   tb_servico.ds_servico,
														   tb_servico.cd_fornecedor,
														   (select tb_fornecedor.nm_fornecedor from tb_fornecedor where tb_fornecedor.cd_fornecedor = tb_servico.cd_fornecedor) as nm_fornecedor,
														   tb_servico.vl_custo,
														   tb_servico.vl_preco,
														   (tb_servico.vl_preco - tb_servico.vl_custo) as vl_lucro
													  from tb_servico
														where cd_aparelho = '$codigoAparelho'
														  and cd_os ".(($codigoOrdem != '') ? "= '$codigoOrdem'" : 'is null')."");
	$linha_Servicos = mysqli_fetch_assoc($result_Servicos);
	$total_Servicos = mysqli_num_rows($result_Servicos);
?>
			<tr>
				<td align="left">
					<label class="azli-label">Serviços (<label id="qt_servicos">x</label>)</label>
				</td>
			</tr>
			<tr>
				<th>
					<label class="azli-label">Descrição</label>
				</th>
				<th>
					<label class="azli-label">Fornecedor</label>
				</th>
				<th>
					<label class="azli-label">Custo</label>
				</th>
				<th>
					<label class="azli-label">Preço</label>
				</th>
				<th>
					<label class="azli-label">Lucro</label>
				</th>
			</tr>
<?php	
	$result = $conexaoPrincipal -> Query("select cd_servico from tb_servico order by cd_servico desc limit 1");
	$linha = mysqli_fetch_assoc($result);
	$total = mysqli_num_rows($result);
	
	if ($total > 0)
	{
		$ultimoServico = $linha['cd_servico'];
	}
	else
	{
		$ultimoServico = 0;
	}
	
	$cont = 0;
	
	if ($total_Servicos > 0)
	{		
		do
		{
?>
			<tr id="servico<?php echo $linha_Servicos['cd_servico']; ?>" class="servico cadastrado">
				<td  width="18%">
					<input type="hidden" class="azli-input func-at" name="servicos[<?php echo $cont; ?>][cd_servico]" value="<?php echo $linha_Servicos['cd_servico']; ?>" readonly/>
					<input type="hidden" class="azli-input func-at" name="servicos[<?php echo $cont; ?>][ds_servico]" value="<?php echo $linha_Servicos['ds_servico']; ?>" readonly/>
					<input type="text" class="azli-input func-at" value="<?php echo $linha_Servicos['ds_servico']; ?>" readonly/>
				</td>
				<td  width="18%">
					<input type="hidden" class="azli-input func-at" name="servicos[<?php echo $cont; ?>][cd_fornecedor]" value="<?php echo $linha_Servicos['cd_fornecedor']; ?>" readonly/>
					<input type="text" class="azli-input func-at" value="<?php echo (($linha_Servicos['cd_fornecedor'] != '') ? $linha_Servicos['nm_fornecedor'] : '-'); ?>" readonly/>
				</td>
				<td  width="18%">
					<input type="hidden" class="azli-input func-at" name="servicos[<?php echo $cont; ?>][vl_custo]" value="<?php echo $linha_Servicos['vl_custo']; ?>" readonly/>
					<input type="text" class="azli-input func-at at-valida" tipo="Real" value="<?php echo $linha_Servicos['vl_custo']; ?>" readonly/>
				</td>
				<td  width="18%">
					<input type="hidden" class="azli-input func-at" name="servicos[<?php echo $cont; ?>][vl_preco]" value="<?php echo $linha_Servicos['vl_preco']; ?>" readonly/>
					<input type="text" class="azli-input func-at at-valida" tipo="Real" value="<?php echo $linha_Servicos['vl_preco']; ?>" readonly/>
				</td>
				<td  width="18%">
					<input type="hidden" class="azli-input func-at" value="<?php echo $linha_Servicos['vl_lucro']; ?>" readonly/>
					<input type="text" class="azli-input func-at at-valida" tipo="Real" value="<?php echo $linha_Servicos['vl_lucro']; ?>" style="<?php if ($linha_Servicos['vl_lucro'] < 0) {echo 'background-color: red;';} ?>" readonly/>
				</td>
				<td width="10%">
					<input type="button" class="func-del" codigo="<?php echo $linha_Servicos['cd_servico']; ?>" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse serviço?')) {/*ExcluirFuncionario(this.getAttribute('codigo'));*/ eval('servico' + this.getAttribute('codigo') + '.remove();'); contServico = contServico - 1;}"/>
				</td>
			</tr>
<?php	
			$cont = $cont + 1;
		}
		while ($linha_Servicos = mysqli_fetch_assoc($result_Servicos));
	}
	
	echo ';-;'.$total_Servicos.';-;'.$ultimoServico.';-;'.$cont;
	
	$conexaoPrincipal -> FecharConexao();
?>