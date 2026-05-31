<?php
if (!function_exists('token_get_all')) {
    die('tokenizer doesnt exists...');
}

if (!defined('DEV_IPS')) {
    define('DEV_IPS', '31.202.146.189');
}

/*
Usage:

_dir1/file1.(self::$display)_
$r = 123;
alert($r); //_dir1/file1._
alert($r); //d

#2 - skip cycle=1, show on cycle=2
for ($i = 1; $i < 5; $i++) {
alert($i); //#2
}

? - if first argument is true
$r = 123;
alert($r == 123, $r); //?

!! p - on post,put etc.

!!w - upload remotely (high priority)
!!l - local

t - for text
j - for json

, - don't stop now

d - write data to the directory

!!+ - payload(GPSC)
!!g - global trace
!!i - files
 */
if (!class_exists('dump13')) {
    class dump13
    {
        const LOCAL_UPLOAD_URL = 'http://dump.sys';
        public static $upload_url = 'http://dump.kl.com.ua';

        public static $html = DUMP13_HTML_HEADER;
        public static $debug_dir = '.dump13';
        public static $debug_uid = 'default';
        public static $time = false;

        protected static $stage = 0;
        protected static $Vars = array();
        protected static $Data = array();

        public static $Trace;
        protected static $Payload;

        protected static $i = 1;
        protected static $output = 'browser';
        protected static $display = 'html';
        protected static $location = '';
        protected static $payload = false;

        protected static $collect_more = false;

        protected static function get_dbg_path()
        {
            $request = trim($_SERVER['REQUEST_URI'], '/');
            if (strlen($request) > 0) {
                $Path = explode('/', $request);
                $pre_file = array_pop($Path);
            } else {
                $pre_file = 'index';
            }
            $entry = empty($Path) ? 'index' : implode('/', $Path);

            $http_method = strtolower($_SERVER['REQUEST_METHOD']);
            $file_name = $http_method . '-' . str_replace('?', '', str_replace('&', ',', $pre_file)) . '.';

            $Res = array(
                rtrim($_SERVER['DOCUMENT_ROOT'], '/'),
                self::$debug_dir,
                self::$debug_uid,
            );
            if (self::$time) {
                $Res[] = date('Y-m-d_H.i.s', time());
            }
            $Res[] = $entry;

            return array(implode('/', $Res), $file_name);
        }

        protected static function trace()
        {
            $Trace = array_reverse(self::$Trace);
            array_pop($Trace);

            $Res = array();
            $tmpl = '%s %s(%s)';
            foreach ($Trace as $EachItem) {

                $Args = array();
                $is_inc = false;
                $fx = '';
                if (isset($EachItem['class'])) {
                    $fx = $EachItem['class'] . $EachItem['type'];
                } else {
                    $is_inc = in_array(strtolower($EachItem['function']),
                        array('require_once', 'include_once', 'require', 'include'));
                }
                if ($is_inc) {
                    $call = 'Included';
                    $fx = '';
                    $Args = array("'" . $EachItem['args'][0] . "'");
                } else {
                    $call = 'Called';
                    $fx .= $EachItem['function'];
                    $Args = array();
                    if (isset($EachItem['args']) && is_array($EachItem['args']) && !empty($EachItem['args'])) {
                        foreach ($EachItem['args'] as $each_arg) {
                            if (is_array($each_arg)) {
                                $Args[] = '@a[' . count($each_arg) . ']';
                            } else if (is_object($each_arg)) {
                                $t1 = "'@obj:" . get_class($each_arg);
                                $d1 = get_parent_class($each_arg);
                                $t1 .= is_string($d1) ? "->${d1}'" : "'";
                                $Args[] = $t1;
                            } else if (is_string($each_arg)) {
                                $l = mb_strlen($each_arg);
                                $Args[] = $l > 32 ? "@s[${l}]" : $each_arg;
                            } else {
                                $Args[] = var_export($each_arg, true);
                            }
                        }
                    }
                }
                $Res[] = sprintf($tmpl, $call, $fx, implode(', ', $Args))
                    . ' from ' . $EachItem['file'] . '{' . $EachItem['line'] . '}';
            }

            // $trace = "\n" . implode("\n", $Res) . "\n";
            return $Res;
        }

        protected static function set_output($opts)
        {
            self::$collect_more = false;
            if (strlen($opts) == 0) {
                return null;
            }

            // always check ip for remote calls
            if (!dev_ips()) {
                return false;
            }

            if (1 == self::$stage) {
                //_dir1/file1.(self::$display)_
                $to_dir = '';
                $has_file = false;
                $opts = preg_replace_callback('/_(.+)_/Uis', function ($matches) use (&$to_dir, &$has_file) {
                    $to_dir = rtrim($matches[1], '/');
                    if (mb_substr($to_dir, 0, 1) == '/') {
                        $to_dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . "${to_dir}";
                    }
                    $has_file = is_int(strpos($matches[1], '.'));
                    return 'd';
                }, $opts);
            }

            // #2 - skip cycle=1, show on cycle=2
            $cycle = 0;
            $opts = preg_replace_callback('/#(\d+)/Uis', function ($matches) use (&$cycle) {
                if ($matches[1] > 0) {
                    $cycle = $matches[1];
                }
                return '';
            }, $opts);
            if ($cycle > 0 && $cycle != self::$i++) {
                return false;
            }

            // p - on post,put etc.
            if (is_int(strpos($opts, 'p'))) {
                $opts = str_replace('p', '', $opts);
                if (strtolower($_SERVER['REQUEST_METHOD']) === 'get') {
                    return false;
                }
            }

            // ? - if first argument is true
            if (is_int(strpos($opts, '?'))) {
                $opts = str_replace('?', '', $opts);
                $vars = self::$Vars[self::$stage];
                if (!reset($vars)) {
                    return false;
                }
                unset(self::$Vars[self::$stage][key($vars)]);
            }

            if (1 == self::$stage) {

                // w - upload remotely (high priority)
                // l - local
                $web1 = is_int(strpos($opts, 'w'));
                $web2 = $web1 ? false : is_int(strpos($opts, 'l'));

                if ($web1 || $web2) {
                    $opts .= 'j';
                    self::$location = $web1 ? self::$upload_url : self::LOCAL_UPLOAD_URL;
                    if ($web2) {
                        // if local then + payload and included files
                        $opts .= '+gh';
                    }
                    self::$output = 'curl';

                    // not text and not any directories
                    $opts = str_replace('t', '', $opts);
                    $opts = str_replace('d', '', $opts);
                }

                // t - for text
                if (is_int(strpos($opts, 't'))) {
                    $opts = str_replace('t', '', $opts);
                    self::$display = 'txt';
                }

                // j - for json
                if (is_int(strpos($opts, 'j'))) {
                    $opts = str_replace('j', '', $opts);
                    self::$display = 'json';
                }
            }

            //, - don't stop now
            if (is_int(strpos($opts, ','))) {
                self::$collect_more = true;
                $opts = str_replace(',', '', $opts);
            }

            //! - cycle debugging
            if (is_int(strpos($opts, '!'))) {
                self::$collect_more = true;
                $opts = str_replace('!', '', $opts);
                self::$output = 'table';
            }

            if (1 == self::$stage) {
                //d - write data to the directory
                if (is_int(strpos($opts, 'd'))) {
                    self::$output = 'file';
                    list($d, $f) = self::get_dbg_path();
                    if (strlen($to_dir) > 0) {
                        $d = $to_dir;
                        if ($has_file) {
                            $f = '';
                        }
                    } else {

                    }
                    if (strlen($f) > 0) {
                        $f = "/${f}";
                    }
                    self::$location = "${d}${f}" . self::$display;
                }

                // + - payload(GPSC)
                if (is_int(strpos($opts, '+'))) {
                    $opts = str_replace('+', '', $opts);
                    if (isset($_GET) && is_array($_GET)) {
                        self::$Payload['get'] = $_GET;
                    }
                    if (isset($_POST) && is_array($_POST)) {
                        self::$Payload['post'] = $_POST;
                    }
                    if (isset($_SESSION) && is_array($_SESSION)) {
                        self::$Payload['session'] = $_SESSION;
                    }
                    if (isset($_COOKIE) && is_array($_COOKIE)) {
                        self::$Payload['cookie'] = $_COOKIE;
                    }

                    $raw = file_get_contents('php://input');
                    if (strlen($raw) > 0) {
                        self::$Payload['raw'] = $raw;
                    }
                }
            }

            // g - global trace
            if (is_int(strpos($opts, 'g'))) {
                $opts = str_replace('g', '', $opts);
                self::$Payload['trace'][self::$stage] = self::trace();
            }

            // i - files
            if (1 == self::$stage) {
                if (is_int(strpos($opts, 'i'))) {
                    $opts = str_replace('i', '', $opts);
                    $inc1 = get_included_files();
                    $inc2 = array();
                    foreach ($inc1 as $ei) {
                        $inc2[] = str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '',
                            str_replace('\\', '/', $ei));
                    }
                    asort($inc2);
                    self::$Payload['includes1'] = $inc1;
                    self::$Payload['includes2'] = $inc2;
                }
            }

            /*
            alert(false) - fallback
            alert(true) - all ok
            alert(0) - default
            alert(1-n) - case n
             */

            if (PHP_SAPI == 'cli' && self::$display == 'browser' && self::$output == 'html') {
                self::$display = 'console';
                self::$output = 'txt';
            }

            return true;
        }

        public static function collect($args, $selfname)
        {
            $Data = reset(self::$Trace);
            $line = $Data['line'];
            $file = $Data['file'];

            $file1 = str_replace('\\', '/', $file);
            if (strlen($_SERVER['DOCUMENT_ROOT']) > 0) {
                $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
                if (strpos($file1, $root) === 0) {
                    $file1 = str_replace($root, '.', $file1);
                }
            }

            if (empty($args)) {
                self::$Vars[++self::$stage] = array();
                self::$Data[self::$stage] = array($file, $line, $file1);
                $out = call_user_func(
                    array(__CLASS__, PHP_SAPI == 'cli' ? 'show_txt' : 'show_html')
                );
                die($out);
            }

            $Lines = explode("\n", file_get_contents($file));
            $fin = $opn = 0;
            $pos = $got = false;
            $Del = $Comma = $Info = $Vars = array();

            $opts = explode(';', strrev($Lines[$line - 1]), 2);
            $opts = trim(strrev(trim(reset($opts))), '//');

            $Tokens = token_get_all('<?php ' . $Lines[$line - 1]);
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
                self::$Data[$i] = array($file, $line, $file1);
            }

            if (false === self::set_output($opts)) {
                unset(self::$Vars[self::$stage], self::$Data[self::$stage]);
                self::$stage--;
                return false;
            }

            if (strlen($opts) == 0 && PHP_SAPI == 'cli') {
                self::$display = 'txt';
            }

            if (!self::$collect_more) {
                $out = call_user_func(array(__CLASS__, 'show_' . self::$display));
                if ('file' == self::$output) {
                    @mkdir(dirname(self::$location), 0777, true);
                    file_put_contents(self::$location, $out);
                    $bdir = rtrim(dirname(self::$location), '/\\');
                    if (!empty(self::$Payload)) {
                        foreach (array('get', 'post', 'session', 'cookie') as $ek) {
                            if (!empty(self::$Payload[$ek])) {
                                foreach (self::$Payload[$ek] as $elk => &$el) {
                                    $el = "{$elk}: {$el}";
                                }
                                file_put_contents(
                                    "{$bdir}/{$ek}.txt",
                                    implode("\n", self::$Payload[$ek])
                                );
                            }
                        }
                        if (!empty(self::$Payload['trace'])) {
                            foreach (self::$Payload['trace'] as $stage => &$el) {
                                $el = "Stage {$stage}:\n\n" . implode("\n\n", $el);
                            }
                            file_put_contents(
                                "{$bdir}/trace.txt",
                                implode("\n", self::$Payload['trace'])
                            );
                        }
                        foreach (array('includes1', 'includes2') as $ek) {
                            if (!empty(self::$Payload[$ek])) {
                                foreach (self::$Payload[$ek] as $elk => &$el) {
                                    $el = "{$elk}: {$el}";
                                }
                                file_put_contents(
                                    "{$bdir}/{$ek}.txt",
                                    implode("\n", self::$Payload[$ek])
                                );
                            }
                        }
                    }
                } else if ('curl' == self::$output) {
                    self::curl_send($out);
                    die('curl sent!');
                } else {
                    die($out);
                }
            }
        }

        protected static function curl_send($data)
        {
            $post = array('dump_data' => base64_encode($data));
            $options = array(
                CURLOPT_URL => self::$location,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/65.0.3325.181 Chrome/65.0.3325.181 Safari/537.36',

                CURLOPT_HEADER => 0,

                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,

                CURLOPT_TIMEOUT => 180,

                CURLOPT_FAILONERROR => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FRESH_CONNECT => 1,

                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post,
            );

            $ch = @curl_init();
            foreach ($options as $k => $v) {
                curl_setopt($ch, $k, $v);
            }

            $http = curl_exec($ch);

            echo "<pre>cURL response:";
            // echo htmlspecialchars(var_export($http, true));
            echo curl_error($ch);
            echo '</pre>';
            die;

            @curl_close($ch);
        }

        protected static function val_x($X, $in_array = true)
        {
            $res = '@undef';
            $eol = $in_array ? ',' : ';';

            if (is_object($X)) {
                $res = "'@obj:" . get_class($X);
                $d1 = get_parent_class($X);
                $res .= is_string($d1) ? "->${d1}'" : "'";
                $res .= $eol;
            } else if (is_resource($X)) {
                $res = "'@res: ";
                $d1 = get_resource_type($X);
                if ('stream' == $d1) {
                    $Data = stream_get_meta_data($X);
                    $res .= "{$Data['wrapper_type']}(\"{$Data['uri']}\")";
                } else {
                    $res .= $d1;
                }
                $res .= "'${eol}";
            } else if (is_string($X)) {
                if (strlen($X) > 0) {
                    if (is_numeric($X)) {
                        $res = "'${X}'${eol}";
                    } else {
                        $qc1 = substr_count($X, "'");
                        $qc2 = substr_count($X, '"');

                        if ($qc1 == 0 && $qc2 > 0) {
                            $res = "'${X}'";
                        } else if ($qc1 > 0 && $qc2 == 0) {
                            $res = '"' . $X . '"';
                        } else {
                            // $res = '"' . str_replace('"', '\\"', $X) . '"';
                            $res = '"' . $X . '"';
                        }
                        $res .= $eol;
                    }
                } else {
                    $res = "''" . $eol;
                }
            } else if (is_float($X)) {
                $d1 = (int) $X;
                $res = $X == $d1 ? "${d1}.0" : $X;
            } else if (is_int($X)) {
                $res = (string) $X;
                $res .= $eol;
            } else if (is_bool($X)) {
                $res = '';
                $res .= $X ? 'true' : 'false';
                $res .= $eol;
            } else if (is_null($X)) {
                $res = "null${eol}";
            }

            return $res;
        }

        protected static function val_php($val, $kseq = array(), $in_array = false)
        {
            $obj = false;

            $eol = $in_array ? ',' : ';';

            if (is_object($val)) {
                $obj = $val;
                if (class_exists('ReflectionObject')) {
                    $result = array();
                    $refObj = new ReflectionObject($val);
                    $props = $refObj->getProperties(
                        ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
                    );
                    for ($i = 0, $pCount = count($props); $i < $pCount; $i++) {
                        $props[$i]->setAccessible(true);
                        $result['prop: $' . $props[$i]->getName()]
                        = $props[$i]->getValue($val);
                    }
                    $val = $result;
                    asort($val);
                } else {
                    $result = (array) $val;
                    if (is_array($result) && !empty($result)) {
                        $val = array();
                        foreach ($result as $k => $v) {
                            $val['prop: $' . $k] = $v;
                        }
                    }
                }
            }

            if (is_array($val)) {
                if (!empty($val)) {
                    $fx = array(__CLASS__, __METHOD__);
                    $jj = $obj ? ' //' . str_replace("'", '', self::val_x($obj)) : '';
                    $axs1 = "array({$jj}";
                    $axs2 = ')';
                    $tab2 = empty($kseq) ? '' : str_repeat(str_repeat(' ', 4), count($kseq));
                    $tab1 = $tab2 . str_repeat(' ', 4);
                    $arr = array();
                    foreach ($val as $k1 => $v1) {
                        $kx = array_merge($kseq, array($k1));
                        $v1 = call_user_func($fx, $v1, $kx, true);
                        if (is_string($k1)) {
                            $k1 = sprintf("'%s'", str_replace("'", "\\'", $k1));
                        }
                        $arr[] = "${tab1}${k1} => ${v1}";
                    }
                    $res = "${axs1}\n" . implode("\n", $arr)
                        . "\n${tab2}  ${axs2}" . ($in_array ? ',' : ';');
                } else {
                    $res = "array()${eol}";
                }
            } else {
                $res = self::val_x($val, $in_array);
            }

            return $res;
        }

        protected static function show_json()
        {
            $Res = array(
                'vars' => array(),
                'data' => array(),
            );

            foreach (self::$Vars as $stage => $EachVals) {
                $Res['data'][$stage] = array(self::$Data[$stage][1] => self::$Data[$stage][2]);
                if (empty($EachVals)) {
                    $Res['data'][$stage][] = '...breakpoint...';
                    break;
                }
                foreach ($EachVals as $var => $val) {
                    $Res['vars'][$stage][$var] = self::val_php($val);
                }
            }

            return json_encode($Res);
        }

        protected static function show_txt()
        {
            $Res = array();
            foreach (self::$Vars as $stage => $EachVals) {
                $Res[] = str_repeat('-', 40) . "\n"
                . $stage . ' ' . self::$Data[$stage][2] . '{' . self::$Data[$stage][1] . '}';
                if (empty($EachVals)) {
                    $Res[] = "\n...breakpoint...";
                    break;
                }
                foreach ($EachVals as $var => $val) {
                    $v1 = $var;
                    $v2 = self::val_php($val);
                    $Res[] = "\n" . ($var === $val ? $v2 : "${v1} = ${v2}");
                }
            }
            return implode("\n", $Res);
        }

        protected static function show_html()
        {
            $__rs = isset($_SERVER['REQUEST_SCHEME']) ? array($_SERVER['REQUEST_SCHEME'])
            : explode('/', $_SERVER['SERVER_PROTOCOL']);
            $this_url = strtolower(reset($__rs)) . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

            $Res = array(
                'url: <span style="cursor:pointer" onclick="var_copy(this); return false;">'
                . "${this_url}</span><br />"
                . sprintf('Entry: %s | ', $_SERVER['SCRIPT_NAME'])
                . sprintf('Executed: %s [%s]<br />', date('r', time()), time()),

            );
            $i = 1;
            foreach (self::$Vars as $stage => $EachVals) {
                $Res[] = '<hr />' . $stage . ' ' . self::$Data[$stage][2] . '{' . self::$Data[$stage][1] . '}';
                if (empty($EachVals)) {
                    $Res[] = '<br />...breakpoint...';
                    break;
                }
                foreach ($EachVals as $var => $val) {
                    // $Res[] = htmlspecialchars($var, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8')
                    $v1 = htmlspecialchars($var);
                    $v2 = self::val_php($val);
                    $id = 'id-el-' . $i;
                    $i++;
                    // $Res[] = '<br />  ' . ($var === $val ? $v2
                    //     : '<span style="cursor:pointer" onclick="var_copy(this); return false;">'
                    //     . "${v1} = ${v2}</span>");

                    $Res[] = '<br />  ' . ($var === $val ? $v2
                        : "{$v1} " . self::val_copy('= ', $val, 'val copy', 'val copied ok!')
                        . self::res_copy($v2, 'title1', 'copied ok!'));
                }
            }

            return sprintf(self::$html, implode('', $Res));
        }

        protected static function val_copy($show, $value, $title = '', $text = '')
        {
            if (strlen($title) > 0) {
                $title = ' title="' . $title . '"';
            }

            if (!is_string($value) || !is_int($value) || !is_float($value)) {
                return htmlspecialchars($show);
            }

            return '<span style="cursor:pointer" onclick="val_copy(\'' .
            str_replace("'", "\\'", $value) . '\', \'' .
            $text . '\'); return false;"' . $title . '>'
            . htmlspecialchars($show) . "</span>";

        }

        protected static function res_copy($value, $title = '', $text = '')
        {
            if (strlen($title) > 0) {
                $title = ' title="' . $title . '"';
            }
            return '<span style="cursor:pointer" onclick="res_copy(this, \'' .
            $text . '\'); return false;"' . $title . '>'
            . htmlspecialchars($value) . "</span>";
        }
    }
} else {
    die('Class dump13 already exists!');
}

