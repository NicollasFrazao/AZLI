<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigoCategoria']))
	{
		exit;
	}
	
	$codigoCategoria = mysql_escape_string($_GET['codigoCategoria']);
	
	$result_Marca = $conexaoPrincipal -> Query("select distinct tb_marca.cd_marca, tb_marca.nm_marca
												  from tb_categoria inner join tb_modelo
													on tb_categoria.cd_categoria = tb_modelo.cd_categoria
													  inner join tb_marca
														on tb_marca.cd_marca = tb_modelo.cd_marca
														  where tb_categoria.cd_categoria = '$codigoCategoria' order by tb_marca.nm_marca");
	$linha_Marca = mysqli_fetch_assoc($result_Marca);
	$total_Marca = mysqli_num_rows($result_Marca);
?>
<option value="">Selecione a marca do aparelho</option>
<?php
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
