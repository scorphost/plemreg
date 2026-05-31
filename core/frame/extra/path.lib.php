<?php /* Filesystem Objects */
#@ Absolute path from document_root
function route($ROAD) { return str_replace(path(), str, realpath($ROAD)); }
#@ {?} Является ли локация синтаксически верной и доступной
## путь должен иметь строгий формат, очистку делать до отправки в функцию
function road($PATH, $WITH_FILE = no, & $STUCKS = array()) {
	list ( $Dirs, $STUCKS ) = of(u($PATH, slash), array());
	if ( ! we($Dirs) ) return cant;
	if ( miss(reset($Dirs)) ) {
		$path = slash;
		array_shift($Dirs);
	} else $path = str;
	if ( ! $WITH_FILE ) if ( miss(end($Dirs)) ) array_pop($Dirs);
	if ( ! c($Dirs) ) return cant;
	list ($Set1, $Set2, $ans) = of(abc_ennum_(), u('.-', str), yes);
	foreach ( down($Dirs) as $i => $part ) {
		if ( ! l($part) ) return cant; else $Data = live($part, $Set1, $Set2);
		if ( ke(0, $Data) || you($part, '-') || here(see(-1, $part), $Set2)
			|| ! ke(1, $Data) ) return nil($STUCKS[$i] = $part);
		$now_file = $WITH_FILE && $i == 0;
		$fx = $now_file ? 'is_file' : 'is_dir';
		$path.= $part. ( $now_file ? str : slash );
		if ( ! @$fx($path) ) $STUCKS[ let($now_file, -1, $i) ] = $part; }
	return is_readable($path); }
#@ {?} Является ли путь к файлу целевым контейнером
## way($PATH) - 'PATH/FILE' & -WRITABLE
## way($PATH, yes) - 'PATH/FILE', +WRITABLE
## way($PATH, $FILE) - 'PATH', 'FILE', -WRITABLE
## way($PATH, $FILE, yes) - 'PATH', 'FILE', +WRITABLE
function way($PATH) {
	if ( so($n, func_num_args()) == 3 ) {
		list ($file, $is_writable) = of(func_get_arg(1), tb(func_get_arg(2)));
	} else if ( 2 == $n ) {
		$file = func_get_arg(1);
		if ( yes($file) ) list ($n, $is_writable) = of(1, yes);
	} else $is_writable = no;
	if ( $n > 1 ) $PATH = cpath($PATH, $file, yes);
	if ( ! ok($ans, road($PATH, yes)) || ! $is_writable ) return $ans;
		else return is_writable($PATH); }
#@ {?} Проложить путь к целевому объекту файловой системы
function track($PATH, $TO_FILE = no, $CHMOD = 0666) {
	## если невозможно или всё уже есть - сразу возвращаем результат
	if ( ! no(so($ans, road($PATH, bit($TO_FILE), $Stuck))) )
		return is($ans) ? is_writable($PATH) : $ans;
	## если нет директории - то нет соответственно и файла
	## если есть директория - то значит ошибка потому что нет файла
	if ( ! is_dir(so($dir, cut($PATH, ti(re(len(who(-1, $Stuck))))))) )
		if ( ! ok($ans, @mkdir($dir, 0777, yes)) || ! $TO_FILE ) return $ans;
	$res = @fclose(@fopen($PATH, 'w')) && is_file($PATH) && is_writable($PATH);
	return $res && chmod($PATH, $CHMOD); }
#@ Загрузка целевого файла (с проверкой формата)
function load($FILE, $MISS = str, $NO = str, $UN = str) {
	if ( ! ok($ans, way($FILE)) ) return no($ans) ? to_text($NO) : to_text($UN);
		else return filesize($FILE) == 0 ? to_text($MISS)
			: file_get_contents($FILE); }
#@ Загрузка целевого файла (без проверки формата)
function read($FILE, $MISS = str, $NO = str, $UN = str) {
	if ( ! is_file($FILE) ) return to_text($UN);
		else if ( ! is_readable($FILE) ) return to_text($NO);
			else if ( filesize($FILE) > 0 ) return file_get_contents($FILE);
				else return to_text($MISS); }
