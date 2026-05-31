<?php
// ПЛЕМІННИЙ СЕРТИФІКАТ
// СЕРТИФІКАТ ВІДПОВІДНОСТІ
// серия и номер в карточку

$self_name = 'GoatCert';

class GoatCert {
	public $id;
	public $page;
	public $theSender;

	protected function as_dol($X, $CNT = 1, $PREC = 3) {
		if ( ! inum($X) ) return cant;
		if ( inat($X) ) return $X. dot. many('0', $CNT);
			else return round($X, $PREC);
	}

	protected function stoodbook($ID) {
		$stdb = know('alias', $ID, 'stoodbook');
		if ( you($stdb, 'ex') ) $res = 'RExB';
			else if ( 'tg' == $stdb ) $res = 'RHB';
				else if ( 'ft' == $stdb ) $res = 'RFB';
					else $res = 'RCB';
		return $res;
	}

	public function postFields() {
		$Post = Req::what('doCert');
		foreach ( $Post as $k => & $val ) {
			if ( strlen($k) > 10 ) {
				if ( ! some($val) ) $val = null;
			} else {
				if ( ! inat($val, pol) ) $val = null; } }
		DBA::line2(1, $Post, 'goats_cert', $this->id, 'id_goat');
		home(of('catalog', 'goats', 'view', $this->id));
	}

	public function show_cert($ID, $PAGE, $OBJ) {
		$this->id = $ID;
		$this->theSender = $OBJ;
		if ( Req::is_post() ) $this->postFields();
		$this->page = $PAGE;
		if ( inat($this->page, pol) ) {
			if ( 2 == $this->page ) {
				return $this->page2();
			} else {
				return $this->page1();
			}
		}
		return false;
	}

