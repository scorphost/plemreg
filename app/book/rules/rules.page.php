<?php
$self_name = 'PageRules';

class PageRules extends NodePage {
	public function build() {
		Legend::set('rule');
		Site::title('Правила');
		$tmpl = Sys::tmpl($this->iam, 'welcome');
		return done(Env::stor($this->iam, 'content', tell($tmpl, ax())));
	}
}

return $self_name;
