<?php /* Наборы алгоритмов для разработки */
#@ {&} Перевод в двоичный формат
function bit(& $VAR) { return $VAR = (bool) $VAR; }
#@ {=} Обмен содержимым
function swap(& $A, & $B) { list ($A, $B) = array($B, $A); }
#@ {=} Восстановление начального значения
function clr(& $VAR) {
 	$nul = null;
 	return $VAR = type($nul, $VAR); }
#@ {=} Выдача значения перед стиранием
function cls(& $X) {
	$res = $X;
	clr($X);
	return $res; }
#@ Специальный ключ (по-умолчанию)
function def(& $AX, $VAL) { if ( arr($AX) ) return done($AX[str] = $VAL); }
#@ {=} Переопределение для случая неопределенности
function ini(& $VAR, $ALT) { return $VAR = set($VAR) ? $VAR : $ALT; }
#@ {&} Является ли аргумент неучитываемым
function skip($X) { return skip === $X; }
#@ {,} Перечисление аргументов
function of() { return func_get_args(); }
#@ {,} Сброс ключей массива
function pure(& $AX) { if ( its($AX, av($AX)) ) return $AX; }
#@ {,} Пустая операция
function noop($X = null) { return $X; }
#@ Временный карман для значения
function x() {
	static $is_array = no;
	static $x = null;
	if ( func_num_args() != 0 ) {
		$is_array = is_array($val = func_get_arg(0));
		$x = $is_array ? serialize($val) : $val;
	} else return $is_array ? unserialize($val) : $val; }
#@ {,} Безопасная передача потока аргументов в нужном количестве
function safe($AX, $NEED = 1, $DEF = null) {
	if ( ! inat($NEED, pol) ) return cant;
		else if ( ! icnt($AX, $ca) ) list ($Vals, $ca) = of(a($AX), 1);
			else $Vals = array_values($AX);
	return $ca >= $NEED ? $Vals : merge($Vals
		, array_fill($ca, $NEED - $ca, $DEF)); }
#@ Зачистка аргументов с конца от null значений
function arg($ARGS) {
	for ( $i = count($ARGS); $i > 1; $i-- )
		if ( un(end($ARGS)) ) array_pop($ARGS);
	return $ARGS; }
#@ В каком виде пришли данные и в каком виде их отдавать
function spot($X, & $type = _) {
	if ( ! data($X) ) return cant; else $type = true;
	if ( ! info($X) ) return $X;
	if ( dgt($X) ) $type = is_int($X) ? 'ti' : 'td'; else $type = false;
	return u($X, str); }
function glue($Data, $type) {
	if ( yes($type) ) return $Data; else $res = implode(str, $Data);
	return no($type) ? $res : bad(num($res), no, $type($res)); }
#@ Стоит ли пара аргументов по-росту
function high(& $X1, & $X2, $INV = no) {
	if ( all::bank($X1, $X2) ) {
		$fx = arr($X1) ? 'count' : 'strlen';
		list ($r1, $r2) = of::$fx($X1, $X2);
	} else if ( all::dgt($X1, $X2) ) list ($r1, $r2) = of($X1, $X2);
	if ( ! isset($r1) ) return cant;
	if ( $INV ) swap($r1, $r2);
	if ( so($ans, ($r2 < $r1)) ) swap($X1, $X2);
	return ! $ans; }
#@ Циклический отладчик
function i($N = null) {
	static $i = 1;
	if ( func_num_args() == 0 ) $i = 1;
		else if ( ! inat($N, pol) ) die('invalid loop...');
			else return $N == $i++; }
#@ Логическое переопределение значения
function hello($OLD, $VAL, & $CHNG = no) {
	return ! skip($VAL) && so($CHNG, $OLD !== $VAL) ? $VAL : $OLD;
}
## Замена пустой строки на что-то альтернативное
function lone(& $X, $ALT) { return zl($X) ? $X = to_text($ALT) : $X; }

#@ Бинарный флаг
function bf(& $X) { return $X = ! $X; }
