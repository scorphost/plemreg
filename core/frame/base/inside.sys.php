<?php /* Анализ элементов */
#@ Это список ключей/занятых/свободных
function keys($AX) { return are::clue($AX) && unique($AX); }
function busy($AX, $ARR) { return are::clue($AX, $ARR) && unique($AX); }
function free($AX, $ARR) { return are::clue($AX, $ARR, yes) && unique($AX);}
#@ Массив состоит из таких же списков и (+ уже упорядочен)
function such($AX1, $AX2, $ORDERED = no, $KEYS = no) {
	if ( ! arrs($AX1, $AX2) ) return cant;
		else if ( ! eqc($AX1, $AX2) ) return no;
	if ( $KEYS ) list ($AX1, $AX2) = of::ak($AX1, $AX2);
	if ( ! $ORDERED ) list ($AX1, $AX2) = of::kind($AX1, $AX2);
	return $AX1 === $AX2; }
#@ {?} Элементы являются уникальными
function unique($AX, $DUP = no) {
	if ( is_array($AX) ) return eqc($AX, uniq($AX, $DUP)) && ! empty($AX); }
#@ Массив является плоским
function flat($AX, $A0 = no) {
	if ( ! arr($AX) ) return cant;
		else if ( count($AX) != count($AX, true) ) return no;
			else return $A0 || no(array_search(array(), $AX, yes)); }
#@ Растет или падает список числовых элементов?
function grow($AX, $DX = no) { return _up_($AX, $DX, yes); }
function fall($AX, $DX = no) { return _up_($AX, $DX, no); }

function chk($AX, $SMP, $ADD = array(), & $LOST = array()) {
	list($php1, $ans, $LOST) = of(str, yes, array());
	foreach ( $ADD as $var => $val )
		$php1 = code('$'. $var. '='. var_export($val, yes));
	foreach ( $AX as $k => $x ) {
		$chk = $SMP[$k];
		$php2 = $php1. code('$x='. var_export($x, yes), '$r='
			. $chk, 'return $r');
		$res = eval($php2);
		$ans = $ans && is($res);
		if ( non($res) ) $LOST[] = $chk; }
	return $ans; }
