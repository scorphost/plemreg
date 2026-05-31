<?php
$self_name = 'PageTech';

class PageTech extends DeadEndPage {
	public function build() {
		$this->master = no;
		Site::title('Сайт закрыт!');
		Legend::set('tech');
	}
}

return $self_name;
