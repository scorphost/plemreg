<?php
class Route
{
	public static $Pull = array();
	public static $uri = str;
	public static function get($BAD_PAGE = page_404, $DEF_PAGE = homepage) {
		$uri = trim(lead(explode('?', $_SERVER['REQUEST_URI'], 2), no), slash);
		$lang = Site::lang();
		if ( ! l($uri) || too('index.php', $uri) )
			return self::away($DEF_PAGE);
				else $Path = u($uri, slash, yes);
		$Book = Env::cfg('book');
		if ( Site::lang($x = array_shift($Path)) ) {
			if ( we($Path) ) $x = array_shift($Path);
				else return self::away($DEF_PAGE);
		}
		if ( ke($x, $Book) ) {
			self::$Pull = $Path;
			$jp = j($Path, slash);
			self::$uri = she($jp) ? self::make($x, $jp)
				: self::make($x);
		} else $x = self::away($BAD_PAGE);
		return $x;
	}
	public static function uri() {
		if ( func_num_args() > 0 ) {
			$back = func_get_arg(0);
			if ( ! inat($back, neg) ) return
				self::$uri. slash. j(func_get_args(), slash);
					else $Parts = u(trim(self::$uri, slash), slash);
			$n = count($Parts) - abs($back);
			return $n < 1 ? slash : slash. j(slice($Parts, 0, $n), slash);
		} else return self::$uri;
	}
	#` Уход на корневую страницу
	public static function away($PAGE = homepage) {
		if ( ! you(Env::cfg('book', $PAGE), '+') ) {
			header(dd('Location'). self::make($PAGE));
			exit();
		} else return $PAGE;
	}
	#` Создание полноценного маршута
	public static function make() {
		return cufa('fpath', pool('/', Site::lang(), func_get_args()));
	}
	#~ pull()
	#~ pull(0)
	#~ pull(0, 'user') ? check
	#~ pull(0, u('add,edit')) ? in
	#~ pull(str) - returns val
	#~ pull(yes) - returns var
	#` Извлечение n-элемента из маршрута
	public static function pull() {
		static $val = '';
		static $var = 0;
		if ( so($na, func_num_args()) == 0 ) return self::$Pull;
			else $x = func_get_arg(0);
		if ( 1 == $na ) {
			if ( zl($x) ) return $val;
				else if ( yes($x) ) return $var;
					else if ( we($x) ) return to::o2v($x, self::$Pull);
						else return o2v($x, self::$Pull);
		} else {
			if ( ! ok($vx, o2v($x, self::$Pull)) ) return $vx;
				else $arg2 = func_get_arg(1);
			if ( we($arg2) ) {
				if ( ! ok($kx, look($vx, pure($arg2))) ) return $kx;
					else return done($val = $vx, $var = $kx + 1);
			} else if ( itext($arg2) ) return $vx === $arg2;
		}
	}
}
