<?php
function attr($ID = _, $NAME = _, $CLASS = _, $TAIL = no
	, $CUSTOM = array(), $STYLE = array()) {
	$AX = array('id' => str, 'class' => str, 'name' => str);
	if ( un($ID) ) unset($AX['id']); else $AX['id'] = $ID;
	if ( un($NAME) ) unset($AX['name']); else $AX['name'] = $NAME;
	if ( un($CLASS) ) unset($AX['class']); else $AX['class'] = $CLASS;
	if ( we($CUSTOM) ) $AX = merge($AX, $CUSTOM);
	if ( we($STYLE) ) $AX['style'] = '+style_data';
	$Data = array();
	foreach ( $AX as $k => $v ) {
		if ( ! itext($v) ) return cant;
			else $Data[] = sprintf('%s="%s"', $k, $v); }
	$res = j($Data, spc);
	return $TAIL ? $res. '%s' : $res; }

class tag {
	public static function __callStatic($TAG, $ARGS) {
		static $tmpl1 = '<%s%s>%s</%1$s>';
		static $tmpl2 = '<%s%s>%s';
		list ($cont, $short, $attr) = safe($ARGS, 3);
		$tmpl = $short ? $tmpl2 : $tmpl1;
		if ( we($attr) ) {
			foreach ( $attr as $k => & $v ) $v = spr('%s="%s"', $k, $v);
			$attr = j($attr, spc); }
		if ( is(len($attr)) ) $attr = pre($attr, spc);
			else if ( yes($attr) ) $attr = '%s';
				else $attr = str;
		if ( itext($cont) ) return sprintf($tmpl, low($TAG), $attr, $cont); }}


