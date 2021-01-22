<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigoMarca']) || !isset($_GET['codigoCategoria']))
	{
		exit;
	}
	
	$codigoMarca = mysql_escape_string($_GET['codigoMarca']);
	$codigoCategoria = mysql_escape_string($_GET['codigoCategoria']);
	
	$result_Modelo = $conexaoPrincipal -> Query("select cd_modelo, nm_modelo from tb_modelo where cd_marca = '$codigoMarca' and cd_categoria = '$codigoCategoria' order by nm_modelo");
	$linha_Modelo = mysqli_fetch_assoc($result_Modelo);
	$total_Modelo = mysqli_num_rows($result_Modelo);
?>
<option value="">Selecione o modelo do aparelho</option>
<?php
	if ($total_Modelo > 0)
	{
		do
		{
?>
			<option value="<?php echo $linha_Modelo['cd_modelo']; ?>"><?php echo $linha_Modelo['nm_modelo']; ?></option>
<?php
		}
		while ($linha_Modelo = mysqli_fetch_assoc($result_Modelo));
	}
?>
