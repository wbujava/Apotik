<?php 
function page()
{
	//SET DOMAIN
	$setting = "http://localhost/ujianx/";
	return $setting;
}
	
	//SETTING GMT DISINI
function timeNow()
{
	$today = date("Y-m-d h:i:s");
	$gmt_server = 7; //daerah amerika
	$gmt_kita = 7; //indonesia
	$gmt = $gmt_server + $gmt_kita;
	return date("Y-m-d h:i:s",strtotime($today)+(3600*$gmt));	
}

	//SET DATABSE HERE
	$mysql['host'] = "localhost";
	$mysql['user'] = "root";
	$mysql['password'] = "anisnuzulan";
	$mysql['database'] = "ujianx";
	database($mysql);
?>
