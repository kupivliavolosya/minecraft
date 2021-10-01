<?php
if ( ! defined ( "INCLUDE_CHECK" ) ) die ("Не получилось посоны");

include './incl/config.php';
include "./incl/db_connect.php";
include "./incl/onepage.php";

$mysql = new db;
$mysql->connect($config['db']['user'],$config['db']['pass'], $config['db']['name'],$config['db']['host']);
$onepage = new onepage($config,$mysql);

if ( isset ( $_POST['buy'] ) ) { $onepage -> send($_POST['nikname'], $_POST['server'], $_POST['group']); }

$monitoring = $onepage->monitoring();