<?php
	session_start();
	
	unset($_SESSION['AZLI']);
	
	if(!isset($_SESSION['AZLI']))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
?>