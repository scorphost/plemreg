<?php
defined('engine_on') or die('Location: /');
#!
function _nakeddir_($DIR) {	return trim(trim((string) $DIR), '/'); }
#@ Absolute path from document_root
function path() {
	static $root = null;
	if ( is_null($root) ) $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
	list ( $res, $n ) = array($root. '/', func_num_args());
	if ( $n > 0 ) $Args = func_get_args(); else return $res;
	if ( end($Args) === true ) { #~ then it's file
		if ( $n-- < 3 ) return $n ? $res. (string) $Args[0] : null;
			else unset($Args[$n--]);
		$file = _nakeddir_($Args[$n]);
		unset($Args[$n]); }
	foreach ( $Args as $dir )
		if ( strlen($dir = _nakeddir_($dir)) > 0 ) $res.= $dir. '/';
	return isset($file) ? $res. $file : $res; }
#@ Result of joining path parts
function cpath() {
	$n = func_num_args();
	if ( $n > 0 ) $Args = func_get_args(); else return null;
	if ( end($Args) === true ) { #~ then it's file
		if ( $n-- < 3 ) return $n > 0 ? (string) $Args[0] : null;
			else unset($Args[$n--]);
		$file = _nakeddir_($Args[$n]);
		unset($Args[$n]); }
	$dirStart = trim((string) array_shift($Args));
	$ld = strlen($dirStart);
	$res = substr_count($dirStart, '.') == $ld
		? str_repeat('../', $ld) : rtrim($dirStart, '/'). '/';
	foreach ( $Args as $dir )
		if ( strlen($dir = _nakeddir_($dir)) > 0 ) $res.= $dir. '/';
	return isset($file) ? $res. $file : $res; }
function fpath() {
	return call_user_func_array('cpath'
		, array_merge(func_get_args(), array(true))); }
#:
define('site_root',  path());
define('path_core',  path('core'));
##
define('path_app',   path('app'));
define('path_book',  cpath(path_app, 'book'));
define('path_logic', cpath(path_app, 'logic'));
define('path_unit',  cpath(path_app, 'unit'));
##
define('path_data',  path('data'));
define('path_tmpl',  cpath(path_data, 'tmpl'));
$PathBoot = Array
(
	'base'		=> fpath(path_core, 'frame', 'base', '*.php'),
	'extra'		=> fpath(path_core, 'frame', 'extra', '*.php'),
	'conf'		=> fpath(path_app , 'conf', '*.php'),
	'duty'		=> fpath(path_core, 'duty', '*.php'),
	'proc'		=> fpath(path_core, 'proc', '*.php'),
	'asset'		=> fpath(path_app, 'asset', '*.php'),
);
#:
$PathPlaces = Array
(
	'js'   => '/js',
	'css'  => '/css',
	'img'  => '/img',
	'pic'  => '/stuff/upload',
	'ava'  => '/stuff/upload/ava',
	'api'  => '/api',
);
