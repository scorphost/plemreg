<?php
function dmp_val($val, $argOpts = array(), $kseq = array()) {
	$def_opts = array(
		'ws' => ' ',
		'tab' => 4,
		'native' => false,
		'join' => '/',
		'php7' => false,
	);
	$opts = array_merge($def_opts, $argOpts);
	extract($opts, EXTR_PREFIX_ALL, 'opt');
	if (is_array($val)) {
		if (!empty($val)) {
			$fx = __FUNCTION__;
			$axs1 = $opt_php7 || !$opt_native ? '[' : 'array(';
			$axs2 = $opt_php7 || !$opt_native ? ']' : ')';
			if (!$opt_native && !empty($val)) {
				$c1 = count($val);
				$c2 = count($val, true);
				$t1 = '@arr(' . $c1;
				if ($c2 > $c1) {
					$t1 .= ":${c2}";
				}
				$t1 .= ') ';
				$axs1 = $t1 . $axs1;
			}
			$tab2 = empty($kseq) ? '' : str_repeat(str_repeat($opt_ws, $opt_tab), count($kseq));
			$tab1 = $tab2 . str_repeat($opt_ws, $opt_tab);
			$arr = array();
			foreach ($val as $k1 => $v1) {
				$kx = array_merge($kseq, array($k1));
				$v1 = $fx($v1, $argOpts, $kx);
				if (true === $opt_native) {
					if (is_string($k1)) {
						$k1 = sprintf("'%s'", str_replace("'", "\\'", $k1));
					}
					$arr[] = "${tab1}${k1} => ${v1}";
				} else {
					$q1 = empty($kseq) && is_int($k1) ? '' : "'";
					$arr[] = $tab1 . $q1 . implode($opt_join, $kx) . $q1 . " => ${v1}";
				}
			}
			$res = "${axs1}\n" . implode(",\n", $arr) . ",\n${tab2}${axs2}";
		} else {
			if ($opt_native) {
				$res = $opt_php7 ? '[]' : 'array()';
			} else {
				$res = '@arr[]';
			}
		}
	} else {
		$res = php_val($val, $opt_native);
	}

	return $res;
}

function dbg_path($dir = '.debug', $name = 'default') {
	$request = trim($_SERVER['REQUEST_URI'], '/');
	if (strlen($request) > 0) {
		$Path = explode('/', $request);
		$pre_file = array_pop($Path);
	} else {
		$Path = array();
		$pre_file = 'index';
	}

	$path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/';

	$http_method = strtolower($_SERVER['REQUEST_METHOD']);
	$file_name = $http_method . '-' . str_replace('?', '+', $pre_file) . '.html';

	## root
	$path_root = $path . "${dir}/";
	## based on own name
	$path_name = $path . $dir . "/${name}/";
	## based on time
	$time = time();
	$path_time = $path . $dir . "/${time}/";
	## based on date
	$path_date = $path . "${dir}/" . date('Y-m-d', $time) . '/' . date('h.i.s', $time) . '/';
	## based on entry
	$entry = count($Path) == 0 ? 'index' : implode('/', $Path);
	$path_entry = $path . "${dir}/${entry}/";

	define('DBG_FILE', $file_name);
	define('DBG_PATH_ROOT', $path_root);

	define('DBG_PATH_NAME', $path_name);

	define('DBG_PATH_TIME', $path_time);
	define('DBG_PATH_DATE', $path_date);

	define('DBG_PATH_ENTRY', $path_entry);
}

function qdbg($val, $file = 1) {
	static $fh0 = null;
	static $fh1 = null;
	static $fh2 = null;
	static $fc0 = 0;
	static $fc1 = 0;
	static $fc2 = 0;
	static $time = null;

	if (is_null($time)) {
		$t1 = time();
		$time = sprintf("## %s (%s)\n", date('d/m/Y H:i:s', $t1), $t1);
	}

	if ($file > 0) {
		$num = 1 === $file ? '1' : '2';
	} else {
		$num = '0';
	}

	$ref1 = "fc${num}";
	$ref2 = "fh${num}";

	$hdr = '';
	if (0 == $$ref1) {
		$hdr = "<?php\n";
		if (!empty($num)) {
			$hdr .= $time;
		}
	}

	if (is_null($fh1)) {
		$fh1 = fopen(path() . '_debug1.php', 'w');
		$fh2 = fopen(path() . '_debug2.php', 'w');

		$file0 = path() . '_debug0.php';
		if (file_exists($file0)) {
			if (filesize($file0) > 0) {
				$hdr = "\n${time}";
			}
		}
		$fh0 = fopen($file0, 'a+');
	}

	$tmpl = '$r' . ($$ref1++) . " = %s;\n";
	fwrite($$ref2, $hdr . sprintf($tmpl, php_val($val)));
}

