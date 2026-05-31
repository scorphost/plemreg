<?php /* Типы */
#@ {=} Преобразование типов
function nil() { return null; }
function tb($VAL) { return (bool) $VAL; }
function ti($VAL) { return (int) $VAL; }
function td($VAL) { return (float) $VAL; }
function ts($VAL) { return (string) $VAL; }
function ta($VAL) { return (array) $VAL; }
function tc($VAL) { return (object) $VAL; }
#@ {=} Приведение типов
function type(& $VAR, $MASTER) {
	settype($VAR, gettype($MASTER));
	return $VAR; }
#@ {"} Аббревиатура типа
function ctp($VAL) { return strtolower( substr(gettype($VAL), 0, 1) ); }
#@ Упаковка данных с сохранением типа
function bin($X, $DE = no, & $FAKE = no) {
	if ( $DE ) {
		if ( ! ilen($X, $l) || $l < 2 ) return nil($FAKE = yes);
			else $tp = $X{0};
		if ( ! in($tp, 'a,o,n,b,d,i,s') ) nil($FAKE = yes);
		if ( ! ok($res, base64_decode(cut($X, 1)))) return nil($FAKE = yes);
		if ( ! in($tp, 'o,a') ) {
			$fx = $tp != 'n' ? 't'. $tp : 'nil';
			$res = $fx($res);
		} else $res = unserialize($res);
	} else {
		if ( is_resource($X) ) return cant; else $tp = ctp($X);
		if ( the($X) || arr($X) ) $X = serialize($X);
		if ( ! info($X) ) $X = ts((int) $X);
		$res = $tp. base64_encode((int) $X); }
	return $res; }
