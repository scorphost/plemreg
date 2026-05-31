<?php
class Render {
	public static function show($TMPL, $MASTER) {
		if ( ! $MASTER ) {
			Env::stor('root', 'content', tell($TMPL, Env::data()));
			return tell(Sys::tmpl('root'), Env::data());
		} else {
			return tell($TMPL, Env::data());
		}
	}
	## Добавить в загловок дополнительный текст
	public static function head() {

	}
}
