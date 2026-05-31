<?php
/* Стандартные функции для обслуживания различных узлов системы */
#@ Главный экран вывода
function scr() {
	static $wb = null;
	if ( func_num_args(0) == 0 ) return $wb; else $wb = func_get_arg(0);
}

function ua2tpl($DEF = 'html') {
	// прогнать по сопадению user_agent
	// lang + html, old, mobi, wap
	return Site::lang(). slash. $DEF;
}

function unit($NAME) {
	$Args = func_get_args();
	$class = require_once(fpath(path_unit, array_shift($Args). '.unit.php'));
	cufa(of($theUnit = new $class(), 'args'), $Args);
	return (string) $theUnit;
}


function node_uri() {
	static $node = null;
	if ( un($node) ) return
		nil($node = cufa('cpath', func_get_args()));
	return func_num_args() == 0 ? Route::make($node)
		: cufa(u('Route,make'), merge(of($node), func_get_args()));
}

function msg_err($DESC) {
	return (bool) nil(Sess::set('msg_err', $DESC));
}

function retry(& $OUT) {
	if ( skip($OUT) ) $OUT = ax();
	$Retry = Sess::get('retry');
	if ( we($Retry) ) foreach ( $Retry as $k => $v ) $OUT[$k] = $v;
	if ( ok($txt, Sess::get('msg_err')) ) $OUT['msg_err'] = $txt;
		else if ( ok($txt, Sess::get('msg_inf')) ) $OUT['msg_inf'] = $txt;
	Sess::del(u('retry,msg_err,msg_inf'));
}


function pre_form(& $VAR = _, $ACT, $BACK = skip) {
	if ( ! arr($VAR) ) $VAR = array();
	if ( she(Sess::get('back')) ) {
		$back = Sess::get('back');
		Sess::set('back', str);
	} else $back = skip($BACK) ? Route::uri(-1) : $BACK;
	$VAR['post_back'] = atag('Назад', $back);
	$VAR['post_uri'] = Route::uri();
	$VAR['post_act'] = 'do'. ucfirst($ACT);
}

function std_form($NAME, $OBJ, $VAR) {
	$type = method_exists($OBJ, 'form_type' ) ? $OBJ->form_type() : 'add';
	$Vars['form']['lang'] = Site::lang();
	$Vars['form']['title'] = $NAME;
	$Vars['form']['content'] = tell(Sys::tmpl($OBJ::iam, $type), $VAR);
	return done($OBJ->theSender->html(tell(Sys::tmpl('form'), $Vars)));
}

function nodata($TEXT = _) {
	if ( skip($TEXT) ) $TEXT = 'Нет данных';
	return tell(Sys::tmpl('no', 'data'), array ('content' => $TEXT));
}

#!
function mime($FILE) {

}

function email($TO, $SUBJ, $TEXT) {
	$tmpl = '<html><body>%s</body></html>';
    	//"Cc: scorphost@gmail.com\r\n".
	$Headers = "From: info@kozovodstvo.center\r\n".
    	"Reply-To: info@kozovodstvo.center\r\n".
    	"MIME-Version: 1.0\r\n".
    	"Content-type: text/html; charset=utf-8\r\n".
    	'X-Mailer: PHP/' . phpversion();
    return mail($TO, $SUBJ, sprintf($tmpl, $TEXT), $Headers);
}


#@ Один из модулей сообщает о невозможности дальнейшей работы
function hitch() {
	static $sender = str;
	if ( func_num_args() == 0 ) return $sender;
		else $arg = func_get_arg(0);
	if ( yes($arg) ) return she($sender); else $sender = $arg;
}


#!

function mb_first($TXT) {
	if ( ! function_exists('mb_strtoupper') ) return $TXT;
		else mb_internal_encoding('UTF-8');
    return mb_strtoupper(mb_substr($TXT, 0, 1)). mb_substr($TXT, 1);
}


#@ Извлечение поля в формате db::fetch
function fld($ARR, $SEQ, $NAME = no) {
	if ( un($ARR) ) $ARR = ax();
	if ( ! arr($ARR) ) return cant; else $v = & $ARR;
	foreach ( p($SEQ) as $k ) {
		$v = & bind($v, $k, $fake);
		if ( $fake ) return cant;
	}
	if ( $NAME ) $v = & bind($v, 'n', $fake);
		else $v = & bind($v, 'v', $fake);
	if ( ! $fake ) return $v; }

#@ Значение селектора со смещением
function shsel($VAL, $INDB = no) {
	if ( $INDB ) return zero($VAL) ? null : dec($VAL);
		else return some($VAL) ? inc($VAL) : 0;
}
