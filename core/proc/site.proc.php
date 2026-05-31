<?php
class Site {
	public static $lang = null;
	public static $mlang = null;
	public static function lang() {
		$List = Env::cfg('langs');
		$Langs = ak($List);
		if ( func_num_args() == 0 ) {
			if ( set(self::$lang) ) return self::$lang;
			if ( so(self::$mlang, arr($Langs, 2)) ) {
				if ( ! in(Cook::get('lang'), $Langs, yes, $lang) )
					$lang = look(yes, $List);
			} else $lang = solo($Langs);
			Cook::set('lang', $lang);
			return self::$lang = $lang;
		} else {
			if ( here($lang = func_get_arg(0), $Langs) )
				return done(Cook::set('lang', self::$lang = $lang));
					else return no;
		}
	}
	public static function title($TITLE) {
		Env::stor('site', 'lang', self::$lang);
		Env::stor('site', 'title', $TITLE);
	}
}
