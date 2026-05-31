<?php
$self_name = 'PageAsk';

class PageAsk extends NodePage {
	public function build() {
		// Legend::set('login');
		// if ( Req::is_post() ) {
		// 	$this->doRegister();
		// }
		Site::title('Обратная связь');
		$tmpl = Env::tmpl($this->iam, 'form');
		$out['post'] = Route::uri();
		$html = tell($tmpl, $out);
		return done(Env::stor($this->iam, 'content', $html));
	}
	public function doRegister() {
		$Post = Req::what('doRegister');
		$res = Pass::create($Post);
		vd($res);
	}
}

return $self_name;
