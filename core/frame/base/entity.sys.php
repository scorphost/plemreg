<?php /* Данные как определенная сущность */
#@ {#} Как числовое значение
function num($X, $EXT = no) {
	if ( dgt($X) ) return $X;
		else if ( is_string($X) ) $str = trim($X);
			else return cant;
	if ( is_numeric($str) ) {
		if ( is(strpos($str, '.')) || is(stripos($str, 'E')) ) {
			$res = (float) $str;
		} else if ( is(stripos($str, 'x')) ) {
			if ( $EXT ) $res = hexdec($str);
		} else $res = (int) $str;
	} else if ( $EXT && preg_match('/^0x[0-9a-f]+/i', $str) ) {
		$res = hexdec($str);
	} else if ( $EXT && preg_match('/^0b[01]+/i', $str) )
		$res = bindec($str);
	return isset($res) ? $res : no; }
#@ {#} Как целоисчисленное значение
function nat($X, $EXT = no) {
	if ( is_int($X) ) return $X;
	if ( ok($num, num($X, $EXT)) ) {
		if ( is_int($num) ) return $num;
			else return $num != floor($num) ? no : (int) $num; }}
#@ {"} Как текстовое значение
function text($X) {#` Все что может стать текстом
	if ( is_string($X) ) return $X;
		else if ( dgt($X) ) return (string) $X; }
#@ {"} Как текст с положительной длиной
function news($X) { if ( itext($X) && l($X)) return $X; }
#@ {"} Как значимый текст
function word($X, $UNI = no) {
	if ( itext($X) && l(so($str, trim($X))) ) return $UNI ? low($str) : $str; }
#@ {=} Как единственный элемент массива
function solo($AX, & $fake = no) {
	if ( sure($AX, 1) ) return reset($AX); else $fake = yes; }
