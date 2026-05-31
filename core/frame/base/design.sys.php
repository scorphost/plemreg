<?php /* Оформление */
#@ Наложение массивов (значения являются указателями на значения другого)
function over($LIST, $DATA) {
	if ( ! pair($LIST, $DATA, $Res) ) return $Res;
	foreach ( $LIST as $k1 => $k2)
		$Res[$k1] = clue($k2, $DATA) ? $DATA[$k2] : $k2;
	return $Res; }
#@ Инверсия ключей
function inv($AX, $ARR) {
	if ( is_array($AX) && ok($Res, take($ARR, $AX, yes)) )
		return array_keys($Res); }
#@ Разворот банка данных
function turn($X, $KEEP = no) {
	if ( bank($X) ) return is_array($X) ? array_reverse($X, $KEEP)
		: strrev($X); }
#@ Упорядочивание списка
function kind($AX, $ASSOC = no, $DSC = no, $HOW = SORT_REGULAR) {
	if ( ! we($AX) ) return poor($AX); else $rev = $DSC ? 'r' : str;
	$assoc = un($ASSOC) ? 'k' : let($ASSOC, 'a', str);
	$fx = sprintf('%s%ssort', $assoc, $rev);
	$fx($AX);
	return $AX; }
#@ Формирование уникального массива
function uniq($AX, $DUP = no) {
	if ( ! icnt($AX, $cnt) ) return cant;
		else if ( $cnt < 2 ) return $AX;
			else $Res = array();
	do {
		$Res[ key($AX) ] = $x = reset($AX);
		foreach ( scan($x, $AX, $DUP) as $k) {
			unset($AX[$k]);
			$cnt--; }
	} while ($cnt > 0);
	return $Res; }
#@ {?} Подмассив является неотъемлемой частью другого массива
function part($AX, $ARR, $DUP = no, & $Lost = array(), & $Diff = array()) {
	if ( ! pair($AX, $ARR, $Res) ) return lie($Res);
		else list ( $Lost, $Diff ) = of::an($Lost, $Diff);
	if ( skip($DUP) ) $fx = 'noop'; else $fx = $DUP ? 'twin' : 'eq';
	foreach ( $AX as $k => $x ) {
		$v = who($k, $ARR, $fake);
		if ( $fake ) $Lost[] = $k; else if ( ! $fx($x, $v) ) $Diff[] = $k; }
	return ! we($Lost) && ! we($Diff); }
#@ Подмена значения по ключу
function subst($KX, $AX, $SDEF = no) {
	if ( ! ok($ans, clue($KX, $AX)) ) {
		if ( func_num_args() == 2 || un($ans) ) return $KX;
			else if ( un($SDEF) ) return reset($AX);
				else if ( yes($SDEF) && ke(str, $AX) ) return $AX[str];
					else if ( clue($SDEF, $AX) ) return $AX[$SDEF];
						else return $KX;
	} else return $AX[$KX]; }

## Ресортировка ручная
function rord($AX, $SEQ) {
	if ( ! we($AX) ) return poor($AX);
	if ( ! ok($ans, we($SEQ)) ) return no($ans) ? $AX : cant;
		else $Res = ax();
	foreach ( $SEQ as $k ) {
		if ( clue($k, $AX) ) $Res[$k] = $AX[$k];
		unset($AX[$k]);
	}
	foreach ( $AX as $k => $v ) $Res[$k] = $v;
	return $Res;
}

## Вставить подмассив на место ключа исходного массива
function expa($AX1, $AX2, $K) {
	if ( ! arrs($AX1, $AX2) ) return cant;
	if ( ! we($AX1) || ! ok($c, k2i($K, $AX1)) ) return no;
	list ( $AXP1, $AXP2 ) = two($AX1, $c);
	foreach ( $AX2 as $k => $v ) $AXP1[$k] = $v;
	return merge($AXP1, $AXP2);
}

function lines($CONT, $NEED, $SKIP = no) {
	if ( ! she($CONT) ) return str; else $Lines = explode(nl, $CONT);
	$Res = ax();
	if ( inat($NEED) || are::nat($NEED) ) {
		$Rows = to::o2i(a($NEED), $LINES);
		foreach ( $Rows as $n )
			if ( $SKIP ) unset($Lines[$n]);
				else if ( ke($n, $Lines) ) $Res[] = $Lines[$n];
	} else if ( yes($NEED) ) {
		foreach ( $Lines as $i => $text ) {
			if ( iword($text) ) {
				if ( $SKIP ) unset($Lines[$i]); else $Res[] = $text; }}
	} else if ( str($NEED) ) {
		foreach ( $Lines as $i => $text ) {
			if ( you($text, $NEED) ) {
				if ( $SKIP ) unset($Lines[$i]); else $Res[] = $text; }}
	} else if ( we($NEED) ) {
		list ($fx, $Args) = lead($NEED, _);
		$is_trim = yes(who('trim', $NEED));
		$is_nor = yes(who('nor', $NEED));
		foreach ( $Lines as $i => $text ) {
			if ( $is_trim ) $text = trim($text);
			if ( $is_nor ) $text = rtrim($text, "\r");
			if ( cufa($fx, pool($text, $Args)) ) {
				if ( $SKIP ) unset($Lines[$i]); else $Res[] = $text; }}}
	return j($SKIP ? $Lines : $Res, nl);
}

#@ Все данные выстраиваются в общую очередь
function pool() {
	$Res = ax();
	foreach ( func_get_args() as $arg ) $Res = merge($Res, a($arg));
	return $Res;
}

#@ Всех ли ключей хватает в этом массиве?
function lack($HAVE, $MUST, & $GOOD = no) {
	if ( ! arrs($HAVE, $MUST) ) return cant; else $Res = ax();
	foreach ( $MUST as $k => $v ) if ( ! ke($k, $HAVE) ) $Res[] = $k;
	$GOOD = ! we($Res);
	return $Res;
}
