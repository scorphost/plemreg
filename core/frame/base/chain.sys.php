<?php /*  Цепь связанных аргументов */
#@ {&} Цепочка сущностей десятичного числа? (max 10)
function nums(& $x0, & $x1, & $x2 = _, & $x3 = _, & $x4 = _,
	& $x5 = _, & $x6 = _, & $x7 = _, & $x8 = _, & $x9 = _) {
	for ($i = 0, $n = func_num_args(), $vx = 'x'. $i
		; $i < $n; $vx = 'x'. ++$i)	if ( ! inum($$vx) ) return no;
	return yes; }
#@ {&} Цепочка сущностей целого числа? (max 10)
function nats(& $x0, & $x1, & $x2 = _, & $x3 = _, & $x4 = _,
	& $x5 = _, & $x6 = _, & $x7 = _, & $x8 = _, & $x9 = _) {
	for ($i = 0, $n = func_num_args(), $vx = 'x'. $i
		; $i < $n; $vx = 'x'. ++$i) if ( ! inat($$vx) ) return no;
	return yes; }
#@ {&} Цепочка сущностей текста? (max 10)
function mean(& $x0, & $x1, & $x2 = _, & $x3 = _, & $x4 = _,
	& $x5 = _, & $x6 = _, & $x7 = _, & $x8 = _, & $x9 = _) {
	for ($i = 0, $n = func_num_args(), $vx = 'x'. $i
		; $i < $n; $vx = 'x'. ++$i) if ( ! itext($$vx) ) return no;
	return yes; }
#@ {&} Цепочка аргументов-строк?
function strs($X1, $X2) {
	foreach (func_get_args() as $x) if ( ! is_string($x) ) return no;
	return yes; }
#@ {&} Цепочка аргументов-массивов?
function arrs($AX1, $AX2) {
	foreach (func_get_args() as $x) if ( ! is_array($x) ) return no;
	return yes; }
#@ {&} Пара непустых банков
function pair($X1, $X2, & $RET = null, $IS_SUB = no, $or_str = no) {
	list ($def, $fx, $fs, $fc) = $or_str
		? of(str, 'strs', 'strlen', 'l') : of(ax(), 'arrs', 'count', 'c');
	if ( $fx($X1, $X2) ) {
		list ($RES, $sx1, $sx2) = of($def, $fs($X1), $fs($X2));
		if ( $fc($sx1) && $fc($sx2) ) $ans = $IS_SUB ? $sx1 <= $sx2 : yes;
	} else $RET = cant;
	return isset($ans) ? $ans : no; }
