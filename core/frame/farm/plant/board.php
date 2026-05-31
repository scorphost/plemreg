<?php
$r = xntc('s.255,!name,,Кличка;*s.255,line1,,Блок данных 1', 'plemreg', 'pedigree');
vd($r);
$r = exst('name', 'HOYERS DIJACOMO', 'animals');

$Head = xarr('k1,h1;k3,h2');
$D[0] = xarr('k1,v11;k2,v12;k3,v13');
$D[1] = xarr('k1,v01;k2,v02;k3,v03;k4,');
$Foot = xarr('k1,f1;k4,f2');

$r = grid::pret(grid::auto($D, _, $Head), '-', str);
// vd(grid::$Grid);
tbl::map($r);
$r = tbl::html(xarr('border,1'));
echo($r); die();

// $r = auto2($D, '-', $Head, $Foot);


$r1 = css('test');
$r2 = js('test');
$r2 = img('test.jpg', 10, 120);
vd($r1, $r2);


/*
footer: title, meta
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>&lt;img&gt; | WebReference</title>
<meta charset="utf-8" />
<meta name="description" content="Отображает на веб-странице изображение." />
<link rel="image_src" href="https://webref.ru/assets/images/book/html5.png" />
<link type="text/css" rel="stylesheet" href="https://webref.ru/assets/css/css_GmPV_e3YNAzxsGyZNJb8l3jyzuLiXRRwUYoy5enMRkM.css" media="all" />
<link type="text/css" rel="stylesheet" href="https://webref.ru/assets/css/css_ks2XpjDvAEh0y3o2YQcyyM0ZfkV-0AtDKW-59rqef0E.css" media="screen" />
</head>
<body>



footer:
script section
</body>
</html>
*/



