<?php /* Данные на основе случайностей */
#@ Случайное булево значение
function rb() { return (bool) mt_rand(0, 1); }
#@ Случайная цифра
function rd($POS_ONLY = no) { return mt_rand($POS_ONLY ? 1 : 0, 9); }
#@ Случайное x-значное число
function rn($LINT = 2, $SIGNED = no) {
	if ( ! inat($LINT, pol) ) return cant;
		else if ( $SIGNED ) $res = rb() ? str : '-';
			else $res = str;
	for ( $i = 0; $i < $LINT; $i++ ) $res.= rd(0 == $i);
	return (int) $res; }
#@ Случайное числов интервале
function rnd($X1 = 0, $X2 = PHP_INT_MAX) {
	if ( nats($X1, $X2) ) return $X1 != $X2 ? mt_rand($X1, $X2) : $X1; }
#@ Случайный элемент массива
function one($AX, & $fake = no) {
	if ( ! we($AX) ) return nil($fake = yes); else $Arr = av($AX);
	return isolo($AX) ? $AX : $Arr[ mt_rand(0, count($Arr) - 1) ]; }
#@ Случайное N-количество элементов
function any($AX, $N = 2, $HASH = no) {
	if ( ! icnt($AX, $cnt) || ! inat($N, pol) ) return cant;
		else if ( 0 == $cnt || $cnt < $N ) return no;
	if ( $cnt-- == $N ) return $AX;
		else list ($Keys, $Res) = of(ak($AX), ax());
	do {
		$k = $Keys[ mt_rand(0, $cnt) ];
		if ( ! $HASH && ke($k, $Res) ) continue;
			else list ($N, $Res[$k]) = of($N - 1, $AX[$k]);
	} while ( $N > 0 );
	return $Res; }
#@ Случайный аргумент
function ra() { return one(func_get_args()); }
#@ Случайная последовательность из х-элементов массива
function line($X1 = 1, $X2 = 2) {
	if ( ! nats($X1, $X2) ) return cant;
		else if ( $X1 > $X2 ) swap($X1, $X2);
	if ( $X1 < 1 ) return cant; else $Data = slice(func_get_args(), 2);
	if ( ! we($Data) ) return cant; else $Res = array();
	for ( $i = 0, $n = rnd($X1, $X2); $i < $n; $i++ ) $Res[] = one($Data);
	return $Res; }