function _g_get($KEY = null, &$RES = null, $OPT) {
	switch ($OPT) {
	case 1:
		$Data = $_GET;
		break;
	case 2:
		$Data = $_POST;
		break;
	case 3:
		$Data = $_COOKIE;
		break;
	case 3:
		$Data = $_SESSION;
	}
	if (func_num_args() == 0) {
		return $Data;
	}
	if (is_array($Data) && count($Data) > 0) {
		$ans = array_key_exists($KEY, $Data);
		$RES = $ans ? $Data[$KEY] : null;
	}
	return isset($ans) ? $ans : false;
}
function _g($KEY = null, &$RES = null) {
	return _g_get($KEY, $RES, 1);
}
function _p($KEY = null, &$RES = null) {
	return _g_get($KEY, $RES, 2);
}
function _c($KEY = null, &$RES = null) {
	return _g_get($KEY, $RES, 3);
}
function _s($KEY = null, &$RES = null) {
	return _g_get($KEY, $RES, 4);
}

function _ss() {
	$upd = true;
	$n = func_num_args();
	if (0 == $n) {
		$upd = count((array) $_SESSION) > 0;
		$_SESSION = array();
		session_destroy();
		return $upd;
	}
	$key = func_get_arg(0);
	if ($n > 1) {
		$upd = array_key_exists($key, $_SESSION);
		$_SESSION[$key] = func_get_arg(1);
	} else {
		unset($_SESSION[$key]);
	}
	return $upd;
}

function _cs() {
	static $expire = false;
	$upd = true;
	$n = func_num_args();
	if (0 == $n) {
		$upd = count($Cookies = (array) $_COOKIE) > 0;
		foreach (array_keys($Cookies) as $k) {
			setcookie($k, null, -1, '/');
			unset($_COOKIE[$k]);
		}
		$_COOKIE = array();
		return $upd;
	}
	$k = func_get_arg(0);
	if ($n > 1) {
		if (!$expire) {
			$expire = time() + (1 * 365 * 24 * 60 * 60);
		}
		setcookie($k, $v = func_get_arg(1), $expire, '/');
		$upd = array_key_exists($k, $_COOKIE);
		$_COOKIE[$k] = $v;
	} else {
		setcookie($k, null, -1, '/');
		unset($_COOKIE[$k]);
	}
	return $upd;
}

define('DBG_HTML',
<<<HTML
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#4A341E">
    <title>PHP-DEBUG v2</title>
</head>
<body style="font-size: 1.1em; background-color: #303030; color: #FAECD3">
    <div style="border: dashed 1px; border-color: #565656; margin: 20px 50px; padding: 0px 20px;">
    <pre style="word-wrap: break-word; word-break: break-all; white-space: pre-wrap;
        font-size: 1.2em; font-family: Liberation Mono;">%s</pre></div></body></html>
HTML
);

class dbg {
	protected static $stage = 0;
	public static $type;
	public static $Vars = array();
	public static $Data = array();

	public static function collect($args, $trace, $selfname) {
		$Data = reset($trace);
		$file = $Data['file'];
		$line = $Data['line'];
		if (empty($args)) {
			self::$Vars[++self::$stage] = array();
			self::$Data[self::$stage] = array($file, $line);
			return;
		}
		$Lines = explode("\n", file_get_contents($file));
		$raw = '<?php ' . $Lines[$line - 1];
		$fin = $opn = 0;
		$pos = $got = false;
		$Del = $Comma = $Info = $Vars = array();
		$Tokens = token_get_all($raw);
		foreach ($Tokens as $k => &$v) {
			if (is_int($pos) && ($k - $pos) === 1 && '(' === $v) {
				$got = $k;
			}
			if (is_int($got) && 0 == $fin && ';' === $v) {
				$fin = $k;
			}
			if (is_array($v)) {
				$v[0] = strtolower(substr(token_name($v[0]), 2));
				if (in_array($v[0], array('whitespace', 'comment'))) {
					$Del[] = $k;
				}
				unset($v[2]);
				if ('string' == $v[0]
					&& strtolower($v[1]) == strtolower($selfname)) {
					$pos = $k;
				}
			}
		}
		unset($v);
		if (0 == $fin--) {
			return false;
		} else {
			$pos++;
		}
		foreach ($Del as $k) {
			unset($Tokens[$k]);
		}
		foreach (array_keys($Tokens) as $k) {
			if ($k <= $pos || $k >= $fin) {
				unset($Tokens[$k]);
			}
		}
		foreach ($Tokens as $k => $v) {
			if ('(' == $v) {
				$opn++;
			} else if (')' == $v) {
				$opn--;
			} else if (',' == $v && 0 == $opn) {
				$Comma[] = $k;
			}
		}
		if (count($Comma) > 0) {
			$i = reset($Comma);
			foreach ($Tokens as $k => $v) {
				if ($k === $i) {
					$Vars[] = $Info;
					$Info = array();
					$i = next($Comma);
				} else {
					$Info[] = $v;
				}
			}
			if (count($Info) > 0) {
				$Vars[] = $Info;
			}
		} else {
			$Vars[] = $Tokens;
		}
		foreach ($Vars as $k => &$Part) {
			$val = '';
			foreach ($Part as $v) {
				$val .= is_array($v) ? $v[1] : $v;
			}
			$Part = $val;
		}
		for ($j = 0, $c = count($Vars), $i = ++self::$stage; $j < $c; $j++) {
			self::$Vars[$i][$Vars[$j]] = $args[$j];
			self::$Data[$i] = array($file, $line);
		}
	}

