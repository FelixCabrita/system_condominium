<?php
defined('BASEPATH') OR exit('No direct script access allowed');
setcookie("workstation_fscv",  '', time() - (86400 * 15),'/'); 
			setcookie("workstation_name_fscv", '', time() - (86400 * 15),'/');
		   setcookie("workstation_fscv_", '', time() - (86400 * 15),'/');
			
echo "\nERROR: ",
	$heading,
	"\n\n",
	$message,
	"\n\n";