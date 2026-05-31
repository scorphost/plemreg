<?php /* Запись */
#@ Безопасное обновление массива собственными результатами
function fresh(& $AX, $DATA) {
	if ( ! arrs($AX, $DATA) ) return cant; else $Arr = $AX;
	foreach ( $DATA as $k => $v )
		if ( ke($k, $AX) ) $Arr[$k] = $v; else return cant;
	$AX = $Arr;
	return c($DATA); }
#@ Обновление по ключам или одного элемента или части списка
function put(& $AX, $KX, $VAL = null, $CAN_NEW = no) {
	if ( ! we($KX) ) return is_array($KX) ? 0 : cant;
	if ( ! is_array($VAL) ) $VAL = many($VAL, count($KX), yes);
		else if ( ! eqc($KX, $VAL) ) return cant;
	list ($Keys, $Vals, $rec) = of(av($KX), av($VAL), 0);
	foreach ( $Keys as $i => $kx ) {
		if ( clue($kx) ) {
			if ( ! ke($kx, $AX) && ! $CAN_NEW ) continue;
				else $AX[$kx] = $Vals[$i];
			$rec++; }}
	return $rec; }
#@ Безусловная запись по ключу того, что пришло
function lay(& $AX, $KX, $DATA = null, $CAN_NEW = no) {
	if ( un(clue($KX)) ) return cant;
	if ( ! ke($kx, $AX) && ! $CAN_NEW ) return no;
		else return done($AX[$KX] = $DATA); }
#@ Записать новый ключ на место старого
function rec(& $AX, $KX1, $KX2) {
	if ( ! clue($KX1, $AX) or ! clue($KX2, $AX, yes) ) return no;
		else $Keys = array_keys($AX);
	$Keys[ array_search($KX1, $Keys, yes) ] = $KX2;
	return done($AX = array_combine($Keys, $AX)); }
#@ Обмен элементами/ключами массива
function vxch(& $AX, $KX1, $KX2) {
	if ( so($ans, clue($KX1, $AX) && clue($KX2, $AX)) )
		swap($AX[$KX1], $AX[$KX2]);
	return $ans; }
function kxch(& $AX, $KX1, $KX2) {
	if ( so($ans, clue($KX1, $AX) && clue($KX2, $AX)) ) {
		$Keys = array_keys($AX);
		swap($Keys[ array_search($KX1, $Keys, yes) ]
			, $Keys[ array_search($KX2, $Keys, yes) ]);
		$AX = array_combine($Keys, $AX); }
	return $ans; }
#@ Создание и упорядочивание массива с такими же ключами как и у образца
function fix(& $AX1, $AX2, $REORD = no, $CLEAN = no) {
	if ( ! arrs($AX1, $AX2) || ! c($AX2) ) return cant;
		else if ( $REORD ) $Order = array_keys($AX2);
	foreach ( $AX1 as $k => $v ) {
		if ( ke($k, $AX2) ) unset($AX2[$k]);
			else if ( $CLEAN ) unset($AX1, $k); }
	foreach ( $AX2 as $k => $v ) $AX1[$k] = $v;
	if ( $REORD ) {
		list ($Copy, $AX1) = of($AX1, array());
		foreach ( $Order as $k ) $AX1[$k] = $Copy[$k]; }}
#@ Углубить значение
function deep(& $AX, $KX) {
	$new_key = func_num_args() > 2;
	if ( $new_key && ! clue(so($k, func_get_arg(2))) ) return cant;
		else $lnk = & bind($AX, $KX, $fake);
	if ( $fake ) return no; else $val = $lnk;
	return done($lnk = $new_key ? array($k => $val) : array($val)); }
#@ Связать два списка в один результирующий список
function tie(& $ARR, $AX1, $PK = 11, $PV = 12) {
	if ( un($ARR) ) $ARR = array();
	if ( ! arrs($ARR, $AX1) ) return cant;
		else if ( ! we($AX1) ) return lie($AX1);
	$is_dual = func_num_args() > 4;
	if ( ! $is_dual && 11 == $PK && 12 == $PV ) {
		foreach ( $AX1 as $k => $v ) $ARR[$k] = $v;
		return yes; }
	if ( ! nats($PK, $PV) ) return cant;
	if ( $is_dual ) {
		if ( ! we(so($AX2, func_get_arg(4))) ) return lie($AX2);
			else if ( ! eqc($AX1, $AX2) ) return cant;
	} else {
		$AX2 = & $AX1;
		if ( func_num_args() == 3 && $PK != 11 ) $PV = 11; }
	switch ( $PK ) {
		case 11: $Keys = ak($AX1); break;
		case 12: $Keys = & $AX1; break;
		case 21: $Keys = ak($AX2); break;
		case 22: $Keys = & $AX2; break;
		default: return cant; }
	if ( ! are::clue($Keys) ) return cant;
	switch ( $PV ) {
		case 11: $Vals = ak($AX1); break;
		case 12: $Vals = & $AX1; break;
		case 21: $Vals = ak($AX2); break;
		case 22: $Vals = & $AX2; break;
		default: return cant; }
	foreach ( array_combine($Keys, $Vals) as $k => $v ) $ARR[$k] = $v;
	return yes; }
#@ {,} Вставка массива на место указанного ключа в исходном массиве
function ins(& $AX1, $AX2, $KX) {
	if ( ! ok($i, k2i($KX, $AX1)) ) return $i;
		else if ( ! ok($ans, free(ak($AX2), $AX1)) ) return $ans;
	list($AX1, $Tail) = two($AX1, $i);
	tie($AX1, $AX2);
	tie($AX1, $Tail);
	return count($AX2); }
#@ {} Смешивание элементов по заданным правилам
function mix(& $AX1, $AX2, $RULE = 'best') {
	if ( ! arrs($AX1, $AX2) || ! call($RULE) ) return cant;
	if ( ! c($AX1) ) return done($AX1 = $AX2);
		if ( ! c($AX2) ) return yes;
	foreach ( $AX1 as $k => & $v ) {
		if ( ke($k, $AX2) ) {
			$v = best($v, $AX2[$k]);
			unset($AX2[$k]); }}
			unset($v);
	foreach ( $AX2 as $k => $v ) $AX1[$k] = $v;
	return yes; }
#@ Запись по многомерному ключу
function intr(& $ARR, $KEY3D, $VAL) {
	if ( un($ARR) ) $ARR = ax();
	if ( ! arr($ARR) ) return no; else $v = & $ARR;
	foreach ( u($KEY3D, slash) as $k ) {
		if ( ! ke($k, $v) ) $v[$k] = ax();
		$v = & bind($v, $k, $fake); }
	return done($v = $VAL); }
