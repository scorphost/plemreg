<?php
/* Генератор данных для шаблонов */
function css($NAME) {
 	static $tmpl = '<link type="text/css" rel="stylesheet" href="%s.css?v=%s" />';
 	global $PathPlaces;
 	return spr($tmpl, fpath($PathPlaces['css'], $NAME), css_version);
}

function js($NAME) {
	static $tmpl = '<script type="text/javascript" src="%s.js"></script>';
 	global $OptPlaces;
 	return spr($tmpl, fpath($OptPlaces['js'], $NAME));
}

function img($FILE, $W = _, $H = _, $ALT = _, $CFG = 'img') {
	static $tmpl = '<img src="%s"%s alt="%s" title="%s">';
 	global $PathPlaces;
 	$dim = str;
 	if ( inum($W, pol) ) $dim.= spc. spr('width="%s"', $W);
 	if ( inum($H, pol) ) $dim.= spc. spr('height="%s"', $H);
 	if ( skip($ALT) || ! iword($ALT) ) $ALT = str_replace(dot, '_', $FILE);
 	$path = arr($CFG) ? reset($CFG) : $PathPlaces[$CFG];
 	return spr($tmpl, fpath($path, $FILE), $dim, $ALT, $ALT);
}

function img2($FILE, $W = _, $H = _, $ALT = _, $CFG = 'img', $STYLE = str) {
	static $tmpl = '<img src="%s"%s alt="%s" title="%s"%s>';
 	global $PathPlaces;
 	$deco = she($STYLE) ? ' style="'. $STYLE. '"' : str;
 	if ( ! she($CFG) ) $CFG = 'img';
 	$dim = str;
 	if ( inum($W, pol) ) $dim.= spc. spr('width="%s"', $W);
 	if ( inum($H, pol) ) $dim.= spc. spr('height="%s"', $H);
 	if ( skip($ALT) || ! iword($ALT) ) $ALT = str_replace(dot, '_', $FILE);
 	$path = arr($CFG) ? reset($CFG) : $PathPlaces[$CFG];
 	return spr($tmpl, fpath($path, $FILE), $dim, $ALT, $ALT, $deco);
}

function ava($FILE, $W = _, $H = _, $ALT = _) {
 	return img($FILE, $W, $H, $ALT, 'ava');
}
function pic($FILE, $W = _, $H = _, $ALT = _) {
 	return img($FILE, $W, $H, $ALT, 'pic');
}

