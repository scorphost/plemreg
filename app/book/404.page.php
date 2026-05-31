<?php
$self_name = 'Page404';

class Page404 extends DeadEndPage {
	public function build() {
		$this->master = no;
		Site::title('Ошибка!');
		Legend::set('404');
	}
}

return $self_name;
