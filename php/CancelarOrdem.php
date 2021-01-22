<?php
	setcookie("AZLI[OrdemRecente][etapaOrdem]", "", time()-3600, "/");
	setcookie("AZLI[OrdemRecente][codigoCliente]", "", time()-3600, "/");
	setcookie("AZLI[OrdemRecente][codigoAparelho]", "", time()-3600, "/");
	setcookie("AZLI[OrdemRecente][codigoOrdem]", "", time()-3600, "/");
	setcookie("AZLI[OrdemRecente][codigoVerificador]", "", time()-3600, "/");
	setcookie("AZLI[OrdemRecente][problemasAparelho]", "", time()-3600, "/");
	setcookie("AZLI[OrdemRecente][codigosServico]", "", time()-3600, "/");
	
	echo '1'.((isset($aviso)) ? $aviso : ';-;');
?>