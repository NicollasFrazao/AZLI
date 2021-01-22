<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_POST['marca']) || !isset($_POST['categoria']))
	{
		exit;
	}
	
	$codigoMarca = mysql_escape_string($_POST['marca']);
	$categoria = mysql_escape_string($_POST['categoria']);
	
	if ($categoria == 'all')
	{
		$result_Modelo = $conexaoPrincipal -> Query("select cd_modelo, nm_modelo, ic_editavel from tb_modelo where cd_marca = '$codigoMarca' order by nm_modelo");
	}
	else
	{
		$result_Modelo = $conexaoPrincipal -> Query("select cd_modelo, nm_modelo, ic_editavel from tb_modelo where cd_marca = '$codigoMarca' and cd_categoria = '$categoria' order by nm_modelo");
	}
	
	$linha_Modelo = mysqli_fetch_assoc($result_Modelo);
	$total_Modelo = mysqli_num_rows($result_Modelo);
?>
<?php
	if ($total_Modelo > 0)
	{
		do
		{
?>
			<tr class="modelo cadastrado" id="modelo<?php echo $linha_Modelo['cd_modelo']; ?>">
				<td width="90%" colspan="<?php if ($linha_Modelo['ic_editavel'] == 0) {echo 2;} else {echo 1;} ?>">
					<input type="text" codigo="<?php echo $linha_Modelo['cd_modelo']; ?>" class="azli-input func-at" value="<?php echo $linha_Modelo['nm_modelo']; ?>" onfocus="this.setAttribute('nomeAnterior', this.value.trim());" onblur="if (this.value.trim() != '') {AlterarModelo(this.getAttribute('codigo'), this.value.trim());} else {this.value = this.getAttribute('nomeAnterior');}" <?php if ($linha_Modelo['ic_editavel'] == 0) {echo 'disabled';} ?>/>
				</td>
				<?php
					if ($linha_Modelo['ic_editavel'] == 1)
					{
				?>
						<td width="10%">
							<input type="button" class="func-del" value="Remover" onclick="if (confirm('Tem certeza que deseja remover esse modelo?')) {ExcluirModelo(this.getAttribute('codigo'));}" codigo="<?php echo $linha_Modelo['cd_modelo']; ?>"/>
						</td>
				<?php
					}
				?>
			</tr>
			<input class="ultimoModelo" style="display: none;" value="<?php echo $linha_Modelo['cd_modelo']; ?>"/>
<?php
		}
		while ($linha_Modelo = mysqli_fetch_assoc($result_Modelo));
	}
	
	$result = $conexaoPrincipal -> Query("select cd_modelo from tb_modelo order by cd_modelo desc limit 1");
	$linha = mysqli_fetch_assoc($result);
	
	echo ';-;'.$linha['cd_modelo'];
?>
