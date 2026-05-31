<?php
define('time_begin', microtime(true));
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Kiev');
define('devel_mode', 0);
#:
define('engine_on', true);

// require_once('pre.lib.php');
// require_once('dbg.lib.php');
// require_once('dump13.php');

require_once('locate.php');
require_once('start.php');
#:
$Book = Env::cfg('book');

#!
// if ( devel_mode > 0 ) {
// 	define('path_farm', path('farm'));
// 	require_once( cpath(path_farm, 'join.php', yes) );
// }
// require_once('board.php');
#!
list ($i, $page) = of(0, Route::get());
do {
	list ($again, $name) = of(no, $Book[$page]);
	if ( he($name, 0, $ch) && '+' == $ch ) $path = cut($name, 1);
		else if ( slash == $ch ) $path = $page. $name;
	$page_class = require_once(path_book. $path. page_ext);
	$thePage = new $page_class($i++, $name);
	if ( $thePage->hitch() || no($thePage->build()) ) {
		$again = done($page = $thePage->redir());
		unset($thePage);
	}
} while ( $again );
$html = (string) $thePage;

/*
if ( App::x('iUser')->admin() )
	$html.= '<div class="sysinfo"><p style="font-size:11px;text-align:center;color:grey;">'
		. dd('Render time'). sp(round(microtime(true) - time_begin, 3))
		. 'sec</p></div>';
*/
#: Finalization
$_SESSION['post_data'] = null;
unset($_SESSION['post_data']);
die($html);
