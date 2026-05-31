<?php
class Req
{
	private static $Data = array();
	private static $is_post;
	##(!) Извлечение пакета данных запроса
	protected static function init() {
		if ( self::$is_post = 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			Sess::set('post_data', to::addslashes($_POST));
			if ( isset($_FILES['filename']['name'])
				&& she($_FILES['filename']['name'] )
				&& some(so($ext, fext($_FILES['filename']['name']))) ) {
				$name = time(). dot. $ext;
				if ( copy($_FILES['filename']['tmp_name']
					, path('tmp', $name, yes)) )
					Sess::set('file', $name);
			}
			if ( isset($_FILES['filename1']['name'])
				&& she($_FILES['filename1']['name'] )
				&& some(so($ext, fext($_FILES['filename1']['name']))) ) {
				$name = time(). dot. $ext;
				if ( copy($_FILES['filename1']['tmp_name']
					, path('tmp', $name, yes)) )
					Sess::set('file1', $name);
			}
			if ( isset($_FILES['filename2']['name'])
				&& she($_FILES['filename2']['name'] )
				&& some(so($ext, fext($_FILES['filename2']['name']))) ) {
				$name = (time() + 1). dot. $ext;
				if ( copy($_FILES['filename2']['tmp_name']
					, path('tmp', $name, yes)) )
					Sess::set('file2', $name);
			}
			home();
		} else {
			self::$is_post = we(so(self::$Data, ta(Sess::get('post_data'))));
			if ( ! self::$is_post ) self::$Data = to::addslashes($_GET);
		}
	}
	public static function is_post() {
		if ( un(self::$is_post) ) self::init();
		return self::$is_post;
	}
	##(!) Извлечение пакета POST-данных по групповому ключу
	public static function what() {
		list ($Args, $Data, $Res) = of(func_get_args(), self::$Data, array());
		list ($grp, $na) = of(array_shift($Args), func_num_args());
		$ok = ke($grp, $Data);
		if ( $na-- == 1 ) {
			if ( $ok ) {
				unset($Data[$grp]);
				return $Data;
			} else return array();
		}
		if ( $ok ) {
			unset($Data[$grp]);
			return need($Args, $Data);
		} else return safe(array(), $na);
	}
	public static function args($MK, $CNT, $DEF = null) {
		return safe(av(cufa(u('Req,what'), of($MK))), $CNT, $DEF);
	}
	##(!) Извлечение пакета GET-данных по мастер ключу
	public static function fetch(& $RES, $MK) {
		if ( self::is_post() ) return no;
			else if ( un($RES) ) $RES = array();
		list ($val, $Data) = of(reset($MK), self::$Data);
		$key = key($MK);
		if ( ! ke($key, $Data) || $Data[$key] !== $val ) return no;
			else unset($Data[$key]);
		if ( we($RES) ) foreach ( $RES as $k => & $v ) $v = who($k, $Data);
			else $RES = $Data;
		return yes;
	}
	public static function get(& $RES, $KEY = skip) {
		if ( self::is_post() ) return no;
		if ( skip($KEY) ) we($RES = self::$Data);
			else $RES = who($KEY, self::$Data, $fake);
		return ! $fake;
	}
}
