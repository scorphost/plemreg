<?php
/* Ассистент по БД */
class DBH
{
	#@ Список таблиц
	public static function tbls() {
		$Res = ax();
		foreach ( DB::run('SHOW TABLES') as $row => $Line )
			$Res[] = reset($Line);
		return $Res;
	}
	#@ Список полей
	public static function flds($TBL) {
		$Res = ax();
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line )
			$Res[] = $Line['Field'];
		return $Res;
	}
	#@ Список полей с комментариями
	public static function fcom($TBL) {
		$Res = ax();
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line ) {
			$fld = $Line['Field'];
			$Com = DB::run("SELECT COLUMN_COMMENT FROM information_schema.`COLUMNS` WHERE TABLE_NAME = '${TBL}' AND COLUMN_NAME = '${fld}'");
			$rem = isset($Com[0]['COLUMN_COMMENT'])
				? $Com[0]['COLUMN_COMMENT'] : str;
			$Res[$fld] = $rem;
		}
		return $Res;
	}
	#@ Вернуть поля с описанием их типов
	public static function ftyp($TBL) {
		$Res = ax();
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line ) {
			$fld = $Line['Field'];
			$Opt = array($Line['Type']);
			if ( $Line['Null'] != 'YES' ) {
				$d1 = 'UNI' == $Line['Key'] ? '+' : str;
				if ( is($Line['Default']) ) $Opt[] = $d1. $Line['Default'];
			} else $Opt[] = 'null';
			$Res[$fld] = j($Opt, qs('->'));
		}
		return $Res;
	}
	#@ Сравнить со схемой и вернуть поля, которых не хватает
	public static function lack($HAVE, $TBL) {
		$Res = ax();
		foreach ( self::run("EXPLAIN `${TBL}`") as $row => $Line )
			if ( $Line['Null'] == 'YES' ) $Res[ $Line['Field'] ] = null;
				else if ( is($Line['Default']) ) $Res[ $Line['Field'] ]
					= $Line['Default'];
		return lack($HAVE, $Res);
	}
	#@ Более наглядный EXPLAIN
	function expl($DB = skip) {
		if ( ! skip($DB) ) $db = a($DB);
		DB::run('SET NAMES utf8');
		$Data = DB::run('SHOW TABLES');
		$Tbls = array();
		foreach ( $Data as $Arr ) $Tbls[] = reset($Arr);
		$Res = array();
		foreach ( $Tbls as $name ) {
			if ( ! skip($DB) && ! in($name, $db) ) continue;
			$Data = DB::run("EXPLAIN $name");
			$row = str;
			foreach ( $Data as $i => $Fields ) {
				$row = $fld = $Fields['Field'];
				if ( $Fields['Null'] != 'NO' ) $row = '*'. $row;
				if ( $Fields['Key'] == 'UNI' ) $row = '!'. $row;
				if ( $Fields['Key'] == 'PRI' ) $row = '@'. $row;
				$row.= dot. $Fields['Type'];
				if ( $Fields['Extra'] == 'auto_increment' ) $row.= '++';
				$row.= wear($Fields['Default'], '[');
				$Com = DB::run("SELECT COLUMN_COMMENT FROM "
					."information_schema.`COLUMNS` WHERE TABLE_NAME"
					." = '$name' AND COLUMN_NAME = '$fld'");
				if ( isset($Com[0]['COLUMN_COMMENT'])
					&& l($Com[0]['COLUMN_COMMENT']) )
						$row.= wear($Com[0]['COLUMN_COMMENT'], '`');
				$Res[$name][$i + 1] = $row; }}
		return $Res;
	}
 }
