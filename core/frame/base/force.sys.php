<?php /* Принудительное формирование данных */
#@ {k} Принуждение к правильному формату ключа массива
function my_key(& $X) {
	if ( is_int($X) ) return $X; else $Arr = array();
	if ( some($X) ) {
		$Arr[$X] = 0;
		return $X = key($Arr); }}
#@ {"} Текстовое значение или его альтернатива
function to_text($X, $ALT = str) {
	return itext($X) ? $X : (string) text($ALT); }
#@ {"} Принуждение к текстовому виду
function my_text(& $X, $ALT = str) { return $X = to_text($X, $ALT); }
#@ {"} Принуждение к удлинненной строке
function my_long(& $X, $Y, $BETWEEN = ' ', $ALT = str) {
	$res = long($X, $Y, $BETWEEN, $len);
	return $X = is($len) ? $res : to_text($ALT); }
#@ {"} Принуждение к префиксированию
function my_pre(& $X, $PREF, $IS_UNIQUE = no, $ALT = str) {
	return its($X, pre($X, $PREF, $IS_UNIQUE)) ? $X : my_text($X, $ALT); }
#@ {:} Обработанный ключ или его альтернатива
function to_clue($X, $AX, $ALT) {
	return iword($X, yes) && clue($X, $AX) ? $X : $ALT; }
#@ {:} Приведение к верному callable формату
function to_call($FX, $ALT = 'noop') { return icall($FX) ? $FX : to_call($ALT);}
