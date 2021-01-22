<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (isset($_GET['codigoOrdem']) && $_GET['codigoOrdem'] != '')
	{
		$codigoOrdem = mysql_escape_string($_GET['codigoOrdem']);
		
		$result = $conexaoPrincipal -> Query("select tb_os.cd_os, group_concat(item_os.cd_aparelho) as cd_aparelhos
												  from tb_os inner join item_os
													on tb_os.cd_os = item_os.cd_os
													  where tb_os.cd_os = '$codigoOrdem'");
		$linha = mysqli_fetch_assoc($result);
		$total = mysqli_num_rows($result);
		
		if ($total > 0)
		{
			$aparelhosOrdem = $linha['cd_aparelhos'];
			$aparelhosOrdem = explode(',', $aparelhosOrdem);
			
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
			$total_AparelhosOrdem = $total;
		}
	}
	else if (isset($_COOKIE['AZLI']['OrdemRecente']['codigoAparelho']))
	{
		$aparelhosOrdem = $_COOKIE['AZLI']['OrdemRecente']['codigoAparelho'];
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
		$total_AparelhosOrdem = 0;
	}
?>
<tr>
	<td colspan="3">
		<label class="azli-label">Aparelhos nessa ordem de serviço (<label id="qt_aparelhosOrdem"><?php echo $total_AparelhosOrdem; ?></label>)</label>
	</td>
</tr>
<?php		
	$cont = 0;
	
	if ($total_AparelhosOrdem > 0)
	{
		do
		{
?>
			<tr>
				<td width="80%">
					<input type="button" class="azli-input func-at list" value="<?php echo $linha_AparelhosOrdem['nm_modelo'].' - '.(($linha_AparelhosOrdem['cd_imei'] != '') ? $linha_AparelhosOrdem['cd_imei'].(($linha_AparelhosOrdem['cd_imei_secundario'] != '') ? ' / '.$linha_AparelhosOrdem['cd_imei_secundario'] : '') : (($linha_AparelhosOrdem['cd_numero_serie'] != '') ? $linha_AparelhosOrdem['cd_numero_serie'] : '')); ?>"/>
				</td>
				<td width="10%">
					<input type="button" class="func-del" value="Editar" onclick="BuscarAparelho('<?php echo $linha_AparelhosOrdem['cd_imei']; ?>', '<?php echo $linha_AparelhosOrdem['cd_imei_secundario']; ?>', '<?php echo $linha_AparelhosOrdem['cd_numero_serie']; ?>', codigoOrdem); btn_cadastrarAparelho.value = 'Alterar'; cmb_categoria.disabled = true; /*txt_IMEI.readOnly = true;*/ btn_novoAparelho.style.display = 'none'; btn_buscarAparelho.style.display = 'none';" tabindex="<?php $cont = $cont + 1; echo $cont; ?>"/>
				</td>
				<td width="10%">
					<input type="button" class="func-del" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse aparelho?')) {RemoverAparelho('<?php echo $linha_AparelhosOrdem['cd_aparelho']; ?>', codigoOrdem);}" tabindex="<?php $cont = $cont + 1; echo $cont; ?>"/>
				</td>
			</tr>
<?php
		}
		while ($linha_AparelhosOrdem = mysqli_fetch_assoc($result_AparelhosOrdem));
	}
	
	echo ';-;'.$total_AparelhosOrdem;
?>