<?php
function php_val($X, $argSelf = true) {
	$res = '@undef';
	if (is_object($X)) {
		$res = "'@obj:" . get_class($X);
		$d1 = get_parent_class($X);
		$res .= is_string($d1) ? "->${d1}'" : "'";
	} else if (is_resource($X)) {
		$res = "'@res: ";
		$d1 = get_resource_type($X);
		if ('stream' == $d1) {
			$Data = stream_get_meta_data($X);
			$res .= "{$Data['wrapper_type']}(\"{$Data['uri']}\")";
		} else {
			$res .= $d1;
		}
		$res .= "'";
	} else if (is_string($X)) {
		if (strlen($X) > 0) {
			if (is_numeric($X)) {
				$res = "'${X}'";
			} else {
				if ($argSelf) {
					$res = '"' . str_replace('"', '\\"', $X) . '"';
				} else {
					$l2 = mb_strlen($X);
					$c = 0;
					for ($i = 0; $i < $l2; ++$i) {
						$c += intval("\n" === mb_substr($X, $i, 1));
					}
					if ($l2 > 20) {
						$l1 = strlen($X);
						$l2 = $l1 == $l2 ? '' : ":${l2}";
						$l3 = $c > 0 ? "~${c}" : '';
						$res = sprintf('"%s"(%s%s%s)', $X, $l1, $l2, $l3);
					} else {
						$res = sprintf('"%s"', $X);
					}
				}
			}
		} else {
			$res = $argSelf ? "''" : '@nos';
		}
	} else if (is_float($X)) {
		$d1 = (int) $X;
		$res = $X == $d1 ? "${d1}.0" : $X;
	} else if (is_int($X)) {
		$res = (string) $X;
	} else if (is_bool($X)) {
		$res = $argSelf ? '' : '@';
		$res .= $X ? 'true' : 'false';
	} else if (is_null($X)) {
		$res = $argSelf ? 'null' : '@null';
	}

	return $res;
}

function is_post() {
	if(isset($_SERVER['REQUEST_METHOD'])) {
		return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
	}
}

// shutdown(true);
function shutdown() {
	static $is_registered = false;
	if (func_num_args() > 0) {
		$arg = func_get_arg(0);
		if (true === $arg) {
			$is_registered = true;
			register_shutdown_function(__FUNCTION__);
		} else if (is_callable($arg)) {
			$is_registered = true;
			register_shutdown_function($arg);
		}
	} else if ($is_registered) {
		$files1 = $files3 = get_included_files();
		$files2 = array();
		foreach ($files1 as &$file) {
			$files2[] = $file = rpath($file);
		}
		sort($files2);
		$file_name1 = path() . 'inc1-' . DEV_ENTRY . '.log'; // simple as is
		$file_name2 = path() . 'inc2-' . DEV_ENTRY . '.log'; // simple sorted
		$file_name3 = path() . 'inc3-' . DEV_ENTRY . '.log'; // full as is
		file_put_contents($file_name1, implode("\n", $files1));
		file_put_contents($file_name2, implode("\n", $files2));
		file_put_contents($file_name3, implode("\n", $files3));
	}
}

define('IS_POST', is_post());
define('NOW', time());
define('DNOW', date('d', NOW));
define('MNOW', date('m', NOW));
define('YNOW', date('y', NOW));
define('YRNOW', date('Y', NOW));
define('SQL_NOW', date('Y-m-d H:i:s', NOW));

#!
/*
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^(favicon\.ico|robots\.txt)
RewriteRule ^(.*)$ index.php/$1 [L]
*/