<?php /* Пcевдонимы стандартных функций */
#@ {&} Принадлежность указанному типу
function isb($X) { return is_bool($X); }
function isi($X) { return is_int($X); }
function isf($X) { return is_float($X); }
function iss($X) { return is_string($X); }
function the($X) { return is_object($X); }
function isa($X) { return is_array($X); }
#@ {&} Специальные типы
function open($X) { return is_resource($X); }
function call($X) { return is_callable($X); }
#@ {&} Нахождение ключа в массиве
function ke($KEY, $ARR) { return array_key_exists($KEY, $ARR); }
#@ {"} Преобразование строк
function cap($STR) { return ucfirst($STR); }
function hi($STR) { return strtoupper($STR); }
function low($STR) { return strtolower($STR); }
#@ {=} Callback - вызов
function cufa($FX, $ARGS) { return call_user_func_array($FX, $ARGS); }
#@ Извлечение первого/последнего элемента
function head(& $arr) { return array_shift($arr); }
function tail(& $arr) { return array_pop($arr); }
#@ {,} Объединение нескольких массивов
function merge() {
	return call_user_func_array('array_merge', func_get_args()); }
#@ {,} Срез массива
function slice() {
	return call_user_func_array('array_slice', func_get_args()); }
#@ {,} Заполняет массив ключей
function afk($KEYS, $VAL = null) { return array_fill_keys($KEYS, $VAL); }
#@ {"} Специальные символы HTML
function hsc($STR) { return htmlspecialchars(html_entity_decode($STR)); }
#@
function eh($O) { echo $O; }
function arc($KEYS, $VALS) {
	$def = func_num_args() > 2 ? func_get_arg(2) : null;
	return array_combine($KEYS, safe($VALS, count($KEYS), $def)); }
