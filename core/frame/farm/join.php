<?php $favi = cpath($OptPlaces['farm'], 'img', 'favicon.ico', yes); ?>
<!DOCTYPE HTML><html>
<head><meta charset="utf-8"><title>FARM</title></head>
<link type="image/x-icon" href="<?= $favi ?>" rel="shortcut icon">
<link type="image/x-icon" href="<?= $favi ?>" rel="icon">
<meta name="theme-color" content="#4A341E">
<body style="color:#FDD798; font-size: 110%;
  background-image: url(<?= cpath($OptPlaces['farm'], 'img', 'ground.jpg', yes); ?>);">
<div style="border: dashed 1px; margin-left: 50px; margin-top: 20px;
	padding-left: 20px; padding-right: 20px; border-color: #565656;
	margin-right: 25px;">
<pre style="word-wrap: break-word; word-break: break-all; white-space: pre-wrap;
font-family: Liberation Mono;"><?php
define('debug_path', path('data', 'debug'));
if ( devel_mode > 1 && ok($incl, path_farm. 'plant/%s.php') )
	foreach ( u('suite,ready,board,probe') as $file )
		require_once(spr($incl, $file));
	vd('debug is finished!...');
