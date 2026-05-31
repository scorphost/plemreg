<?php
class Pass {
	protected static $hash;
	protected static $secret;
	public static function hash($PASS) {
		$res = md5(crc32(base64_encode((strrev($PASS) + salt))));
		return self::$hash = $res;
	}
	public static function secret() {
		$res = j(any(array_merge(abc('az'), abc('AZ'), abc('09')), 16)). '==';
		return self::$secret = $res;
	}
	public static function token() {
		$tk1 = md5(self::$secret. self::$hash);
		$Data = ch($tk1) ;
		shuffle($Data);
		list ($Res, $k, $j, $tk2) = of(ax(), 1, 0, str);
		for ( $i = 0; $i < 16; $i++ ) {
			$tk2.= one($Data);
			if ( $k++ > 3 ) {
				$Res[$j++] = $tk2;
				list ($k, $tk2) = of(1, str); }}
		return j($Res, '-');
	}
	public static function create($POST) {
		$pass = who('pass1', $POST);
		unset($POST['pass1'], $POST['pass2']);
		$Data['pass'] = self::hash($pass);
		do { $secret = self::secret(); }
			while ( exst('secret', $secret, 'users') );
		do { $token = self::token(); }
			while ( exst('token', $token, 'users') );
		put($Data, u('secret,token'), of($secret, $token), yes);
		$sql = xsql::ins(merge($Data, $POST), 'users');
		return (bool) DB::run($sql);
	}
	public static function change($POST) {
		if ( ! she(so($pass, who('pass1', $POST))) ) return cant;
			else unset($POST['pass1'], $POST['pass2']);
		$Upd['pass'] = self::hash($pass);
		$sql = xsql::upd($Upd, app::x('iUser')->id(), 'users');
		return (bool) DB::run($sql);
	}
}
