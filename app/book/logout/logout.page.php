<?php
$self_name = 'PageLogout';

class PageLogout extends NodePage {
	public function build() {
		Cook::del('uid_token');
		home(homepage);
	}
}

return $self_name;
