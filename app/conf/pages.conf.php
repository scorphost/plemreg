<?php
/* Связь с обработчиком страницыи базовые настройки страницы */
define('page_ext', '.page.php');

define('homepage',  'home');
define('page_404',  'p404');
define('page_dbx',  'dberr');
define('page_dev',  'dev');
define('page_tech', 'tech');
define('page_wait', 'wait');

/* connection request -> unit */
$CfgBook = Array
(
	homepage   => '/main',
	page_404   => '+404',
	page_dbx   => '+dbe',
	page_tech  => '+tech',
	page_wait  => '+wait',
	'login'	   => '/login',
	'logout'   => '/logout',
	'rules'	   => '/rules',
	'catalog'  => '/catalog',
	'farms'	   => '/farm',
	page_dev   => '+dev',
	'denied'   => '+acl',
	'register' => '/register',
	'confirm'  => '/confirm',
	'profile'  => '/profile',
	'users'  => '/users',
	'feedback'  => '/feedback',
	// 'locked'   => '+lock',

	##
	'test'     => '/test',
	// 'newpage'  => '/newpage',
	#newline#
);

$CfgLangs = array('ru' => true, 'en' => null, 'ua' => false);
?>
