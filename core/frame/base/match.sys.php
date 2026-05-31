<?php /* Математическая логика */
#@ {?} Попадание числа в заданный интервал
function hit($X, $I1, $I2) {
	if ( nums($X, $I1, $I2) )
		return no($X < min($I1, $I2) || $X > max($I1, $I2)); }
#@ {?} Число не выходит за указанные пределы
function lim($X, $B1, $B2) {
	if ( nums($X, $B1, $B2) )
		return $X > min($B1, $B2) && $X < max($B1, $B2); }
#@ {?} Вес пары чисела
function more($X1, $X2 = 0) { if ( nums($X1, $X2) ) return $X1 > $X2; }
function less($X1, $X2 = 0) { if ( nums($X1, $X2) ) return $X1 < $X2; }
function from($X1, $X2 = 0) { if ( nums($X1, $X2) ) return $X1 >= $X2; }
function upto($X1, $X2 = 0) { if ( nums($X1, $X2) ) return $X1 <= $X2; }
#@ {?} Число имеет дробную часть
function dbl($X) { return ne(zero(frac($X))); }
#@ {?} Признаки взаимной делимости
function mul($X, $BY) { if ( ok($z, rest($X, $BY)) ) return 0 == $z; }
function even($X) { if ( inat($X) ) return zero($X & 1); }
#@ {?} Является ли число естественным для преобразования
function ciph($X) {
	if ( is(num($X, yes)) ) {
		return dgt($X) || (! stripos($X, 'x') && ! stripos($X, 'b')); }}
