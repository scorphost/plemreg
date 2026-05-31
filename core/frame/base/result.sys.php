<?php /* Обработка результата */
#@ {=} Безусловная присвоение результата с передачей
function so(& $VAR, $RES) { return $VAR = $RES; }
#@ {?} Принудительная инверсия
function not($VAL) { return ! $VAL; }
#@ {?} Булева инверсия
function ne($X) { if ( is_bool($X) ) return ! $X; }
#@ {=} Выдача результата на основе логической дилеммы
function let($RES, $IF_YES = yes, $IF_NO = no) {
	return (bool) $RES ? $IF_YES : $IF_NO; }
#@ {=} Ничего или ложно
function lie($RES, $TYPE = array()) {
	if ( gettype($RES) == gettype($TYPE) ) return false; }
#@ {=} Ничего или истинно
function good($RES, $TYPE = array()) {
	if ( gettype($RES) == gettype($TYPE) ) return true; }
#@ {=} Ничего или пусто
function poor($RES, $TYPE = array()) {
	if ( gettype($RES) == gettype($TYPE) ) return clr($TYPE); }
#@ {=} Наложение своих значений на полученный результат
function bad($RES, $IF_BAD = null, $ON_GOOD = skip) {#` ...на плохой/хороший
		if ( skip($ON_GOOD) ) $ON_GOOD = & $RES;
		return is($RES) ? $ON_GOOD : $IF_BAD; }
#` ...на неопределенный/ложный/хороший результат
function on($RES, $IF_NO = no, $IF_UN = null, $ON_GOOD = skip, $THEN = skip) {
	if ( skip($ON_GOOD) ) $ON_GOOD = & $RES;
	if ( non($RES) ) return no($RES) ? $IF_NO : $IF_UN;
		else return skip($THEN) ? $ON_GOOD : $THEN($ON_GOOD); }
#@ {=} Выбор из пары того, чей результат лучше
function best($A, $B, $IS_REV = no, $B_IS_MAIN = no, & $which = 0 ) {
	if ( $A === $B ) {## бессмысленно
		$which = 0;
		return $A; }
	list ($r1, $r2) = $IS_REV ? of('B', 'A') : of('A', 'B');
	if ( dgt($A) && dgt($B) ) { ## Числа можно сравнить относительно нуля
		if ( 0 == $A ) {
			$which = $IS_REV ? 0 : 1;
			return $$r2;
		} else if ( 0 == $B ) {
			$which = $IS_REV ? 1 : 0;
			return $$r1;
		}}
	if ( gettype($A) == gettype($B) ) {
		if ( miss($A) ) {
			$which = $IS_REV ? 0 : 1;
			return $$r2;
		} else if ( miss($B) ) {
			$which = $IS_REV ? 1 : 0;
			return $$r1;
		} else $which = $B_IS_MAIN ? 1 : 0;
		return $B_IS_MAIN ? $B : $A;
	} else $Args = array('A' => func_get_arg(0), 'B' => func_get_arg(1));
	foreach ( $Args as $ptrVar => $eachArg ) {
		$ptrType = 'tp'. $ptrVar; ## 'A'~>'tpA'
		if ( un($$ptrVar) ) $$ptrType = 0;
			else if ( is_bool($$ptrVar) ) $$ptrType = 1;
				else if ( info($$ptrVar) ) $$ptrType = 2;
					else $$ptrType = 3; }
	if ($tpA > $tpB) { ## доминирование типа
		$which = $IS_REV ? 1 : 0;
		return $$r1;
	} else if ($tpB > $tpA) {
		$which = $IS_REV ? 0 : 1;
		return $$r2;
	} else $which = $B_IS_MAIN ? 1 : 0;
	return $B_IS_MAIN ? $B : $A; }
#@ {=} Худший результат из всех возможных
function worst($X1, $X2) {
	$res = & $X1;
	for ( $n = func_num_args(), $i = 1; $i < $n; $i++ ) {
		$x = func_get_arg($i);
		best($res, $x, yes, no, $which);
		if ( 1 == $which ) $res = & $x; }
	return $res; }
#@ Подтверждение результата
function can($X, $RES, $CANT = _, $PROC = 'noop') {
	if ( ! call($PROC) ) return cant;
		else if ( yes($RES) ) return $PROC($X);
			else if ( ok($X, $RES) ) return $X;
				else return $PROC($CANT); }
#@ Фильтр по типу
function only($X, $TYPE = array()) { if ( eqt($X, $TYPE) ) return $X; }
#@ Является ли результат достаточным, чтобы его обрабатывать дальше
function stop($RES, & $RET = cant) {
	if ( is($RES) ) {
		$ans = miss($RES);
		$RET = clr($RES);
	} else $ans = yes;
	return $ans; }
#@ Показать в итоге истину
function done() { return yes; }
function ino() { return no; }
#@ Наложение на пустой/годный результат
function res($RES, $IF_MISS, $ON_FULL = skip) {
	if ( is($RES) ) {
		if ( miss($RES) ) return $IF_MISS;
			else return skip($ON_FULL) ? $RES : $ON_FULL;
	} else return $RES; }

class flt {
	public static function __callStatic($FX, $ARGS) {
		list ($res, $if_not, $on_good) = safe($ARGS, 3);
		if ( non($FX($res)) ) return $if_not;
		if ( skip($on_good)) return $res;
			else return call($on_good) ? $on_good($res) : $res;
	}
}
