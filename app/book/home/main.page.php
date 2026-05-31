<?php
$self_name = 'PageMain';

class PageMain extends NodePage
{
	public function build() {
		Legend::set('home');
		Site::title('Главная страница');
		$html = tell(Sys::tmpl($this->iam, 'welcome'), ax());
		return done(Env::stor($this->iam, 'content', $html));
	}
}

return $self_name;