	public function page1() {
		$sql1 = xsql::get(_, $this->id, 'animals');
		$sql2 = xsql::get(_, $this->id, 'goats_data', 'id_goat');
		$sql3 = xsql::get(_, $this->id, 'goats_test', 'id_goat'
			, ' ORDER BY `date_test` DESC LIMIT 1');
		#/
		$TAnm = DB::run($sql1, _);
		$TGoat = DB::run($sql2, _);
		$TTest = DB::run($sql3, _);
		// заголовок 1
		$stdb = $this->stoodbook($TGoat['id_stoodbook']);
		$fld['i_hdr1'] = 'RHB' == $stdb ? 'ПЛЕМІННИЙ СЕРТИФІКАТ'
			: 'СЕРТИФІКАТ ВІДПОВІДНОСТІ';
		// заголовок 2
		$fld['i_hdr2'] = str;
		#:
		// Кличка
		$fld['i_name'] = $TAnm['name'];
		//Порода
		$fld['i_breed'] = know('alias', $TGoat['id_breed'], 'breeds');
		//Рогатість
		$horn = shsel($TGoat['horns_type']);
		$res = who($horn, u(',Комолий,Обезрожен,Рогатий'));
		$fld['i_horn'] = $res;
		//Д.нар.
		$fld['i_dob'] = today2($TGoat['date_born']);
		//Породність
		$fld['i_por'] = str;
		//Бал п/н
		$fld['i_score'] = $TGoat['score'];
		//Кровність
		$fld['i_blood'] = str;
		//Масть
		$fld['i_mast'] = str;
		//Пол
		$res = $TAnm['sex'] ? 'Чоловіча' : 'Жиноча';
		$fld['i_sex'] = $res;
		//ID ABG
		$fld['i_code_abg'] = $TGoat['code_abg'];
		//Нар. в числі
		$fld['i_born_qty'] = $TGoat['born_qty'];
		//Екс.оц.,к-ть бал.
		$fld['i_test_score'] = $TTest['score_total'];
		//ID UA
		$fld['i_code_ua'] = $TGoat['code_ua'];
		//Вага п/н
		$fld['i_weight'] = $TGoat['born_weight'];
		//Клас
		$fld['i_class'] = $TTest['class'];
		//Чіп
		$fld['i_code_chip'] = $TGoat['code_chip'];
		//Жива вага
		$fld['i_weight_live'] = str;
		//Заводчик
		$fld['i_manuf'] = $TGoat['manuf'];
		//Плем.кн.
		$fld['i_stdb'] = $stdb;
		//Кіл-ть семʼян.
		$fld['i_semia'] = str;
		#:
		// Лактация колонка "номер"
		$fld['i_lact_no'] = array();
		// Лактация колонка "дней"
		$fld['i_lact_day'] = array();
		// Лактация колонка "удой-кг"
		$fld['i_lact_milk_kg'] = array();
		$fld['i_lact_milk_class'] = array();
		// Лактация колонка "жир-%"
		$fld['i_lact_fat_perc'] = array();
		$fld['i_lact_fat_class'] = array();
		// Лактация колонка "белок-%"
		$fld['i_lact_prot_perc'] = array();
		$fld['i_lact_prot_class'] = array();
		$sql = xsql::get(_, $this->id, 'goats_lact', 'id_goat');
		foreach ( DB::run($sql) as $row => $Data ) {
			$fld['i_lact_no'][$row] = $Data['lact_no'];
			$fld['i_lact_day'][$row] = $Data['lact_days'];
			$fld['i_lact_milk_kg'][$row] = $this->as_dol($Data['milk']);
			$fld['i_lact_fat_perc'][$row] = $this->as_dol($Data['fat']);
			$fld['i_lact_prot_perc'][$row] = $this->as_dol($Data['protein']);
		}
		#:
		$sql = xsql::get(_, $this->id, 'goats_cert', 'id_goat');
		$DbRes = DB::run($sql, _);
		// Лактация колонка "потомство"
		$fld['d_lact_day'] = array(); ## колонка "дней"
		$fld['d_lact_milk_kg'] = array(); ## колонка "удой-кг"
		$fld['d_lact_fat_perc'] = array(); ## колонка "жир-%"
		$fld['d_lact_prot_perc'] = array(); ## колонка "белок-%"
		for ( $i = 1; $i < 6; $i++ ) {
			$row = "id_i_row${i}";
			$id = who($row, $DbRes);
			if ( ! inat($id, pol) ) continue;
				else $sql = xsql::get(_, $id, 'goats_lact');
			$Lact = DB::run($sql, _);
			if ( ! we($Lact) ) continue;
			$fld['d_lact_day'][$i - 1] = $Lact['lact_days'];
			$fld['d_lact_milk_kg'][$i - 1] = $this->as_dol($Lact['milk']);
			$fld['d_lact_fat_perc'][$i - 1] = $this->as_dol($Lact['fat']);
			$fld['d_lact_prot_perc'][$i - 1] = $this->as_dol($Lact['protein']);
		}
		#` Неизвестные поля
		$fld['d_lact_cnt'] = array();
		$fld['d_lact_milk_class'] = array();
		$fld['d_lact_fat_class'] = array();
		$fld['d_lact_prot_class'] = array();
		#:
		$fld['date_release'] = today();
		$tmpl = Sys::tmpl('cert', $this->page);
		return done($this->theSender->html(tell($tmpl, $fld)));
	}
	public function page2() {
		$theTree = Sys::with('goats', 'tree');
		$Pre = u('m,f,mm,fm,mf,ff,mmm,fmm,mfm,ffm,mmf,fmf,mff,fff');
		$DLeg = u('M,F,MM,MF,FM,FF,MMM,MMF,MFM,MFF,FMM,FMF,FFM,FFF');
		$Leg = ax();
		foreach ( $DLeg as $i => $seq ) {
			$lid = $theTree->idof($this->id, $seq);
			if ( inat($lid, pol) ) $Leg[$i] = $lid;
				else $Leg[$i] = null;
		}
		$sql = xsql::get(_, $this->id, 'goats_cert', 'id_goat');
		$DbRes = DB::run($sql, _);
		foreach ( $Leg as $i => $id ) {
			if ( un($id) ) continue;
			$pref = $Pre[$i]. '_';
			$sql1 = xsql::get(_, $id, 'animals');
			$sql2 = xsql::get(_, $id, 'goats_data', 'id_goat');
			$sql3 = xsql::get(_, $id, 'goats_test', 'id_goat'
				, ' ORDER BY `date_test` DESC LIMIT 1');
			#/
			$TAnm = DB::run($sql1, _);
			$TGoat = DB::run($sql2, _);
			$TTest = DB::run($sql3, _);
			// заголовок 1
			$stdb = $this->stoodbook($TGoat['id_stoodbook']);
			#:
			// Кличка
			$fld[$pref. 'name'] = $TAnm['name'];
			//Порода
			$fld[$pref. 'breed'] = know('alias'
				, $TGoat['id_breed'], 'breeds');
			//Д.нар.
			$fld[$pref. 'dob'] = today2($TGoat['date_born']);
			//Породність
			$fld[$pref. 'por'] = str;
			//Бал п/н
			$fld[$pref. 'score'] = $TGoat['score'];
			//Кровність
			$fld[$pref. 'blood'] = str;
			//Масть
			$fld[$pref. 'mast'] = str;
			//ID ABG
			$fld[$pref. 'code_abg'] = $TGoat['code_abg'];
			//Екс.оц.,к-ть бал.
			$fld[$pref. 'test_score'] = $TTest['score_total'];
			//ID UA
			$fld[$pref. 'code_ua'] = $TGoat['code_ua'];
			//Клас
			$fld[$pref. 'class'] = $TTest['class'];
			//Владелец
			$fld[$pref. 'owner'] = $TGoat['owner'];
			//Плем.кн.
			$fld[$pref. 'stdb'] = $stdb;
			#:
			for ( $j = 1; $j < 4; $j++ ) {
				$row = "id_${pref}row${j}";
				$id = who($row, $DbRes);
				if ( ! inat($id, pol) ) continue;
					else $sql = xsql::get(_, $id, 'goats_lact');
				$Lact = DB::run($sql, _);
				if ( ! we($Lact) ) continue;
				$fld[$pref. 'lact_no'][$j - 1] = $Lact['lact_no'];
				$fld[$pref. 'lact_day'][$j - 1] = $Lact['lact_days'];
				$fld[$pref. 'lact_milk_kg'][$j - 1]
					= $this->as_dol($Lact['milk']);
				$fld[$pref. 'lact_fat_perc'][$j - 1]
					= $this->as_dol($Lact['fat']);
				$fld[$pref. 'lact_prot_perc'][$j - 1]
					= $this->as_dol($Lact['protein']);
			}
			$fld[$pref. 'lact_milk_class'] = array();
			$fld[$pref. 'lact_fat_class'] = array();
			$fld[$pref. 'lact_prot_class'] = array();
		}

		foreach ( u('m,f') as $par ) {
			foreach ( u('milk_kg,fat_perc,prot_perc') as $pnt ) {
				$key = "${par}_lact_${pnt}";
				if ( ke($key, $fld) && we($fld[$key]) ) {
					$cnt = $sum = 0;
					foreach ( $fld[$key] as $val ) {
						if ( inum($val, pol) ) {
							$sum+= $val;
							$cnt++;
						}
					}
					if ( $cnt > 0 ) {
						$avg = "${key}_avg";
						$fld[$avg] = round($sum / $cnt, 3);
					}
				}
			}
		}

		list ($_1, $Pre2) = two($Pre, -8);
		foreach ( $Pre2 as $pref ) {
			$fld["${pref}_prod"] = who("id_${pref}_row1", $DbRes);
		}
		$tmpl = Sys::tmpl('cert', $this->page);
		return done($this->theSender->html(tell($tmpl, $fld)));
	}
}

return $self_name;