if (!function_exists('alert')) {
    function alert()
    {
        dump13::$Trace = debug_backtrace();
        dump13::collect(func_get_args(), __FUNCTION__);
    }
} else {
    die('Function alert() already exists!');
}

/*
1. add @BotFather, create new broadcast bot, save it's token
2. create private chat
3. add my bot and @get_id_bot
4. run /my_id @get_id_bot to get channel's chat_id
 */
function tg_log($message)
{

    static $token = '1829188609:AAHHEh5D9blx71EQth1IgOG6CB5wT4QNasI'; // http://t.me/log_baria_bot
    static $chat_id = '-1001553179956';

    static $url = null;

    if (!dev_ips()) {
        return false;
    }

    $host = get_val('HTTP_HOST', $_SERVER, 'unknown');
    $ip = get_val('SERVER_ADDR', $_SERVER, 'localhost');

    $message = sprintf(
        "<code>Message from %s (%s)</code>\n%s",
        $host,
        $ip,
        $message
    );

    if (!isset($url)) {
        $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html";
        /*
    parse_mode=html
    <b>bold</b>, <strong>bold</strong>
    <i>italic</i>, <em>italic</em>
    <a href="http://www.example.com/">inline URL</a>
    <code>inline fixed-width code</code>
    <pre>pre-formatted fixed-width code block</pre>
    parse_mode=markdown
     *bold*  _italic_
     */
    }

    $hcurl = curl_init();
    $Opts = array(
        CURLOPT_URL => $url . "&text=" . urlencode($message),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
    );

    curl_setopt_array($hcurl, $Opts);
    $json = curl_exec($hcurl);
    curl_close($hcurl);

    return json_decode($json, true);
}

