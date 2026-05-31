<?php
#! Env - станет var, Sys станет Env
class Env
{
	public static $F2C = array();
	public static $C2O = array();
	#@ Получение данных из глобальных конфигурационных массивов
	public static function cfg($VAR, $SECT = skip, $OPT = skip) {
		$Data = $GLOBALS['Cfg'. cap($VAR)];
		if ( skip($SECT) ) return $Data;
		$key = skip($OPT) ? $SECT : j(of($SECT, $OPT), slash);
		$res = & bind($Data, $key, $fake);
		return $fake ? cant : $res;
	}
	#@ Получение данных из глобального хранилища данных
	public static function data($SECT = skip, $KEY = skip) {
		if ( skip($SECT) ) return $GLOBALS['APP_VARS'];
			else $Data = $GLOBALS['APP_VARS'];
		$key = skip($KEY) ? $SECT : j(of($SECT, $KEY), slash);
		$res = & bind($Data, $key, $fake);
		return $fake ? cant : $res;
	}
	public static function stor($SECT, $KEY, $VAL = null) {
		$Data = & $GLOBALS['APP_VARS'];
		if ( ! ke($SECT, $Data) ) $Data[$SECT] = array();
		$link = & bind($Data, $SECT, $fake);
		return ! $fake && done($link[$KEY] = $VAL);
	}
	public static function pack($SECT, $KEYS) {
		$Data = & $GLOBALS['APP_VARS'];
		if ( ! ke($SECT, $Data) ) $Data[$SECT] = array();
		$link = & bind($Data, $SECT, $fake);
		if ( ! $fake ) foreach ( $KEYS as $k => $v ) $link[$k] = $v;
		return ! $fake;
	}
}
