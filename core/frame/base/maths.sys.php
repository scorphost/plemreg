<?php /* Математика */
#@ {#} Базовая арифметика
function sum($X1, $X2) {#`сложение
	if ( nums($X1, $X2) ) return $X1 + $X2; }
function pro($X1, $X2) {#`произведение
	if ( nums($X1, $X2) ) return $X1 * $X2; }
function sub($X1, $X2) {#`вычитание
	if ( nums($X1, $X2) ) return $X1 - $X2; }
function div($X, $BY) {#`деление
	if ( nums($X, $BY) ) return $BY != 0 ? $X / $BY : no; }
#@ {#} Остаток от деления пары чисел
function rest($X, $BY) {
	if ( nums($X, $BY) ) return $BY != 0 ? $X % $BY : no; }
#@ {#} Целая часть от деления пары чисел
function mod($X, $BY) { if ( ok($res, div($X, $BY)) ) return (int) $res; }
#@ {#} Извлечение беззнаковой дробной части
function frac($X) {
	if ( inum($X) ) return is_float(so($X, abs($X))) ? $X - floor($X) : 0; }
#@ {#} Работа со знаком числа
function ab($X) { if ( inum($X) ) return abs($X); } #`модуль
function re($X) { if ( inum($X) ) return -$X; } #`обратный знак
function sig($X1, $X2) { #`знак по образцу
	if ( nums($X1, $X2) ) return ($X1 < 0) != ($X2 < 0)	? -$X1 : $X1; }
#@ {#} Работа с целой частью числа (значение и его длина)
function intp($X) { if ( inum($X) ) return (int) floor(abs($X)); }
function lint($X) { return len(text(intp($X))); }
#@ {#} Изменение по модулю
function inc($X, $ON = 1) {
	if ( nums($X, $ON) && $ON > 0 ) return sig(abs($X) + $ON, $X); }
function dec($X, $ON = 1) {
	if ( nums($X, $ON) && $ON > 0 )
		return so($a, abs($X)) >= $ON ? sig($a - $ON, $X) : $X; }
#@ {#} Абсолютная дистанция между парой чисел
function dist($X, $Y = 0) { if ( nums($X, $Y) ) return abs($X - $Y); }
#@ {#} Расчет направления и количества прыжков между парой чисел
function foot($X, $Y, $DX = 1, $WITH_SELF = no) {
	if ( ! nums($X, $Y, $DX) ) return cant;
	if ( $X != $Y ) {
		if ( 0 == $DX || ($X < $Y && $DX < 0) ) return no;
			else if ( $X > $Y && $DX > 0 ) $DX = -$DX;
	} else $jmp = 0;
	if ( ! isset($jmp) ) $jmp = mod(dist($X, $Y), $DX);
	return $WITH_SELF ? inc($jmp) : $jmp; }
#@ Выбрать из всех чисел ближайшее к этому
function near($X, $AX) {
	if ( inum($X) ) { $Arr = kind(to::dist(own::num($AX, $X), $X), yes);
	return stop($Arr, $Res) ? $Res : own::eq($Arr, reset($Arr)); }}
#@ На сколько первое значение больше второго
function most($X1, $X2) {
	if ( ! nums($X1, $X2) ) return cant;
	if ( $X1 > $X2 ) return abs(dist($X1, $X2));
		else if ( $X2 > $X1 ) return re(abs(dist($X1, $X2)));
			else return 0; }
#@ Число с обязательным знаком
function pol($X) {
	return ( ok($n, num($X)) && $n < 0 ? str : '+' ). to_text($X); }

#@ Экспонента (показатель степени по модулю целого числа)
function exn($X, $Y, & $TAIL = 0) {
	if ( ! nats($X, $Y) ) return cant;
		else if ( have::less($X, $Y) || $Y > $X ) return no;
			else if ( 1 == $X ) return $TAIL = 0;
				else if ( $X == $Y ) return inc($TAIL = 0);
					else list ($res1, $k) = of(1, 0);
	do {
		$res0 = $res1;
		list ($res1, $k) = of($res1 * $Y, $k + 1);
	} while ( $res1 < $X );
	return so($TAIL, $X - $res0) ? $k - 1 : $k; }
