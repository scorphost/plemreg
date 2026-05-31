<?php
$self_name = 'Page404';

class Page404 extends DeadEndPage {
	public function build() {
		$this->master = no;
		Legend::set('acl');
		Site::title('Ошибка!');
	}
}

return $self_name;
