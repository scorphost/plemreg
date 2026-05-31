<?php
class Sess
{
	public static function get() {
		if ( func_num_args() == 0 ) return $_SESSION;
			else list($Res, $Keys) = of(array(), func_get_arg(0));
		foreach ( a($Keys) as $k )
			$Res[] = clue($k, $_SESSION) ? $_SESSION[$k] : null;
		return arr($Keys) ? $Res : solo($Res); }
	public static function set($KEY, $VAL) {
		$ans = clue($KEY, $_SESSION);
		if ( un($ans) || inum($KEY) ) return no;
			else return done($_SESSION[$KEY] = $VAL); }
	public static function del() {
		$Keys = func_num_args() == 0 ? ak($_SESSION) : func_get_arg(0);
		foreach ( a($Keys) as $k )
			if ( clue($k, $_SESSION) ) unset($_SESSION[$k]); }
}

class Cook
{
	public static function get() {
		if ( func_num_args() == 0 ) return $_COOKIE;
			else list($Res, $Keys) = of(array(), func_get_arg(0));
		foreach ( a($Keys) as $k )
			$Res[] = clue($k, $_COOKIE) ? $_COOKIE[$k] : null;
		return arr($Keys) ? $Res : solo($Res); }
	public static function set($KEY, $VAL, $EXPIRE = 0, $PATH = slash) {
		$ans = clue($KEY, $_COOKIE);
		if ( un($ans) || inum($KEY) ) return no;
			else setcookie($KEY, to_text($VAL), $EXPIRE, $PATH);
		return done($_COOKIE[$KEY] = $VAL);
	}
	public static function del() {
		$Keys = func_num_args() == 0 ? ak($_COOKIE) : func_get_arg(0);
		foreach ( a($Keys) as $k ) {
			if ( clue($k, $_COOKIE) ) {
				setcookie($k, null, -1, slash);
				unset($_COOKIE[$k]); }}}
}

class DB
{
	public static $obj;
	public static function obj($OBJ) { self::$obj = $OBJ; }
	public static function run($SQL, $TYPE = array(), & $FAKE = no) {
		if ( self::$obj->ready() ) {
			return self::$obj->run($SQL, $TYPE, $FAKE); }}
	public static function base() { return self::$obj->base(); }
	public static function args($SQL, $CNT) {
		return safe(av(self::run($SQL, _)), $CNT); }
	public static function last_id() { return self::$obj->last_id(); }
	public static function trunc($TBLS, $DB = 'plemreg') {
		$Rows = ax();
		foreach ( u($TBLS) as $table ) $Rows[] = sp('TRUNCATE')
			. "`${DB}`.`${table}`";
		// vd(j($Rows, sp(';')));
		return done(DB::run(j($Rows, sp(';')))); }
}