	public static function html($type = null) {
		if (self::$type === 'table') {
			return self::html_table();
		}
		static $useq = '\0\0[hr}\0';
		$time = time();
		$Res = array(sprintf('Executed: %s [%s]<br />', date('d.m.Y H:i:s', $time), $time));
		$c = count(self::$Vars);
		foreach (self::$Vars as $stage => $EachVals) {
			$Res[] = $stage . '.' . self::$Data[$stage][0] . '{' . self::$Data[$stage][1] . '}';
			if (empty($EachVals)) {
				$Res[] = '...breakpoint...';
				break;
			}
			foreach ($EachVals as $var => $val) {
				$Res[] = htmlspecialchars($var) . ' = ' . htmlspecialchars(dmp_val(
					$val,
					array(
						'ws' => ' ',
						'tab' => 2,
						'native' => false,
						'php7' => false,
					)
				));
			}
			if (--$c > 0) {
				$Res[] = $useq;
			}
		}
		$html = sprintf(DBG_HTML, implode('<br />', $Res));
		return str_replace($useq . '<br />', '<hr />', $html);
	}
	public static function html_table() {
		$Res = array();
		$tmpl0 = '<table style="border: 1px dotted #7E7D5A;width: 100%%;border-collapse: collapse">%s</tr>';
		$tmpl1 = '<tr>%s</tr>';
		$th = '';
		$prev = array();
		$Res[] = self::$Data[1][0] . ':' . self::$Data[1][1] . "\n";
		foreach (self::$Vars as $stage => $EachVals) {
			if (empty($EachVals)) {
				if (strlen($th) == 0) {
					$Res = array('...empty table...');
				}
				break;
			}
			$ax = array();
			$i = 0;
			foreach ($EachVals as $var => $val) {
				$xval = dmp_val(
					$val,
					array(
						'ws' => ' ',
						'tab' => 1,
						'native' => false,
						'php7' => false,
					)
				);
				$diff = '%s';
				if (1 == $stage) {
					$th .= "<th style=\"border: 1px dotted #7E7D5A;color:#FBD091\">${var}</th>";
				} else if ($i > 0 && $prev[$i] !== $val) {
					$diff = '<span style="color: #80FFFF">%s</span>';
				}
				$prev[$i] = $val;
				$ax[$i++] = '<td style="border:1px dotted #7E7D5A;padding-left:12px;vertical-align:top;'
				. (1 == $i ? 'text-align:center;' : '') . '">'
				. sprintf($diff, $xval) . '</td>';
			}
			$Res[] = sprintf($tmpl1, implode("\n", $ax));
		}
		array_unshift($Res, $th);
		return sprintf(DBG_HTML, sprintf($tmpl0, implode("\n", $Res)));
	}
}

function alert() {
	dbg::collect(func_get_args(), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), __FUNCTION__);
	die(dbg::html());
}
function alertd() {
	dbg::collect(func_get_args(), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), __FUNCTION__);
}
function alerti() {
	dbg::$type = 'table';
	dbg::collect(func_get_args(), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), __FUNCTION__);
}

function vdb($text) {
	static $tmpl = <<<HTML
<script>
    function cb_copy() {
    	var box = document.getElementById('dbg_box');
        box.select();
        if (document.execCommand('copy')) alert('Copied ' + box.innerHTML.length + ' chars');
        box.blur();
    }
</script>
<center><textarea style="background-color:transparent; color:inherit; font-size:1.3em;"
	rows="24" cols="100" id="dbg_box">%s</textarea><br />
<button style="height: 40px; width: 164px;" onclick="cb_copy();">COPY</button></center>
HTML;
	if (!is_string($text) && !is_int($text) && !is_float($text)) {
		$text = dmp_val($text, array('ws' => ' ', 'tab' => 2, 'native' => true));
	}
	die(sprintf(DBG_HTML, sprintf($tmpl, $text)));
}
