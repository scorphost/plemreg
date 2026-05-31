<?php /* Проверка одиночных аргументов */
#@ {&} Проверка на соответствие числу с преобразованием
function inum(& $X, $POLE = false, $EXT = no) {
	if ( its($X, num($X, $EXT)) ) {
		if ( no($POLE) ) return yes;
			else if ( nz === $POLE ) return $X != 0;
			else if ( pol === $POLE ) return $X > 0;
			else if ( neg === $POLE ) return $X < 0;
			else if ( poz === $POLE ) return $X >= 0;
			else if ( nez === $POLE ) return $X <= 0; }}
#@ {&} Проверка на соответствие целому числу с преобразованием
function inat(& $X, $POLE = false, $EXT = no) {
	if ( its($X, nat($X, $EXT)) ) return inum($X, $POLE, $EXT);	}
#@ {&} Проверка на разные текстовые сущности
function itext(& $X) { return its($X, text($X)); }
function inews(& $X) { return its($X, news($X)); }
function iword(& $X, $UNI = no) { return its($X, word($X, $UNI)); }
#@ {&} Проверка на наличие какой-либо длины
function icnt($X, & $CNT = null) { return its($CNT, cnt($X)); }
function ilen($X, & $LEN = null) { return its($LEN, len($X)); }
#@ {?} Проверка на счет
function rcnt($X, & $RET, $is_str = no) {
	list ($fx, $fl, $def) = $is_str ? array('is_string', 'l', str)
		: array('is_array', 'c', array());
	if ( $fx($X) ) list ($ans, $RET) = of($fl($X), $def);
		else $ans = $RET = cant;
	return $ans; }
function rlen($X, & $RET) {	return rcnt($X, $RET, yes); }
#@ {&} Проверка на фиксированную длину
function sure($AX, $CNT_EQ) {
	if ( is_array($AX) && inat($CNT_EQ, poz) )
		return count($AX) == $CNT_EQ; }
function note($X, $LEN_EQ) {
	if ( is_string($X) && inat($LEN_EQ, poz))
		return strlen($X) == $LEN_EQ; }
#@ {&} Проверка тип и на допустимую длину
function str($X) {
	return func_num_args() < 2 ? is_string($X)
		: size($X, func_get_arg(1), yes); }
function arr($X) {
	return func_num_args() < 2 ? is_array($X)
		: size($X, func_get_arg(1)); }
#@ {&} Проверка на единственный элемент массива
function isolo(& $AX) {
	$x = solo($AX, $fake);
	if ( ! $fake ) $AX = $x;
	return ! $fake; }
#@ {&} Проверка на наличие размера
function iwas(& $X) { return its($X, was($X)); }
#@ {&} Проверка на допустимый callable формат
function icall(& $FX) {
	if ( ! so($ans, call($FX)) )
		if ( str($FX, 3) && meet(dot, $FX, 1) )
			$ans = call(so($call, u($FX, dot)));
	if ( $ans && isset($call) ) $FX = $call;
	return $ans;
}
