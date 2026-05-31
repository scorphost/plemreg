<?php /* Разделение данных по типам и значению */
#@ {&} Детекторы информации
function dgt($VAL) { return is_int($VAL) or is_float($VAL); }
function bank($VAL) { return is_string($VAL) or is_array($VAL); }
function info($VAL) { return dgt($VAL) or is_string($VAL); }
function atom($VAL) { return info($VAL) or is_bool($VAL); }
function data($VAL) { return info($VAL) or is_array($VAL); }
#@ {&} Типы определители
function no($VAL) { return false === $VAL; }
function yes($VAL) { return true === $VAL; }
function un($VAL) { return null === $VAL; }
function set($VAL) { return ! un($VAL); }
function non($VAL) { return un($VAL) or no($VAL); }
function is($VAL) { return ! non($VAL); }
#@ {&} Проверка на начальное значение своего типа
function miss($VAL) {
	return is_string($VAL) ? strlen($VAL) == 0 : empty($VAL); }
#@ {?} Является ли содержимое знакоместом
function some($X) {
	if ( info($X) ) return is_string($X) ? strlen($X) > 0 : true; }
#@ {?} Пустой контейнер
function box($X, $IT_ARRAY = _) {
	if ( bank($X) ) {
		if ( skip($IT_ARRAY) ) return cnt($X) === 0 || len($X) === 0;
			else return $IT_ARRAY ? cnt($X) === 0 : len($X) === 0; }}

function just($X) {
	return ! is_object($X) && ! is_callable($X) && ! is_resource($X);
}