#@ Сохранение целевого файла
## Если целевого пути не существует - то сначала его нужно создать
## Если целевой путь существует - то ничего нельзя трогать
function save($FILE, $DATA = str, $ADD = no, $EOL = nl) {
	if ( so($is_clear, skip($ADD)) ) $ADD = no;
	if ( ! mean($DATA, $EOL) ) return cant;
		else if ( ! ok($ans, track($FILE, yes)) ) return $ans;
	if ( ! l($DATA) && ! $is_clear ) return 0;
	list ($size, $mode) = $ADD ? of(filesize($FILE), 'a') : of(0, 'w');
	if ( $ADD and $size > 0 ) $DATA = $EOL. $DATA;
	if ( ! is_resource(so($fh, @fopen($FILE, $mode)))
		|| ! @flock($fh, LOCK_EX) || ! @fwrite($fh, $DATA) ) return cant;
			else @fflush($fh) && @flock($fh, LOCK_UN) && @fclose($fh)
				&& @clearstatcache(yes, $FILE);
	return filesize($FILE) - $size; }
## Записать в уникальный файл
#@ Очистить файл
function clean($FILE, $INI = str) {
	return ok($r, save($FILE, $INI, skip)) ? eqn($r, len(text($INI))) : $r; }
#@ Получить список объектов файловой системы (unix ls)
function ls($PATH, $MODE = skip) {
	if ( ! ok($ans, road($PATH)) ) return $ans;
	list ($Arr, $Dirs, $o) = of(array($PATH => array()), array($PATH), 0);
	list ($Nodes, $Files) = of(array(0 => & $Arr[$PATH]), array());
	do {
		$Bind = & $Nodes[$o];
		$d = dir(so($dir, $Dirs[$o]));
		while ( is($item = $d->read()) ) {
			if ( dot == $item || dot(2) == $item ) continue;
			if ( is_dir(so($path, cpath($dir, $item))) ) {
				list ($Dirs[], $Bind[$item]) = of($path, array());
				$Nodes[] = & $Bind[$item];
			} else list($Bind[], $Files[]) = of($item, cpath($dir, $item, yes));
		} $d->close();
	} while ( ke(++$o, $Nodes) );
	unset($Arr[0], $Dirs[0]);
	return skip($MODE) ? $Arr : let($MODE, $Files, $Dirs); }
#@ Расчет целевой директории
## OFFS = -1 директория на один блок короче
## 3 показать начиная с 3 (обрезка)
## 3. показать только первые/последние 3
## is_file + 0 показать чистую директорию
function сdir($PATH, $OFFS, $HAS_FILE = no) {
	if ( ! inews($PATH) || ! inum($OFFS) ) return cant;
	$pre = slash == $PATH{0} ? slash : str;
	$path = cut($PATH, strlen($pre));
	if ( ! l($path) ) {
		if ( $HAS_FILE ) return cant;
			else if ( 0 != $OFFS ) return no; }
	$Dirs = u($path, slash);
	if ( $HAS_FILE || miss(trim(end($Dirs))) ) array_pop($Dirs);
	if ( 0 == $OFFS ) return $pre. implode(slash, $Dirs);
	if ( is_float($OFFS) ) {
		if ( so($is_neg, $OFFS < 0) ) $pre = str;
		$need = abs($o = ti($OFFS));
		list ($p1, $j) = $is_neg ? of($o, $need) : of(0, $need - 1);
		$Res = arr::oj($Dirs, $p1, $j);
		if ( few($Res, $need) and inat($OFFS) ) clr($Res);
	} else {
		if ( so($is_ltr, $OFFS >= 0) ) $pre = str;
		list ($p1, $p2) = of(0, abs($OFFS));
		high($p1, $p2, $is_ltr);
		$Res = arr::cc($Dirs, $p1, $p2);
		if ( ! c($Res) ) clr($Res); }
	return res($Res, str, $pre. j($Res, slash). slash); }
#@ Очистка директории
## Если TARGET - шаблон, выполняется очистка файлов по glob
## Если TARGET - no, выполняется очистка всей директории
## Если TARGET - yes, выполняется все что и для no + удаление директории
function erase($PATH, $TARGET = '*.*') {
	if ( is_dir($PATH) ) {
		$ans = yes;
		if ( ilen($TARGET) ) {
			foreach ( glob(cpath($PATH, $TARGET, yes)) as $file )
				$ans = @unlink($file) && ! is_file($file) && $ans;
			$flag = null;
		} else $flag = (bool) $TARGET;
		if ( yes($flag) ) $do_this = yes;
		if ( isset($do_this) || no($flag) ) {
			foreach ( ls($PATH, yes) as $file )
				$ans = @unlink($file) && ! is_file($file) && $ans;
			foreach ( array_reverse(ls($PATH, no)) as $dir )
				$ans = @rmdir($dir) && ! is_dir($dir) && $ans; }
		if ( yes($flag) ) $ans = @rmdir($dir) && ! is_dir($dir) && $ans;
	} else if ( is_file($PATH) ) $ans = @unlink($PATH) && ! is_file($PATH);
	return isset($ans) ? $ans : cant; }
