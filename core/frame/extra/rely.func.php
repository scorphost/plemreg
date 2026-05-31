<?php
#@ Английская алфавитно-цифровая последовательность
function abc_ennum_() {
	return merge(of('_'), abc('az'), abc('AZ'), abc('09')); }
#@ База данных месяцев
function as_mon($LC = 'en', $X = skip, $LONG = no) {
	$Mon = Array(
		'en' => Array(
			0 => array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			1 => array(1 => 'January', 'February', 'March', 'April', 'May',
				'June', 'July', 'August', 'September', 'October', 'November',
				'December'),
		),
		'ru' => Array(
			0 => array(1 => 'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
				'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'),
			1 => array(1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май',
				'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь',
				'Декабрь'),
		), );
	if ( ! iword($LC, yes) || ! clue($LC, $Mon) ) $LC = 'en';
	if ( ! skip($X) ) {
		if ( hit($X, 1, 12) ) return $Mon[$LC][ (int) $LONG][$X];
	} else return $Mon[$LC][ (int) $LONG]; }
#@
function datestamp($PTRN = skip, $LC = skip, $LONG = no, $GMT = 0) {
	static $tmpl = "%'.02d/%s/%d %'.02d:%'.02d:%'.02d";
	if ( skip($PTRN) ) $PTRN = $tmpl;
	if ( ! l(so($sh, pol($GMT))) ) return str;
	if ( empty($sh) ) $sh = str; else $sh.= spc. ' hours';
	extract(getdate(strtotime('Now'. $sh)));
	$m = set($LC) ? as_mon($LC, $mon, $LONG) : sprintf("%'.02d", $mon);
	return sprintf($PTRN, $mday, $m, $year, $hours, $minutes, $seconds); }
