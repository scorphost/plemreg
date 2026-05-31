<?php
function farm() {
	echo '<!DOCTYPE HTML><html>
<head><meta charset="utf-8"><title>VAR_DUMP</title></head>
<link type="image/x-icon" href="<?= $favi ?>" rel="shortcut icon">
<link type="image/x-icon" href="<?= $favi ?>" rel="icon">
<meta name="theme-color" content="#4A341E">
<body style="color:#FDD798; font-size: 125%;
  background-image: url(/img/ground.jpg)">
<div style="border: dashed 1px; margin-left: 50px; margin-top: 20px;
	padding-left: 20px; padding-right: 20px; border-color: #565656;
	margin-right: 25px;">
<pre style="word-wrap: break-word; word-break: break-all; white-space: pre-wrap;
font-family: Iosevka;">';
}
define('dbg_hr', '<hr style="color:gray; border:dotted 1px;" />');
define('mode_html', true);
#: Общие функции нижнего порядка
#@ Трассировщик аргументов и значений
function _vdtrace_($TRC, $FNC) {
	list ($file, $line) = av(give('file,line', reset($TRC)));
	$raw = sp('<?php'). o2v($line - 1, explode(nl, load($file)));
	$Tok = token_get_all($raw);
	$fin = (int) $pos = $got = (bool) $Del = $Spl = $Part = $Arr = $Dat = ax();
	foreach ( $Tok as $k => & $v ) {
		if ( sub($k, $pos) === 1 && '(' === $v ) $got = $k;
		if ( is($got) && 0 == $fin && ';' === $v ) $fin = $k;
		if ( arr($v) ) {
			$v[0] = strtolower(substr(token_name($v[0]), 2));
			if ( in($v[0], 'whitespace,comment') ) $Del[] = $k;
			unset($v[2]);
			if ( 'string' == $v[0] && too($v[1], $FNC) ) $pos = $k; }}
	if ( 0 == $fin ) die('place vd in one line!');
	take($Tok, $Del);
	list ($Tok, $opn) = of(av(arr::kkC($Tok, $pos, $fin)), 0);
	array_shift($Tok);
	array_pop($Tok);
	unset($v);
	foreach ( $Tok as $k => $v ) {
		if ( '(' == $v ) $opn++;
			else if ( ')' == $v ) $opn--;
				else if ( ',' == $v && 0 == $opn ) $Spl[] = $k; }
	if ( we($Spl) ) {
		$i = reset($Spl);
		foreach ( $Tok as $k => $v ) {
			if ( $k === $i ) {
				$Arr[] = $Dat;
				$Dat = ax();
				$i = next($Spl);
			} else $Dat[] = $v; }
		if ( we($Dat)) $Arr[] = $Dat;
	} else $Arr[] = $Tok;
	foreach ( $Arr as $k => & $Spl ) {
		$res = str;
		foreach ( $Spl as $v ) $res.= ( arr($v) ? hsc($v[1]) : hsc($v) );
		$Spl = $res; }
	return $Arr; }
#@
function _dump_($FX, $ARGS, $N, $TR, $WB) {
	list ($file, $line) = av(give('file,line', reset($TR)));
	$WB->hr();
	wl(sprintf('(%s) Debug call *%s{%s} from %s:%s'
		, datestamp(), $FX, $N, route($file), $line), $WB);
	$WB->cr();
	foreach ( _vdtrace_($TR, $FX) as $k => $var) {
		list ($res, $arg) = of(spc(4). $var, $ARGS[$k]);
		if ( mode_html ) $res = tag::b(tag::i($res));
		$val = qs('='). ( arr($arg) ? ah($arg) : xh($arg) );
		$WB->p($res. $val);
		$WB->cr(); }
	return $WB->z(); }
#:
## dump and terminate
function vx($X) {
	var_export($X);
	die(); }
#@
function vd() {
	farm();
	$res = _dump_(__FUNCTION__, func_get_args(), func_num_args()
		, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), scr());
	die($res);
	}
#@ dump and go on
function vdi() {
	static $i = 0;
	static $var = str;
	static $wb = null;
	static $ini = no;
	if ( no($ini) ) {
		$ini = yes;
		$wb = func_num_args() == 0 ? scr() : func_get_arg(0);
		return; }
	if ( func_num_args() == 0 ) {
		$var = str;
		return nil($i = 0); }
	list ($FX, $ARGS, $N) = of(__FUNCTION__, func_get_args(), func_num_args());
	if ( 0 == $i++ ) {
		$TR = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		list ($file, $line) = av(give('file,line', reset($TR)));
		$wb->hr();
		wl(sprintf('(%s) Dump call *%s{%s} from %s:%s'
			, datestamp(), $FX, $N, route($file), $line), $wb);
		$var = serialize(_vdtrace_($TR, $FX)); }
	$wb->p(spc(4). sp(wear($i)));
	$Vars = unserialize($var);
	foreach ( $ARGS as $k => $arg)
		$wb->p($Vars[$k]. qs('='). xh($arg), sp(';'));
	$wb->cr();
	return $wb->z(); }

class st {
	const dbg_ext = '.dbg';
	public static function __callStatic($FILE, $ARGS) {
		if ( ! we($ARGS) ) return cant;
			else $file = dump_dir. $FILE. self::dbg_ext;
		$x = base64_encode(bin(array_shift($ARGS)));
		$rem = to_text(reset($ARGS));
		$res = sj(of($x, $rem), ',', yes);
		return save($file, $res); }}
class ld {
	public static function __callStatic($FILE, $A) {
		$err = of(cant, cant, yes);
		$file = dump_dir. $FILE. st::dbg_ext;
		list ( $x, $rem ) = safe(u(load($file)), 2);
		if ( ! ok($x, base64_decode($x))) return $err;
			else $res = bin($x, yes, $fake);
		return $fake ? $err : of($res, $rem, no); }}
