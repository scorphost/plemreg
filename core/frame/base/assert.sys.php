<?php /* Сравнительный анализ */
#@ {&} Эквивалентность всех значений
function eq($X1, $X2) {##'a' == 1 == true
	for ( $n = func_num_args(), $i = 1; $i < $n; $i++ )
		if ( $X1 != func_get_arg($i) ) return no;
	return yes; }
#@ {&} Равенство всех значений
function twin($X1, $X2) {
	for ( $n = func_num_args(), $i = 1; $i < $n; $i++ )
		if ( $X1 !== func_get_arg($i) ) return no;
	return yes; }
function diff($X1, $X2) { return $X1 !== $X2; }
#@ {&} Равенство типов ('a' == 'b')
function eqt($X1, $X2) { return ctp($X1) == ctp($X2); }
#@ {?} Равенство десятичных чисел
function eqn($X1, $X2) { if ( nums($X1, $X2) ) return $X1 == $X2; }
function eqi($X1, $X2) { if ( nats($X1, $X2) ) return $X1 == $X2; }
#@ {?} Равенство числового знака
function eqp($X1, $X2) {
	if ( nums($X1, $X2) ) return 1 != ti($X1 < 0) + ti($X2 < 0); }
#@ {?} Равенство размеров
function eqc($X1, $X2) { return eqi(cnt($X1), cnt($X2)); }
function eql($X1, $X2) { return eqi(len($X1), len($X2)); }
#@ {?} Сравнение массивов по его элементам и расположению
function eqa($AX1, $AX2, $IS_DUP = no) {
	if ( arrs($AX1, $AX2) ) return $IS_DUP ? $AX1 === $AX2 : $AX1 == $AX2; }
#@ {?} Смысловое совпадение строк ('abc' == 'ABC')
function too($X1, $X2) {
	if (strs($X1, $X2)) return strtolower($X1) == strtolower($X2); }
#@ {&} Идентичность данных ('1.2' == 1.2, false != 0)
function same($X1, $X2) { return inum($X1) ? eqn($X1, $X2) : $X1 === $X2; }
