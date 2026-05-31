<?php
#: DB
function know($FLDS, $EQV, $TBL, $UID = 'id') {
	$Res = DB::run(xsql::get(a($FLDS), $EQV, $TBL, $UID));
	return arr($FLDS) ? safe(ta(o2v(0, $Res)), count($FLDS))
		: who($FLDS, o2v(0, $Res));
}
function exst($FLD, $VAL, $TBL) {
	$sql = "SELECT COUNT(*) FROM `${TBL}` WHERE $FLD='${VAL}'";
	$res = DB::run($sql, 0);
	return more($res);
}
#: TMPL
function elem() {
	static $tmpl = str;
	static $html = str;
	if ( func_num_args() == 0 ) return cls($html); else $Data = func_get_arg(0);
	if ( str($Data) ) return l($Data) ? done($tmpl = $Data) : (bool) clr($tmpl);
		else $html.= tell($tmpl, $Data);
}
#: HTML
function atag($X, $HREF, $BL = no, $ATTR = no) {
	$Opt['href'] = $HREF;
	if ( yes($BL) ) $Opt['target'] = '_blank';
	if ( we($ATTR) ) foreach ($ATTR as $k => $v) $Opt[$k] = $v;
	return tag::a($X, no, $Opt);
}
#: URL
#` Немедленный переход по указанному месту
function home() {
	if ( func_num_args() == 0 ) $place = Route::uri(); ## та же страница
		else $place = func_get_arg(0);
	if ( yes($place) ) Route::away(); ## домашняя
		else if ( inat($place, neg) ) $place = Route::uri($place);
			else if ( we($place) ) $place = cufa(u('Route,make'), $place);
				else if ( ! iword($place) ) $place = slash;
	header(dd('Location'). $place);
	exit();
}
#` Построить полный url-маршрут
function url() {
	return cufa('cpath', array_merge(of(slash), func_get_args(), of(yes)));
}
