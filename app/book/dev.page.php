<?php
$self_name = 'PageDev';

class PageDev extends DeadEndPage {
	public function build() {
		$this->master = no;
		Site::title('Сайт закрыт!');
		Legend::set('dev');
	}
}

return $self_name;
