<?php
	//session_start();
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_POST['status']) || !isset($_POST['codigo']))
	{
		exit;
	}
	
	$codigo = mysql_escape_string($_POST['codigo']);
	$status = mysql_escape_string($_POST['status']);
	
	if ($status == 'all')
	{	
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
																where tb_os.cd_verificador like '%$codigo%'
																	order by tb_os.dt_cadastro desc");
	}
	else
	{
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
																where tb_os.cd_verificador like '%$codigo%' 	
																	and tb_status.cd_status = '$status'
																		order by tb_os.dt_cadastro desc");
	}
		
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
	
	$conexaoPrincipal -> FecharConexao();
?>