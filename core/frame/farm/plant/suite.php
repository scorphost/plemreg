<?php
#@
function esql($SQL = _) {
	static $table = null;
	if ( un($table) ) $table = $GLOBALS['CfgDB']['base'];
	$erc = error_reporting();
	error_reporting(0);
	$dbh = new mysqli('localhost', 'root', 'root', $table);
	if ( ! the($dbh) ) {
		die(cr. sprintf('MySQL error %s! %s'
				, $dbh->connect_errno, $dbh->connect_error));
	} else error_reporting($erc);
	$qh = $dbh->query($SQL);
	if ( the($qh) ) {
		$res = array();
		while ( $Row = $q->fetch_assoc() ) $res[] = $Row;
		$q->free();
	} else $res = $qh;
	$dbh->close();
	return $res; }
#@ Выборка уровня для ключей типа roll
function as_lvl($N = 2) {
	if ( inat($N, pol) ) return 1 == $N ? '^[^\/]+$'
		: sprintf('^([^\/]+\/){%s}[^\/]+$', $N - 1); }
