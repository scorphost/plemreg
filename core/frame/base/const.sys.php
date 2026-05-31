<?php /* Специальные константы */
define('str', '');
define('slash', '/');
define('nl', "\n");
define('spc', ' ');
define('dot', '.');
define('q', "'");
define('qq', '"');
define('idk', '%s');
define('br', '<br />');
define('eol', "\r\n");
#` Повторители констант
function slash($N = 1) { if ( inat($N, pol) ) return str_repeat(slash, $N);}
function eol($N = 1) { if ( inat($N, pol) ) return str_repeat(eol, $N); }
function nl($N = 1) { if ( inat($N, pol) ) return str_repeat(nl, $N); }
function spc($N = 1) { if ( inat($N, pol) ) return str_repeat(spc, $N); }
function dot($N = 1) { if ( inat($N, pol) ) return str_repeat(dot, $N); }
function br($N = 1) { if ( inat($N, pol) ) return str_repeat(br, $N); }
function hr($N = 1) { if ( inat($N, pol) ) return str_repeat(hr, $N); }
function tab($N = 1) { if ( inat($N, pol) ) return str_repeat("\t", $N); }
function nbsp($N = 1) { if ( inat($N, pol) ) return str_repeat('&nbsp;', $N); }
#` Константы окружения
define('skip', null);
define('keep', true);
define('cant', null);
define('_', null);
define('no', false);
define('yes', true);
define('eq', '=');
define('ne', '!=');
define('lt', '<');
define('gt', '>');
define('ge', '>=');
define('le', '<=');

define('und', '!undefined!');
define('unf', '!unfinished!');
define('ok', 'ok!');

