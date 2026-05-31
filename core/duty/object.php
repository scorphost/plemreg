<?php
abstract class DeadEndPage {
	protected $redir;
	protected $iam;
	protected $master = yes;
	protected $is_direct;

	abstract protected function build();

	public function __construct($I, $NAME) {
		$this->is_direct = 0 == $I;
		$this->iam = ltrim($NAME, '/+');
		Sess::set('back', Route::make(homepage));
	}
	public function redir() { return $this->redir; }
	public function hitch() { return no; }
	public function __toString() {
		return Render::show(Sys::tmpl($this->iam), $this->master);
	}
}

abstract class NodePage {
	protected $redir = no;
	protected $iam;
	protected $html; 		## субобъект хочет разместить свои данные отдельно
	protected $master = no;	## отдельная страница (не в контексте root.tmpl)

	abstract public function build();

	/*
	рендер занят, буфер заполнен
	драйвер ДБ выдал ошибку
	*/

	public function __construct($I, $NAME) {
		$this->iam = ltrim($NAME, '/+');
		Sess::set('back', $_SERVER['REQUEST_URI']);
	}
	#@ Уже есть помеха в рендеринге страницы
	public function hitch() {
		if ( hitch(yes) ) return done($this->fall(hitch()));
			else return no;
	}
	public function redir() { return $this->redir; }
	#@ Падаем с указанной страницей ошибки (в зависимости от контекста)
	public function fall($PAGE) { return ino($this->redir = $PAGE); }
	public function as_name() {
		if ( func_num_args() == 0 ) return $this->iam;
			else return $this->iam = $NAME;
	}
	## Вывод в браузер
	public function __toString() {
		return she($this->html)	? $this->html
			: Render::show(Sys::tmpl($this->iam, slash), $this->master);
	}
	public function html($HTML) { return $this->html = $HTML; }
	## Отладочный блок для тяжелых шаблонов
	public function tvars() {
		$tmpl = Sys::tmpl($this->iam, slash);
		tell($tmpl, ax(), $Seq);
		return $Seq;
	}
}
