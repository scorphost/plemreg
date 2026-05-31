<?php
/* Библиотека работы с валютой */
class usd {
	function int2mon($NUM) {
		if ( ! inum($NUM) ) return cant;
			else $n = abs(round($NUM));
		if ( $n < 100 ) $res = ( $n < 10 ? '00' : '0' ). ts($n);
			else $res = ts($n);
		return ( $NUM < 0 ? '-' : str ). j(two($res, -2), dot);
	}
}

/*
взяли откуда-то
посчитали
положили куда скажут
*/
class warp {
	public static function __callStatic($FX, $ARGS) {

	}
}
