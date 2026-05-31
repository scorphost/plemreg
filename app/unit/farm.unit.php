<?php

$self_name = 'UnitFarm';

class UnitFarm {
	public function args() {}
	public function __toString() {
		$sql = xsql::get(u('id,name'), no, 'farms', _, ' ORDER BY `id`');
		$Data = DB::run($sql);
		$Col = col($Data, 'name');
		$html = str;
		foreach ( av($Col) as $i => $v)
			$html.= tag::p(atag($v, uri('farms', $Data[$i]['id'])));
		return $html;
	}

}

return $self_name;
