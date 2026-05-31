<?php /*  Проверка */
#@ {&} Проверка полученного результата после передачи
function ok(& $VAR, $RES) { return is($VAR = $RES); }
#@ {&} Является ли результат пригодным для перезаписи
function its(& $X, $RES) {#`cмысловой аналог "it is"
	if ( so($ans, is($RES)) ) $X = $RES;
	return $ans; }
#@ {&} Анализ после возвращения на достаточность
function few($SIZE, $MUST) {
	return iwas($SIZE) && inat($MUST) ? $SIZE < $MUST : yes; }
#@ {?} Безопасная проверка наличия ключа в массиве
function clue($X) {
	if ( is(my_key($X)) ) $ans = yes; else return cant;
	if ( func_num_args() > 1 ) {
		$ax = func_get_arg(1);
		$is_free = func_num_args() > 2 && func_get_arg(2);
		$ans = is_array($ax) ? (ke($X, $ax) - $is_free) != 0 : cant; }
	return $ans; }
#@ {?} Проверка на начальное значение по конкретному банку
function we($X, & $RES = null ) {
	if ( is_array($X) ) return empty($X) ? no : done($RES = array()); }
function she($X, & $RES = null ) {
	if ( is_string($X) ) return strlen($X) > 0 ? done($RES = str) : no; }

function were($X, & $DATA = array()) { return we($DATA = $X); }
