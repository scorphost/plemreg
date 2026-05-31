<?php
function get_date($X = null, $EXT = no) {
	if ( un($X) ) $X = time();
		else if ( the($X) ) $X = date_timestamp_get($X);
			else if ( ! inat($X) ) $X = strtotime($X);
	$Date = getdate($X);
	$Res = arc(u('y,m,d,h,mi,s'), give('year,mon,mday,hours,minutes,seconds'
		,$Date ));
	return $EXT ? merge($Res, arc(u('wd,yd,hd,hm')
		, give('wday,yday,weekday,month', $Date))) : $Res;
}

function was_date($DATE, $LONG = no, $LC = 'ru', $SEC = no) {
	static $tmpl1 = "%'.02d-%s-%d %'.02d:%'.02d";
	static $tmpl2 = "%s %'.02d %d, %'.02d:%'.02d";
	$tmpl = $LONG ? $tmpl2 : $tmpl1;
	extract(getdate(strtotime($DATE)));
	$ptrn = $SEC ? $tmpl.":%'.02d" : $tmpl;
	$m = set($LC) ? as_mon($LC, $mon, $LONG) : sprintf("%'.02d", $mon);
	return $LONG ? sprintf($ptrn, $m, $mday, $year, $hours, $minutes, $seconds)
		: sprintf($ptrn, $mday, $m, $year, $hours, $minutes, $seconds);
}

function today($X = skip) {
	$Date = get_date($X);
	$tmpl = lpq(). dot. lpq(). '.%s';
	return spr($tmpl, $Date['d'], $Date['m'], $Date['y']);
}

function today2($X = skip) {
	if ( ! she($X) ) return $X;
	$Date = get_date($X);
	$tmpl = lpq(). dot. lpq(). '.%s';
	return spr($tmpl, $Date['d'], $Date['m'], $Date['y']);
}

function zdate($X) {
	list ($d, $m, $y) = u($X, dot);
	$tmpl = '%s/'. lpq(). slash. sp(lpq()). '00:00:00';
	return spr($tmpl, $y, $m, $d);
}


function lpq($N = 2, $P = '0', $T = 'd') { return "%'.${P}${N}${T}"; }

function ppm($P = 1, $T = 's') { return "%${P}\$${T}"; }


// Поза(Вчера), Сегодня, 3 часа
function nx_date($DDL) {
	$sid_ = 24 * 60 * 60;
	$theDDL = new DateTime($DDL);
	$theNow = new DateTime();
	list ($ts1, $ts2) = of::date_timestamp_get($theDDL, $theNow);
	list ($dx, $isLate) = of(abs($ts2 - $ts1), $ts2 > $ts1);
	$dl = mod($dx, $sid_);
	$hl = mod($dx - $dl * $sid_, 3600);
	$_hl3600 = $hl * 3600;
	$ml = mod($dx - $dl * $sid_ - $_hl3600, 60);
	// $sl = $dx - $dl * $sid_ - $_hl3600 - $ml * 60; ## reserved for seconds
	$d1 = get('d', so($Deadline, get_date($theDDL)));
	$d2 = get('d', get_date($theNow));
	$res = ! $isLate ? 'Окончание работ %s в %s:'. lpq()
		. ' (остал'. let(1 == $hl, 'ся', 'ось'). ' %s)'
		: 'Просрочено, срок окончания работ %s в %s:'. lpq(). ' (прошло %s)';
	$dx = dist($d1, $d2);
	list ($wh, $wm) = av(give('h,mi', $Deadline));
	if ( $dx < 2 ) {
		if ( 0 == $dx ) $when = 'Сегодня';
			else $when = $isLate ? 'Вчера' : 'Завтра';
	} else $when = vsprintf(j(many(lpq(), 2, yes), dot). dot. idk
			, give('d,m,y', $Deadline));
	if ( all::zero($dl, $hl) ) {
		## разница только в минутах (до часа)
		if ( $ml < 5 ) $how = 'менее 5 минут';
			else if ( hit($ml, 50, 59 ) ) $how = 'меньше часа';
				else $how = "${ml} минут";
	} else {
		$t0 = 'час';
		$t1 = num(who(0, ch(strrev(text($hl)))));
		if ( ! hit($hl, 11, 19) && lim($t1, 1, 5) ) $t0.= 'а';
			else if ( $hl > 1 ) $t0.= 'ов';
		$_t1 = sp($hl). $t0; ## часы
		if ( $ml > 0 ) {
			$t0 = 'минут';
			$t1 = num(who(0, ch(strrev(text($ml)))));
			if ( ! hit($ml, 11, 19) && $t1 != 0 ) {
				if ( $t1 < 5 ) $t0.= $t1 > 1 ? 'ы' : 'а';
			}
			$_t2 = sp($ml). $t0;
		} else $_t2 = str; ## минуты если есть
		if ( $dl > 0 ) {
			$t1 = num(who(0, ch(strrev(text($dl)))));
			if ( hit($dl, 11, 19) || ($t1 > 4 || 0 == $t1) ) $t0 = 'дней';
				else $t0 = $t1 > 1 ? 'дня' : 'день';
			$_t0 = sp($dl). $t0;
		} else $_t0 = str; ## дни
		$how = j(own::l(of($_t0, $_t1, $_t2)), spc);
	}
	return spr($res, $when, $wh, $wm, $how);
}
