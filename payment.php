<?php
	
define ("INCLUDE_CHECK", true);
include "./incl/config.php";
include "./incl/db_connect.php";
include "./incl/onepage.php";

$mysql = new db;
$mysql->connect($config['db']['user'],$config['db']['pass'], $config['db']['name'],$config['db']['host']);
$onepage = new onepage($config,$mysql);

if (isset($_REQUEST['method']) && isset($_REQUEST['params'])) {
	$check = strtolower($_REQUEST['method']);
	if ( $check == "pay" || $check == "check" ) {
		$checktype = $check == "pay" ? true : false;
		echo $onepage->up_sign($_REQUEST['params'], $checktype);
	} else echo $onepage->up_json_reply("error", $_REQUEST['params']);
} else echo ",k,k";
?>