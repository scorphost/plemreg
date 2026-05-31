<?php
$self_name = 'PageDBe';

class PageDBe extends DeadEndPage {
	public function build() {
		$this->master = no;
		Site::title('Ошибка!');
		Legend::set('bad');
	}
}


return $self_name;
