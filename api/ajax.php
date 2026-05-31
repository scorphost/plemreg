<?php
define('engine_on', true);
session_start();
/*
AJAX Get processor
*/
require_once('../locate.php');
require_once('../start.php');

#:
if ( isset($_GET['m']) && 'tree' == $_GET['m'] ) {
	$id = $_GET['id'];
	$theTree = Sys::with('goats', 'tree');
	$resp = $theTree->bldTree($id);
	die($resp);
}

if ( isset($_GET['m']) && 'name' == $_GET['m'] ) {
	$id = $_GET['id'];
	$resp = know('name', $id, 'animals');
	die($resp);
}

if ( isset($_GET['m']) && 'invite' == $_GET['m'] ) {
	$id = $_GET['id'];
	$gens = $_GET['g'];
	$hrs = $_GET['h'];
	$res = invite_me($id, $hrs, $gens);
	die($res);
}



?>
