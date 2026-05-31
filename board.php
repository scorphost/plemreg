<?php

function rnat($X, $POLE = no, $EXT = no) {
	return is(nat($X, $POLE, $EXT));
}

function cnat($X, $ALT = 0) {
	if ( ! inat($ALT) ) $ALT = 0;
		else return inat($X) ? $X : $ALT; }

#@ Как число в определенном представлении
function as_num($X, $SEP, $PREC) {}

#@ Как целое число в определенном представлении
## шестнадцатиричное 16
## восьмиричное 8
## двоичное bool (от старшего, от младшего)
function as_nat() {}

#@ Собрать в строку данные из массива
function talk($SMPL, $DATA) {
	if ( ! itext($SMPL) ) return cant;
		else if ( ! we($DATA) ) return $SMPL;
			else $res = tell($SMPL, ax(), $List);
	if ( ! we($List) ) return $res;
	foreach ( $List as $key ) {
		do {
			$Arr = & bind($DATA, $key);
			if ( ! arr($Arr) ) break; else $theTree = new Tree($Arr);
			$max = max($theTree->L());
			if ( $max == 1 ) break; else $Tbl = ax();
			foreach ( scan($max, $theTree->L()) as $id ) {
				$pid = $theTree->R($id);
				if ( ! ke($pid, $Tbl) ) $Tbl[$pid] = str;
				$Tbl[$pid].= to_text($theTree->id2v($id)); }
			foreach ( $Tbl as $pid => $nid ) {
				$Join = of($theTree->K($pid));
				foreach ( $theTree->trace($pid) as $rid )
					if ( $rid > 0 ) $Join[] = $theTree->K($rid); else break;
				intr($Arr, j($Join, slash, yes), $nid); }
		} while( $max-- > 1 );
		if ( we($Arr = & bind($DATA, $key)) ) $Arr = j($Arr);
	}
	return tell($SMPL, $DATA);
}

#@ Расставить межинтервальные знаки
function mid() {}
#@ Расставить пред- постинтервальные знаки
function edge($DATA, $MAP, $DIR) {}
#@ Расставить знаки окружения (workaround)
function wa($DATA, $MAP) {}

#!
$smpl = 'My{{id}}{{nick}}!';
$Data = array
(
	'id' => array
	(
		0 => array(
			'w2' => ' name',
			'w1' => array( 1=> ' i', 0 => of('s', spc)),
		),
		1 => 'or ',
	),
	'nick' => 'Alek',
);

// $r = talk($smpl, $Data);
// vd($r);

// $fh = fopen('board.php', 'a');
// fwrite($fh, '$r = '. rand(0, 9).';'. nl);
// fclose($fh);

//function () {} где находится текст
// function rep() {} замена текста

// function cm () {} count more
// rnat() - для результата в виде числа
// as_text() {} с большой, с маленькой, большими, маленькими, сокращение