define('DUMP13_HTML_HEADER', <<<HTML
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#4A341E">
    <title>PHP-DUMP v0.3</title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"
        defer="defer"></script>
       <script>
            function res_copy(e, text = false) {
                let textArea = document.createElement("textarea");
                let txt = "Copied " + e.innerHTML.length + " chars";
                if(text) {
                    txt = text;
                }
                textArea.value = e.textContent;
                document.body.appendChild(textArea);
                textArea.select();
                if(document.execCommand("Copy")) {
                    alert(txt)
                }
                textArea.remove();
            }

            function val_copy(val, text = false) {
                let textArea = document.createElement("textarea");
                let txt = "Copied " + val.length + " chars";
                if(text) {
                    txt = text;
                }
                textArea.value = val;
                document.body.appendChild(textArea);
                textArea.select();
                if(document.execCommand("Copy")) {
                    alert(txt)
                }
                textArea.remove();
            }

            function do_ajax() {
                alert('do ajax');
            }
        </script>

</head>
<body style="background-color: #212927; color: #FAE6CD;" >
    <div style="border: dashed 1px; border-color: #565656; margin: 20px 50px; padding: 0px 20px; text-align: left; padding-left: 5%%; line-height: 2em !important">

        <form style="margin-top: 12px" enctype="multipart/form-data" method="POST"
            action="http://main.src/action.php" id="id-test-form1">
            <input type="text" name="test_field1" id="id-field1" value="aaa"><br>
            <input type="submit" name="form_submit_1" value="POST"
                >&nbsp;<input type="button" name="form_submit_2" value="AJAX"
                onclick="do_ajax(); return false"
                >&nbsp;<input type="file" name="test-files[]" size="60" multiple="multiple" />
        </form>
        <span id="dump-answer"></span>

    <pre style="word-wrap: break-word; word-break: break-all; white-space: pre-wrap;
        font-size: 20px; font-family: 'IBM Plex Mono', Arial; text-shadow: 2px 2px 2px #131313">%s</pre></div></body></html>
HTML
);

if (!function_exists('dev_ips')) {
    function dev_ips()
    {
        if (!isset($_SERVER['SERVER_ADDR']) || strlen($_SERVER['SERVER_ADDR']) == 0) {
            return true;
        }
        if (preg_match('/^127\.0\.[0-1]\.1$/', $_SERVER['SERVER_ADDR'])) {
            return true;
        }

        foreach (explode(';', DEV_IPS) as $ip) {
            if ($_SERVER['REMOTE_ADDR'] === $ip) {
                return true;
            }
        }
        return false;
    }
}
